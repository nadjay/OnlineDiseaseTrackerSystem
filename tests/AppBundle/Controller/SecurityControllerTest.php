<?php
/**
 * Created by PhpStorm.
 * User: TOSHIBA
 * Date: 6/15/2016
 * Time: 11:06 AM
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginPatient(){

        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $button= $crawler->selectButton('Log in');
        $form = $button->form(array(
            '_username' => 'pamodaaw@gmail.com',
            '_password' => 'pamoda',
        ),'POST');
        $client->submit($form);
        $client->getResponse()->getTargetUrl();
        $client->followRedirect();
        $client->followRedirect();
        $this->assertRegExp('/patient%20homepage/', $client->getRequest()->getUri());

    }

    public function testLoginAdmin(){

        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $button= $crawler->selectButton('Log in');
        $form = $button->form(array(
            '_username' => 'nadini.jayathissa@gmail.com',
            '_password' => 'admin',
        ),'POST');
        $client->submit($form);
        $client->getResponse()->getTargetUrl();
        $client->followRedirect();
        $client->followRedirect();
        $this->assertRegExp('/admin_homepage/', $client->getRequest()->getUri());

    }
}