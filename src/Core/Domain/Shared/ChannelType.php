<?php

namespace App\Core\Domain\Shared;

enum ChannelType: string
{
    case Text = 'text';
    case Video = 'video';
}
