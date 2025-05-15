<?php

declare(strict_types=1);

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    /**
     * @var string $env
     */
    $env = $context['APP_ENV'] ?? 'dev';

    return new Kernel($env, (bool) ($context['APP_DEBUG'] ?? true));
};
