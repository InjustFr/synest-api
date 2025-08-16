<?php

declare(strict_types=1);

namespace App\Core\Domain\Entity;

use App\Core\Domain\Event\ServerCreatedEvent;
use App\Core\Domain\Shared\Assertion;
use App\Core\Domain\Shared\ContainsEventsInterface;
use App\Core\Domain\Shared\PrivateEventRecorderTrait;
use App\Core\Domain\Shared\RecordsEventsInterface;
use App\Infrastructure\Doctrine\Type\NonEmptyStringType;
use Assert\Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
class Server implements RecordsEventsInterface, ContainsEventsInterface
{
    use PrivateEventRecorderTrait;

    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME)]
    private Ulid $id;

    /**
     * @var non-empty-string
     */
    #[ORM\Column(type: NonEmptyStringType::TYPE)]
    private string $name;

    #[ORM\ManyToOne(User::class)]
    #[JoinColumn(nullable: false)]
    private User $owner;

    /**
     * @var ArrayCollection<array-key, User>
     */
    #[ORM\ManyToMany(User::class, mappedBy: 'servers', cascade: ['persist'])]
    private Collection $users;

    /**
     * @var ArrayCollection<array-key, ServerSettingValue>
     */
    #[ORM\OneToMany(ServerSettingValue::class, mappedBy: 'server', cascade: ['persist', 'remove'])]
    private Collection $settingValues;

    private function __construct(string $name, User $owner)
    {
        Assert::that($name)->notBlank('Name can not be blank');
        Assert::that($name)->maxLength(255, 'Name is too long.');

        $this->id = new Ulid();
        $this->name = $name;
        $this->owner = $owner;

        $this->users = new ArrayCollection();
        $this->settingValues = new ArrayCollection();
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function rename(string $name): void
    {
        Assert::that($name)->notBlank('Name can not be blank');
        Assert::that($name)->maxLength(255, 'Name is too long.');

        $this->name = $name;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function changeOwner(User $owner): void
    {
        Assertion::inArray($owner, $this->users->toArray(), 'Could not find new owner in subscribed users');

        $this->owner = $owner;
    }

    /**
     * @return User[]
     */
    public function getUsers(): array
    {
        return $this->users->toArray();
    }

    public function subscribeUser(User $user): void
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addServer($this);
        }
    }

    public function unsubscribeUser(User $user): void
    {
        Assert::that($user)->notEq($this->owner, 'Can\'t remove a user from a server they own');

        if ($this->users->removeElement($user)) {
            $user->removeServer($this);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function getSettings(): array
    {
        $settings = [];
        foreach ($this->settingValues as $value) {
            $settings[$value->getServerSetting()->getKey()] = $value->getValue();
        }

        return $settings;
    }

    private function findSettingValue(ServerSetting $setting): ?ServerSettingValue
    {
        return $this->settingValues->findFirst(
            fn (int|string $k, ServerSettingValue $value): bool => $value->getServerSetting() === $setting
        );
    }

    public function getSettingValue(ServerSetting $serverSetting): mixed
    {
        $settingValue = $this->findSettingValue($serverSetting);

        return null !== $settingValue ? $settingValue->getValue() : $serverSetting->getDefaultValue();
    }

    public function changeSetting(ServerSetting $serverSetting, mixed $value): void
    {
        Assert::that($value)->scalar('Value must be scalar.');
        Assert::that(\gettype($value))
            ->eq(
                $serverSetting->getType(),
                \sprintf('Value for setting %s is not of type %s.', $serverSetting->getKey(), $serverSetting->getType())
            );

        $settingValue = $this->findSettingValue($serverSetting);

        if (null !== $settingValue) {
            $settingValue->changeValue($value);

            return;
        }

        $this->settingValues[] = ServerSettingValue::create($serverSetting, $this, $value);
    }

    public function resetSettingToDefault(ServerSetting $serverSetting): void
    {
        $settingValue = $this->findSettingValue($serverSetting);

        Assertion::notNull($settingValue, 'Could not find value to remove');

        $this->settingValues->removeElement($settingValue);
    }

    public static function create(
        string $name,
        User $owner,
    ): self {
        $self = new self($name, $owner);

        $self->subscribeUser($owner);

        $self->record(new ServerCreatedEvent(
            $self->id,
            $self->name,
            $self->owner->getId()
        ));

        return $self;
    }
}
