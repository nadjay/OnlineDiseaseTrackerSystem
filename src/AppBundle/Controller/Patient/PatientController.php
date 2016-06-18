<?php

namespace AppBundle\Controller\Patient;

use AppBundle\Entity\MedicalRecord;
use AppBundle\Entity\Patient;
use AppBundle\Form\PatientNew;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
class PatientController extends Controller
{
    /**
     * @Route("/patient/patient homepage", name="patient_homepage")
     * @Security("has_role('ROLE_PATIENT')")
     */
    public function patientHomeAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('patient/home.html.twig', array());
    }

    /**
     * @Route("/patient/successful", name="success_update")
     */
    public function sucessAction(){
        return $this->render('patient/message.html.twig', array()
        );
    }

    /**
     * @Route("/patient/thankyou", name="thankyou")
     */
    public function thankyouAction(){
        return $this->render('patient/thankyou.html.twig', array()
        );
    }


    /**
     * @Route("/signup", name="user_register")
     */
    public function userNewAction(Request $request ){
        //build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        //handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            
             //encode the password
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            
            $user->setRole('ROLE_PATIENT');
            
            //save the user
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('initial_login');
        }

        return $this->render('patient/register.html.twig', array(
            'form'=> $form->createView(),
            ));


    }



    /**
     * @Route("/patient/update Account", name="patient_new")
     * @Security("has_role('ROLE_PATIENT')")
     */
    public function patientNewAction(Request $request)
    {
        $user = $this->getUser();
        $email= $user->getUsername();

        $patient = new Patient();

        $form = $this->createFormBuilder($patient)
            ->add('email', TextType::class, array('data'=>$email))
            ->add('name', TextType::class)
            ->add('gender', ChoiceType::class,
                ['choices'=>['Male' => 'Male', 'Female' => 'Female'], 'choices_as_values' => true, 'placeholder' =>'-SELECT-'])
            ->add('dob', DateType::class,
                ['input' => 'datetime', 'widget' => 'choice', 'years' => range(1920,2016),'placeholder' => '-SELECT-'])
            ->add('save', submitType::class, array('label'=>'update', 'attr'=>array('class'=>"btn btn-primary")))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($patient);
            $em->flush();

            return $this->redirectToRoute('medical_profile');
        }


        return $this->render('patient/new_patient.html.twig', array(
            'form'=> $form->createView(),
        ));
    }

    /**
     * @Route("/patient/update medical profile", name="medical_profile")
     * @Security("has_role('ROLE_PATIENT')")
     */
    public function updateMedicalHistory(Request $request){
        $em = $this->getDoctrine()->getEntityManager();
        $diseases =$em->getRepository('AppBundle:Disease')->findAll();
        $connection = $em->getConnection();

        $user_email = $this->getUser()->getUsername();
        $query = "SELECT patient.id FROM patient WHERE patient.email = '$user_email'";
        $st = $connection->prepare($query);
        $st->execute();
        $p_id = $st->fetchAll();

        $query2 = "SELECT * FROM disease WHERE disease.id IN (";
        $query2 .= "SELECT medical_record.disease_id FROM medical_record ";
        $query2 .= "WHERE medical_record.patient_id = :id)";
        $st = $connection->prepare($query2);
        $st->bindValue('id', $p_id[0]['id']);
        $st->execute();
        $defaults = $st->fetchAll();
        $mark = [];
        for($i=0;$i<count($defaults); $i++){
            $dis = $em->getRepository('AppBundle:Disease')->find($defaults[$i]['id']);
            array_push($mark, $dis);
        }


        $form = $this->createFormBuilder()
            ->add('prereq', choiceType::class, array(
                'label'=>false,
                'choices'=>$diseases,
                'choice_label'=> function($disease){
                    return $disease->getName();
                },
                'data'=> $mark,
                'choices_as_values'=>true,
                'multiple'=>true,
                'expanded'=>true,
            ))
            ->add('update', SubmitType::class, ['label'=> 'update profile', 'attr'=>array('class'=>'btn btn-primary')])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()){
            $patient = $em->getRepository('AppBundle:Patient')->find($p_id[0]['id']);
            $data=$form->getData();
            for ($i=0; $i<count($data['prereq']); $i++){
                if (!in_array($data['prereq'][$i], $mark)){
                    $medical_record = new MedicalRecord();
                    $medical_record->setPatientID($patient);
                    $medical_record->setDiseaseID($data['prereq'][$i]);
                    $em->persist($medical_record);
                    $em->flush();
                }

            }

            //return $this->redirectToRoute('success_update');
        }


        return $this->render('patient/medicalProfile.html.twig', array(
            'form'=> $form->createView(),
        ));


    }

    /**
     * @Route("/patient/find_doctor/{disease_id}", defaults={"disease_id"=0}, name="Find_Doctor")
     * @Security("has_role('ROLE_PATIENT')")
     */

    public function findDoctorAction(Request $request, $disease_id){
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();

        $statement = $connection->prepare("SELECT disease.id, disease.name FROM disease");
        $statement->execute();
        $diseases=$statement->fetchAll();

        if($request->isMethod(("POST"))){
            $disease_id = $request->request->get('disease_id');
        }

        if ($disease_id == 0){
            return $this->render('doctor/view.html.twig', array('diseases' => $diseases,
                'disease_id' => $disease_id
            ));
        }

        $query1 = "SELECT doctor.id, doctor.name, doctor.speciality FROM doctor WHERE doctor.id IN (";
        $query1 .= "SELECT DISTINCT doc__dis.doctor_id FROM doc__dis WHERE ";
        $query1 .= "doc__dis.disease_id=$disease_id)";
        $statement1 = $connection->prepare($query1);
        $statement1->execute();
        $doctors = $statement1->fetchAll();


        return $this->render('doctor/view.html.twig',
            array('diseases'=>$diseases,
                'disease_id' => $disease_id,
                'Doctors' => $doctors
            ));
        

    }

    /**
     * @Route("/patient/disease_info/{disease_id}", defaults={"disease_id"=0}, name="Disease_Info")
     * @Security("has_role('ROLE_PATIENT')")
     */

    public function viewDiseaseInfoAction(Request $request, $disease_id){

        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();

        $query = "SELECT disease.id, disease.name FROM disease";

        $statement = $connection->prepare($query);
        $statement->execute();
        $diseases = $statement->fetchAll();

        if($request->isMethod(("POST"))){
            $disease_id = $request->request->get('disease_id');
        }

        if ($disease_id == 0){
            return $this->render('disease/view.html.twig', array('diseases' => $diseases,
                'disease_id' => $disease_id
            ));
        }

        $query1 = "SELECT * FROM disease WHERE disease.id = $disease_id";
        $statement1 = $connection->prepare($query1);
        $statement1->execute();
        $diseases_ = $statement1->fetchAll();
        

        return $this->render('disease/view.html.twig', 
            array('diseases'=>$diseases,
                'disease_id' => $disease_id,
                'Diseases' => $diseases_
                ));
        

    }
}

