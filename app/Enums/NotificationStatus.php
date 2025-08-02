<?php

namespace App\Enums;

enum NotificationStatus: string
{
    case Completed = 'completed';
    case InProgress = 'in_progress';
}
