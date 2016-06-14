<?php

/**
 * Created by PhpStorm.
 * User: TOSHIBA
 * Date: 5/18/2016
 * Time: 5:29 PM
 */
namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AdminController extends Controller
{
    /**
     * @Route("/admin/admin_homepage", name="admin_homepage")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function adminHomeAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('Admin/home.html.twig', array());
    }


    /**
     * @Route("/admin/successful", name="success_message")
     */

    public function sucessAction(){
        return $this->render('Admin/message.html.twig', array()
        );
    }
    
    
}