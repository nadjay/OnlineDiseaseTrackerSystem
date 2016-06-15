<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testHome()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $heading = $crawler->filter('h2')->eq(0)->text();
        $this->assertEquals('Symptom Checking', $heading);

        $link = $crawler->selectLink('login')->link();
        $loginPage = $client->click($link);
        $this->assertEquals('Login to continue', $loginPage->filter('h3')->eq(0)->text());
        
    }
}
