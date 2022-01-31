<?php
declare(strict_types=1);

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationParticular extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->remove('name')
            ->remove('firstname')
            ->remove('siret')
            ->remove('societyName')
            ->remove('adresse')
            ->remove('city')
            ->remove('tel')
            ->remove('civility')
            ->remove('rubrique')
        ;
    }

    /**
     * @return string
     */
    public function getParent(): string
    {
        return RegistrationType::class;
    }
}
