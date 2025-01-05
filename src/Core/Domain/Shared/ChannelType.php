<?php

declare(strict_types=1);

namespace App\Core\Domain\Shared;

enum ChannelType: string
{
    case Text = 'text';
    case Video = 'video';
}
