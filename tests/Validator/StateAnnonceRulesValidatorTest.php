<?php

namespace App\Tests\Validator;

use App\Entity\Annonce;
use App\Entity\Category;
use App\Entity\Rubrique;
use App\Entity\State;
use App\Validator\StateAnnonceRules;
use App\Validator\StateAnnonceRulesValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class StateAnnonceRulesValidatorTest extends TestCase
{
    public function badSlug(): array
    {
        return [
            ['animaux'],
            ['education'],
            ['emploi-et-insertion-sociale']
        ];
    }

    public function goodSlug(): array
    {
        return [
            ['vacances-et-famille'],
            ['style-et-mode'],
            ['maison-et-equipement'],
            ['divers'],
        ];
    }

    /**
     * @dataProvider badSlug
     */
    public function testWithBadSLug(string $slug)
    {
        $annonce = $this->getAnnonce($slug);
        $this->getValidator(true)->validate($annonce, new StateAnnonceRules());
    }

    /**
     * @dataProvider goodSlug
     */
    public function testWithGoodSLug(string $slug)
    {
        $annonce = $this->getAnnonce($slug);
        $this->getValidator()->validate($annonce, new StateAnnonceRules());
    }

    /**
     * Get PasswordRulesValidator
     *
     * @param bool $expectedViolation
     *
     * @return StateAnnonceRulesValidator
     */
    private function getValidator(bool $expectedViolation = false): StateAnnonceRulesValidator
    {
        $context = $this->getMockBuilder(ExecutionContextInterface::class)->getMock();
        if ($expectedViolation) {
            $violation = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)->getMock();
            $violation->expects($this->any())->method('atPath')->willReturn($violation);;
            $violation->expects($this->once())->method('addViolation');
            $context
                ->expects($this->once())
                ->method('buildViolation')
                ->willReturn($violation)
            ;
        } else {
            $context
                ->expects($this->never())
                ->method('buildViolation')
            ;
        }

        $validator = new StateAnnonceRulesValidator();
        $validator->initialize($context);

        return $validator;
    }

    private function getAnnonce($slugCategory): Annonce
    {
        $rubrique = new Rubrique();
        $rubrique->setSlug($slugCategory)->setTitle('rub title');

        $category = new Category();
        $category->setTitle('cat title')->setSlug('cat-slug')->setRubrique($rubrique);

        $state = new State();
        $state->setTitle('neuf')->setDescription('state description');

        $annonce = new Annonce;
        $annonce->setTitle('title')
            ->setState($state)
            ->setDescription('description')
            ->setCategory($category);

        return $annonce;
    }
}
