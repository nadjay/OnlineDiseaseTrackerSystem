<?php
/**
 * Created by PhpStorm.
 * User: TOSHIBA
 * Date: 4/30/2016
 * Time: 8:01 PM
 */
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SecurityController2 extends Controller
{
    /**
     * @Route("/initial_login", name="initial_login")
     */
    public function loginAction()
    {
        $helper = $this->get('security.authentication_utils');

        //get the login error if there is one
        $error = $helper->getLastAuthenticationError();

        //last username entered by the user
        $lastUsername = $helper->getLastUserName();

        return $this->render('security/initial_login.html.twig', array(

            'last_username' => $lastUsername,
            'error' => $error

        ));
    }

    /**
     * This is the route the login form submits to.
     *
     * But, this will never be executed. Symfony will intercept this first
     * and handle the login automatically. See form_login in app/config/security.yml
     *
     * @Route("/login_check", name="security_login_check")
     */
    public function loginCheckAction()
    {
        throw new \Exception('This should never be reached!');
    }

    /**
     * This is the route the user can use to logout.
     *
     * But, this will never be executed. Symfony will intercept this first
     * and handle the logout automatically. See logout in app/config/security.yml
     *
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction()
    {
        throw new \Exception('This should never be reached!');
    }
}
    
