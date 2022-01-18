<?php

namespace App\Tests\Validator;

use App\Entity\Annonce;
use App\Entity\Category;
use App\Entity\Rubrique;
use App\Entity\State;
use App\Validator\PriceAnnonceRules;
use App\Validator\PriceAnnonceRulesValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class PriceAnnonceRulesValidatorTest extends TestCase
{
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
     * Test with UnexpectedType of password value
     */
    public function testCatchBadTypeException()
    {
        $validator = new PriceAnnonceRulesValidator();
        $this->expectException(UnexpectedTypeException::class);
        $validator->validate('password', new NotBlank());
    }


    /**
     * Test with catch password value if value is not string
     */
    public function testCatchNotAnnonceValue()
    {
        $validator = new PriceAnnonceRulesValidator();
        $this->expectException(UnexpectedTypeException::class);
        $validator->validate(12345, new PriceAnnonceRules());
    }

    public function testWithBadSLug()
    {
        $annonce = $this->getAnnonce('emploi-et-insertion-sociale');
        $this->getValidator(true)->validate($annonce, new PriceAnnonceRules());
    }

    /**
     * @dataProvider goodSlug
     */
    public function testWithGoodSLug(string $slug)
    {
        $annonce = $this->getAnnonce($slug);
        $this->getValidator()->validate($annonce, new PriceAnnonceRules());
    }

    /**
     * Get PriceAnnonceRulesValidator
     *
     * @param bool $expectedViolation
     *
     * @return PriceAnnonceRulesValidator
     */
    private function getValidator(bool $expectedViolation = false): PriceAnnonceRulesValidator
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

        $validator = new PriceAnnonceRulesValidator();
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
            ->setPrice(400)
            ->setDescription('description')
            ->setCategory($category);

        return $annonce;
    }
}
