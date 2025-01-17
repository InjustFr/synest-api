<?php

declare(strict_types=1);

namespace App\Core\Application\Repository;

use App\Core\Domain\Entity\File;
use Symfony\Component\Uid\Ulid;

/**
 * @api
 */
interface FileRepositoryInterface
{
    /**
     * @return File[]
     */
    public function list(): array;

    public function get(Ulid $id): File;

    public function save(File $file): void;

    public function delete(File $file): void;
}
