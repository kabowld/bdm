<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class StateAnnonceRules extends Constraint
{
    public string $message = 'Aucun état ne doit être saisi pour les rubriques "Animaux", "Education", "Emploi et insertion" !';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

}
