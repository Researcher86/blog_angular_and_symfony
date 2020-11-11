<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation()
 */
class ExistsUser extends Constraint
{
    public $message = 'User [{{ id }}] not found.';
}
