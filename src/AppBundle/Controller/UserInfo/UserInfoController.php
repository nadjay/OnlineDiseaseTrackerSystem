<?php

namespace AppBundle\Controller\UserInfo;
use AppBundle\Entity\User_Info;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Created by PhpStorm.
 * User: TOSHIBA
 * Date: 5/30/2016
 * Time: 8:37 PM
 */
class UserInfoController extends Controller
{
    /**
     * @Route("/patient/provide info", name="provide_info")
     * @Security("has_role('ROLE_PATIENT')")
     */
    public function provideInfoAction(Request $request){

        $email = $this->getUser()->getUsername();
        $user_info = new User_Info();

        $form = $this->createFormBuilder($user_info)
            ->add('username', TextType::class,['label'=>'user email', 'data'=>$email])
            ->add('information',TextareaType::class,['label'=>'Information'])
            ->add('state',TextType::class, ['label'=> 'state', 'data'=>'not yet evaluated',])
            ->add('save',submitType::class, ['label'=>'Submit', 'attr'=>array('class'=>'btn btn-primary')])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($user_info);
            $em->flush();

            return $this->redirectToRoute('thankyou');
        }


        return $this->render('userInfo/provide.html.twig', array(
            'form'=>$form->createView(),
        ));
    }

    /**
     * @Route("/admin/view user provided info", name="view_user_provided_info")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function viewProvidedInfoAction(){
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $query1 = "SELECT * FROM user__info WHERE user__info.state ='not yet evaluated' ORDER BY user__info.id DESC";
        $query2 = "SELECT * FROM user__info WHERE user__info.state = 'successful' ORDER BY user__info.id DESC";
        $query3 = "SELECT * FROM user__info WHERE user__info.state = 'failure' ORDER BY user__info.id DESC";
        
        $st1 = $connection->prepare($query1);
        $st1->execute();
        $not_yet = $st1->fetchAll();

        $st2 = $connection->prepare($query2);
        $st2->execute();
        $success = $st2->fetchAll();

        $st3 = $connection->prepare($query3);
        $st3->execute();
        $failure = $st3->fetchAll();
        
        return $this->render('UserInfo\view.html.twig', array(
            'not_yet'=>$not_yet,
            'failure'=>$failure,
            'success'=>$success
        ));
    }

    /**
     * @Route ("/admin/evaluate info/mark sucess/{user_info_id}/{user_email}", name="mark_success")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function markSuccessAction($user_info_id,$user_email){

        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        
        $query = "UPDATE user__info SET user__info.state = 'successful' WHERE user__info.id = $user_info_id";
        $st = $connection->prepare($query);
        $st->execute();
        //sending the response mail
        return $this->sendEmailAction($user_email);
    }

    /**
     * @Route ("/admin/evaluate info/mark failure/{user_info_id}", name="mark_failure")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function markFailureAction($user_info_id ){

        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $query = "UPDATE user__info SET user__info.state = 'failure' WHERE user__info.id = $user_info_id";
        $st = $connection->prepare($query);
        $st->execute();
        return $this->redirectToRoute('view_user_provided_info');
    }

    public function sendEmailAction($username)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject("We Care | Disease Tracker System")
            ->setFrom('wecareodts$gmail.com')
            ->setTo($username)
            ->setBody(
                "This is an automatically generated message\n \nThe information you provided has been marked as reliable information.
                \nThank you for your outstanding contribution.\nKeep up your good work and be with us!!"
            )
        ;
        $this->get('mailer')->send($message);

        return $this->redirectToRoute('view_user_provided_info');
    }
}