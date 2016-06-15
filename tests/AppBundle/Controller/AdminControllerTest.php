<?php
/**
 * Created by PhpStorm.
 * User: TOSHIBA
 * Date: 6/15/2016
 * Time: 1:21 PM
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testAddDisease(){
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/admin/admin_homepage');
        
        $link = $crawler->selectLink('New Disease')->link();
        $newDiseasePage = $client->click($link);
        $client->followRedirect();
        $button = $newDiseasePage->selectButton('addSymptoms');
        $form = $button->form(array(
            'name'=> 'test',
            'gender' => 'male',
            'severity'=>'high',
            'description'=>'testing',
            ), 'POST'
        );
        $client->submit($form);
        $client->click($button);
        $client->getResponse()->getTargetUrl();
        $client->followRedirect();
        $this->assertRegExp('/admin/add_symptoms/', $client->getRequest()->getUri());
        
        
        
    }
}