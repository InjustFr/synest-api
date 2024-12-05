<?php

namespace App\Core\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;
use Assert\Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Types\UlidType;

#[ORM\Entity]
class Channel {
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME)]
    public readonly Ulid $id;

    #[ORM\Column]
    public string $name {
        set(string $value) {
            Assert::that($value)->notBlank('Name can not be blank');
            $this->name = $value;
        }
    }

    #[ORM\OneToMany(Message::class, mappedBy: 'channel')]
    private Collection $messages;

    private function __construct(
        string $name
    ) {
        $this->id = new Ulid();
        $this->name = $name;

        $this->messages = new ArrayCollection();
    }

    /**
     * @return Message[]
     */
    public function getMessages(): array {
        return $this->messages->toArray();
    }

    public function addMessage(Message $message): void {
        $this->messages[] = $message;
    }

    public function removeMessage(Message $message): void {
        $this->messages->removeElement($message);
    }

    public static function create(string $name): self {
        return new self($name);
    }
}