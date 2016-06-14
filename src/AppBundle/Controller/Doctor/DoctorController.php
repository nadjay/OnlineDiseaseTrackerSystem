<?php

namespace AppBundle\Controller\Doctor;
use AppBundle\Entity\Doc_Dis;
use AppBundle\Entity\Doctor;
use AppBundle\Form\DoctorNew;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class DoctorController extends Controller
{
    /**
     * @Route("/admin/new doctor", name="new_doctor")
     * @Security("has_role('ROLE_ADMIN')")
     */

    public function newDoctorAction(Request $request){

        $doctor = new Doctor();

        $form = $this->createForm(DoctorNew::class, $doctor);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($doctor);
            $em->flush();

            if ($form->get('save')->isClicked()){
                return $this->redirectToRoute("success_message");
            }
            if ($form->get('continue')->isClicked()){
                return $this->redirectToRoute('add_hospital',array('data_id'=>$doctor->getId()));
            }

        }

        return $this->render('Doctor/new.html.twig', array(
            'form'=> $form->createView(),
        ));
    }

    /**
     * @Route ("/admin/select doctor", name="select_doctor")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function selectDoctorAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $doctors =$em->getRepository('AppBundle:Doctor')->findAll();

        $form = $this->createFormBuilder()
            ->add('selectDoctor',choiceType::class, [
                'choices' => $doctors,
                'choice_label'=> function($doctor){
                    return $doctor->getName();
                },
                'choice_attr'=>function($doctor){
                    return ['value' => $doctor->getID()];
                },
                'choices_as_values'=>true,
                'placeholder'=>'Choose doctor',
            ])
            ->add('add', SubmitType::class, ['label'=> 'Continue', 'attr'=>array('class'=>'btn btn-primary')])
            ->getForm();

        $form -> handleRequest($request);

        if($form -> isValid()){
            $data= $form->getData();
            $doctor=current($data);
            $doctor_id=$doctor->getID();

            return $this->redirectToRoute('add_diseases_to_doctor', array('doctor_id'=>$doctor_id));
        }

        return $this->render('Doctor/select.html.twig', array(
            'form'=>$form->createView(),
        ));
    }


    /**
     * @Route("/admin/add diseases to doctor/{doctor_id}", name="add_diseases_to_doctor")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addDiseasesToDoctorAction(Request $request,$doctor_id){
        $em = $this->getDoctrine()->getEntityManager();
        $diseases= $em->getRepository('AppBundle:Disease')->findAll();

        $query1 = "SELECT doc__dis.disease_id FROM doc__dis WHERE doc__dis.doctor_id = $doctor_id";
        $connection =$em->getConnection();
        $st1=$connection->prepare($query1);
        $st1->execute();
        $defaults = $st1->fetchAll();
        $mark = [];
        for($i=0;$i<count($defaults); $i++){
            $dis = $em->getRepository('AppBundle:Disease')->find($defaults[$i]['disease_id']);
            array_push($mark, $dis);
        }


        $form = $this->createFormBuilder()
            ->add('diseaseID', choiceType::class,['label'=>'Diseases',
                'choices'=>$diseases,
                'choice_label'=>function($disease){
                    return $disease->getName();
                },
                'data'=> $mark,
                'choices_as_values'=>true,
                'multiple'=>true,
                'expanded'=>true,
            ])
            ->add('save', submitType::class,['label'=> 'Continue','attr'=>array('class'=>'btn btn-primary')])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()){

            $doctor=$em->getRepository('AppBundle:Doctor')->find($doctor_id);
            $data=$form->getData();
            for($i=0; $i<count($data['diseaseID']); $i++){
                if (!in_array($data['diseaseID'][$i],$mark)) {
                    $doc_dis = new Doc_Dis();
                    $doc_dis->setDiseaseID($data['diseaseID'][$i]);
                    $doc_dis->setDoctorID($doctor);
                    $em->persist($doc_dis);
                    $em->flush();
                }               
            }
            return $this->redirectToRoute("success_message");

        }


        return $this->render('doctor/addDiseases.html.twig', array(
            'form'=>$form->createView(),
        ));

    }
}