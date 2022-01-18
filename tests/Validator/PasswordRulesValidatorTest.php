<?php

namespace App\Tests\Validator;

use App\Validator\PasswordRules;
use App\Validator\PasswordRulesValidator;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class PasswordRulesValidatorTest extends TestCase {

    /**
     * Test set for incorrect passwords
     *
     * @return array|\string[][]
     */
    public function badPasswordList(): array
    {
        return [
            ['cp|h$k6'],
            ['p,!Bq$XrJIJG*ZIN'],
            ['D3xWFFCyoikIv74lLCMzhlKtviIAJQ74d4pziTCMzqhjWy4h5FssIEI7whSYEAtgg'],
            ['rootroot'],
            ['admin'],
            ['RootRoot']
        ];
    }

    /**
     * Test set for correct passwords
     *
     * @return array|\string[][]
     */
    public function goodPasswordList(): array
    {
        return [
            ["buFtyH&r'Nq~Lf8hlRu'Zyt%FBCnpSTn#Vy.UGD£SM$4ipOzD`xWf$,D"],
            ['Y2pWN2SSwAJDkcm'],
            ['k"ta3jTBc#'],
            ['P@ssw0rd']
        ];
    }

    /**
     * Test with UnexpectedType of password value
     */
    public function testCatchBadTypeException()
    {
        $validator = new PasswordRulesValidator();
        $this->expectException(UnexpectedTypeException::class);
        $validator->validate('password', new NotBlank());
    }

    /**
     * Test with empty and null value
     */
    public function testIfValueIsNullOrEmpty()
    {
        $validator = new PasswordRulesValidator();

        $this->assertNull($validator->validate('', new PasswordRules()));
        $this->assertNull($validator->validate(null, new PasswordRules()));
    }

    /**
     * Test with catch password value if value is not string
     */
    public function testCatchNotStringValue()
    {
        $validator = new PasswordRulesValidator();
        $this->expectException(UnexpectedValueException::class);
        $validator->validate(12345, new PasswordRules());
    }

    /**
     * Test bad password
     *
     * @dataProvider badPasswordList
     *
     * @param string $value
     */
    public function testWithBadPassword(string $value)
    {
        $this->getValidator(true)->validate($value, new PasswordRules());
    }

    /**
     * Test good password
     *
     * @dataProvider  goodPasswordList
     *
     * @param string $value
     */
    public function testWithGoodPassword(string $value)
    {
        $this->getValidator()->validate($value, new PasswordRules());
    }

    /**
     * Get PasswordRulesValidator
     *
     * @param bool $expectedViolation
     *
     * @return PasswordRulesValidator
     */
    private function getValidator(bool $expectedViolation = false): PasswordRulesValidator
    {
        $context = $this->getMockBuilder(ExecutionContextInterface::class)->getMock();
        if ($expectedViolation) {
            $violation = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)->getMock();
            $violation->expects($this->any())->method('setParameter')->willReturn($violation);;
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

        $validator = new PasswordRulesValidator();
        $validator->initialize($context);

        return $validator;
    }
}
