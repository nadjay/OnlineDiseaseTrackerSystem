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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="user_login")
     */
    public function loginAction()
    {
        $helper = $this->get('security.authentication_utils');

        //get the login error if there is one
        $error = $helper->getLastAuthenticationError();

        //last username entered by the user
        $lastUsername = $helper->getLastUserName();

        return $this->render('security/login.html.twig', array(

            'last_username' => $lastUsername,
            'error' => $error

        ));
    }

    /**
     * @Route("login/redirect", name="redirect")
     */

    public function redirectAction(){
        $user = $this->getUser();
        $role = $user->getRole();
        if ($role == 'ROLE_PATIENT'){
            return $this->redirectToRoute('patient_homepage');
        }
        if ($role == 'ROLE_ADMIN'){
            return $this->redirectToRoute('admin_homepage');
        }
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
    
