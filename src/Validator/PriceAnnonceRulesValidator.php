<?php

namespace App\Validator;

use App\Entity\Annonce;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PriceAnnonceRulesValidator extends ConstraintValidator
{
    const RUBRIQUE_SLUG = 'emploi-et-insertion-sociale';

    public function validate($annonce, Constraint $constraint)
    {
        if (!$constraint instanceof PriceAnnonceRules) {
            throw new UnexpectedTypeException($constraint, PriceAnnonceRules::class);
        }

        if (!$annonce instanceof Annonce) {
            throw new UnexpectedTypeException($annonce, Annonce::class);
        }

        if ($annonce->getCategory() === null) {
            return;
        }

        /**
         * @var Annonce $annonce
         */
        $slug = $annonce->getCategory()->getRubrique()->getSlug();

        if (!is_null($annonce->getPrice()) && $slug === self::RUBRIQUE_SLUG) {
            $this->context->buildViolation($constraint->message)
                ->atPath('price')
                ->addViolation();
        }
    }
}
