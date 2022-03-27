<?php
namespace App\Validator;

use App\Entity\Annonce;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class StateAnnonceRulesValidator extends ConstraintValidator
{

    const BANNED_RUBRIQUES = ['animaux', 'education', 'emploi-et-insertion-sociale'];

    public function validate($annonce, Constraint $constraint)
    {
        if (!$constraint instanceof StateAnnonceRules) {
            throw new UnexpectedTypeException($constraint, StateAnnonceRules::class);
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
        $slugRubrique = $annonce->getCategory()->getRubrique()->getSlug();

        if (!is_null($annonce->getState()) && in_array($slugRubrique, self::BANNED_RUBRIQUES)) {
            $this->context->buildViolation($constraint->message)
                ->atPath('state')
                ->addViolation();
        }
    }
}
