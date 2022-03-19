<?php


namespace App\Tests\Controller\Security\Register;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegisterFormSuccessParticular()
    {
        $client = static::createClient();
        $client->request('GET', '/creation/compte');
        $this->assertResponseIsSuccessful();

        $client->submitForm('registration_particular_save', [
            'registration_particular[email]' => 'alfred@mail.test',
            'registration_particular[password][first]' => 'RootRoot1588',
            'registration_particular[password][second]' => 'RootRoot1588'
        ]);

        $this->assertResponseRedirects();
    }


    public function testRegisterFormSuccess()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/creation/compte/pro');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('registration_save')->form();

        $form->setValues([
            'registration[tel]' => '0678943678',
            'registration[adresse]' => 'blabla',
            'registration[name]' => 'alfred',
            'registration[firstname]' => 'test',
            'registration[email]' => 'alfred@mail.test',
            'registration[password][first]' => 'RootRoot1588',
            'registration[password][second]' => 'RootRoot1588',
        ]);


        $form['registration[city]']->setValue(1);
        $form['registration[rubrique]']->setValue(1);
        $form['registration[civility]']->setValue('monsieur');
        $client->submit($form);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertPageTitleContains('Félicitation compte créé');
        $this->assertSelectorTextSame('.congrat_title', 'Bravo, votre compte a été créé avec succès !');
    }
}
