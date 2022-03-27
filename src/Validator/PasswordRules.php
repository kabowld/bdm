<?php
declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PasswordRules extends Constraint
{

    public string $message = 'Le mot de passe "{{ password }}" ne respecte pas les normes !';
}
