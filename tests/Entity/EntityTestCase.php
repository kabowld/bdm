<?php
namespace App\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EntityTestCase extends KernelTestCase
{
    private const ERROR_CONSTRAINT_VIOLATION = "la propriété %s => %s";
    private const GLUE = "\n";
    private const DEFAULT_STR = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";

    /**
     * Test number error of entity
     *
     * @param object $entity
     * @param int    $number
     *
     * @return void
     */
    protected function assertHasErrors(object $entity, int $number = 0): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $messages = [];
        $errors = $container->get(ValidatorInterface::class)->validate($entity);
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $messages[] = sprintf(self::ERROR_CONSTRAINT_VIOLATION, $error->getPropertyPath(), $error->getMessage());
        }

        $this->assertCount($number, $errors, implode(self::GLUE, $messages));
    }

    /**
     * Get a random string value
     *
     * @param int $length
     *
     * @return string
     */
    protected function str_random(int $length): string
    {
        return substr(str_shuffle(str_repeat(self::DEFAULT_STR, $length)), 0, $length);
    }

}
