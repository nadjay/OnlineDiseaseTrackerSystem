<?php

/**
 * Created by PhpStorm.
 * User: TOSHIBA
 * Date: 6/14/2016
 * Time: 11:43 PM
 */
namespace Tests\AppBundle\Entity;
use AppBundle\Entity\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testGetUsername()
    {
        $user = new User();
        $user->setUsername('pamodaaw@gmail.com');
        $result = $user->getUsername();

        // assert that your calculator added the numbers correctly!
        $this->assertEquals('pamodaaw@gmail.com', $result);
    }
}
