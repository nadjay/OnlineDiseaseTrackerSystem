<?php
namespace AppBundle\Controller\Pre_req;
use AppBundle\Entity\pre_req;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Created by PhpStorm.
 * User: TOSHIBA
 * Date: 5/30/2016
 * Time: 6:54 PM
 */
class PrereqController extends Controller
{
    /**
     * @Route ("/admin/root disease", name="root_disease")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function selectDiseaseAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $diseases =$em->getRepository('AppBundle:Disease')->findAll();

        $form = $this->createFormBuilder()
            ->add('selectADisease',choiceType::class, [
                'choices' => $diseases,
                'choice_label'=> function($disease){
                    return $disease->getName();
                },
                'choice_attr'=>function($disease){
                    return ['value' => $disease->getID()];
                },
                'choices_as_values'=>true,
                'placeholder'=>'Choose disease',
                'label'=>false,
            ])
            ->add('add', SubmitType::class, ['label'=> 'Continue', 'attr'=>array('class'=>'btn btn-primary')])
            ->getForm();

        $form -> handleRequest($request);

        if($form -> isValid()){
            $data= $form->getData();
            $disease=current($data);
            $root_id=$disease->getID();

            if($form->get('add')->isClicked()) {
                return $this->redirectToRoute('add_prereq', array('root_id' => $root_id));
            }
        }

        return $this->render('disease/select.html.twig', array(
            'form'=>$form->createView(),
        ));
    }

    /**
     * @Route("/admin/add prereq/{root_id}", name="add_prereq")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addPrereqAction(Request $request,$root_id){
        $em = $this->getDoctrine()->getEntityManager();
        $prerequisites =$em->getRepository('AppBundle:Disease')->findAll();
        
        $form = $this->createFormBuilder()
            ->add('pre_conditions', choiceType::class,['choices'=>$prerequisites,
                    'choice_label'=> function($disease){
                        return $disease->getName();
                    },
                    'choice_attr'=>function($disease){
                        return ['value' => $disease->getID()];
                    },
                    'choices_as_values'=>true,
                    'placeholder'=>'Choose disease',
                    'label'=>false
                ])
            ->add('save', submitType::class,['label'=> 'finish adding prerequisites','attr'=>array('class'=>'btn btn-primary')])
            ->add('add', submitType::class, ['label'=> 'add more prerequisites', 'attr'=> array('class'=>'btn btn-primary')])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()){
            $root=$em->getRepository('AppBundle:Disease')->find($root_id);
            if ($form->get('save')->isClicked()){
                $data=$form->getData();
                $pre_req = new pre_req();
                $pre_req->setDiseaseId($root);
                $pre_req->setPreReqId(current($data));
                $em->persist($pre_req);
                $em->flush();
                return $this->redirectToRoute("success_message");
            }

            if ($form->get('add')->isClicked()){
                $data=$form->getData();
                $pre_req = new pre_req();
                $pre_req->setDiseaseId($root);
                $pre_req->setPreReqId(current($data));
                $em->persist($pre_req);
                $em->flush();

                return $this->redirectToRoute('add_prereq', array('root_id'=>$root_id));
            }
            
        }


        return $this->render('pre_req/add.html.twig', array(
            'form'=>$form->createView(),
        ));

    }
}