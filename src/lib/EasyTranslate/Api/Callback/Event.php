<?php

declare(strict_types=1);

namespace EasyTranslate\Api\Callback;

class Event
{
    public const PRICE_APPROVAL_NEEDED = 'project.status.approval_needed';

    public const TASK_COMPLETED = 'task.updated';
}
