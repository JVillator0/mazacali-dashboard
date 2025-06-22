<?php

namespace App\Enums;

use App\Attributes\Description;

enum OrderStatusEnum: string
{
    use HasEnums;

    #[Description('Pending')]
    case PENDING = 'pending';

    #[Description('In Progress')]
    case IN_PROGRESS = 'in_progress';

    #[Description('Completed')]
    case COMPLETED = 'completed';

    #[Description('Cancelled')]
    case CANCELLED = 'cancelled';
}
