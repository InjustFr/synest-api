<?php

declare(strict_types=1);

namespace App\Core\Application\Query;

use App\Core\Domain\Entity\ServerSetting;

interface FindServerSettingByKeyInterface
{
    public function execute(string $key): ?ServerSetting;
}
