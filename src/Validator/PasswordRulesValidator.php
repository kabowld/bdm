<?php
declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class PasswordRulesValidator extends ConstraintValidator
{
    private const MIN_LENGTH = 8;
    private const MAX_LENGTH = 64;

    /**
     * @param mixed      $value
     * @param Constraint $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof PasswordRules) {
            throw new UnexpectedTypeException($constraint, PasswordRules::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');

            // separate multiple types using pipes
            // throw new UnexpectedValueException($value, 'string|int');
        }

        if (!$this->getRules($value)) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ password }}', $value)
                ->addViolation();
        }
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    private function getRules(string $value): bool
    {
        return
            preg_match_all('/[A-Z]+/', $value) &&
            preg_match_all('/[a-z]+/', $value) &&
            preg_match_all('/\d+/', $value) &&
            (strlen($value) >= self::MIN_LENGTH && strlen($value) <= self::MAX_LENGTH)
        ;
    }
}
