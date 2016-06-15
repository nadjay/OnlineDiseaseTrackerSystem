<?php
/**
 * Created by PhpStorm.
 * User: TOSHIBA
 * Date: 6/15/2016
 * Time: 12:17 PM
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PatientControllerTest extends WebTestCase
{
    public  function testSymptomChecker(){
        $client = static::createClient();

        $crawler = $client->request('GET', '/patient/patient%20homepage');
        
        $link = $crawler->selectLink('Symptom Checker')->link();
        $sympCheckPage = $client->click($link);
        $buttonNode = $sympCheckPage->selectButton('go');
        $form = $buttonNode->form();
        $form['symptom1']->select['itching'];
        $form['symptom2']->select['headache'];
        $client->submit($form);
        $client->getResponse()->getTargetUrl();
        $client->followRedirect();
        $this->assertRegExp('/possible diagnoses/1,2/', $client->getRequest()->getUri());
    }
    
    public function testPossibleDiagnose(){
        $client = static::createClient();

        $crawler = $client->request('GET', '/patient/possible diagnoses/1,2');

        $link = $crawler->selectLink('next step')->link();
        $client->click($link);
        $client->getResponse()->getTargetUrl();
        $client->followRedirect();
        $this->assertRegExp('/patient/find_doctor/7/', $client->getRequest()->getUri());
        
    }
}