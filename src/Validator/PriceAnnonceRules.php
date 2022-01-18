<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PriceAnnonceRules extends Constraint {

    public string $message = 'Aucun prix ne doit être saisi pour la rubrique Emploi et insertion!';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
