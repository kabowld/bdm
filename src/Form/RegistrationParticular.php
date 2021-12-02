<?php
declare(strict_types=1);

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationParticular extends AbstractType
{

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

    public function getParent()
    {
        return RegistrationType::class;
    }
}
