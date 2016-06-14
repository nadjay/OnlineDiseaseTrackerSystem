<?php

namespace AppBundle\Controller\Disease;
use AppBundle\Entity\Disease;
use AppBundle\Entity\Doc_Dis;
use AppBundle\Form\DiseaseNew;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class DiseaseController extends Controller
{

    /**
     * @Route("/admin/new disease", name="new_disease")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addDiseaseAction(Request $request){
        
        $disease = new Disease();

        $em = $this->getDoctrine()->getEntityManager();
        $query="SELECT name FROM disease";
        

        $form = $this->createForm(DiseaseNew::class, $disease);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

                $em->persist($disease);
                $em->flush();

                if ($form->get('finish')->isClicked()){
                    return $this->redirectToRoute("success_message");
                }
                if ($form->get('addSymptoms')->isClicked()){
                    return $this->redirectToRoute('add_symptoms',array('data_id'=>$disease->getId()));
                }

            

        }

        return $this->render('disease/addNew.html.twig', array(
            'form'=> $form->createView(),
        ));
    }

    /**
     * @Route ("/admin/select disease", name="select_disease")
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
            ])
            ->add('add', SubmitType::class, ['label'=> 'Continue', 'attr'=>array('class'=>'btn btn-primary')])
            ->getForm();

        $form -> handleRequest($request);

        if($form -> isValid()){
            $data= $form->getData();
            $disease=current($data);
            $disease_id=$disease->getID();

            if($form->get('add')->isClicked()) {
                return $this->redirectToRoute('add_doctors_to_disease', array('disease_id' => $disease_id));
            }
        }

        return $this->render('disease/select.html.twig', array(
            'form'=>$form->createView(),
        ));
    }

    /**
     * @Route("/admin/add doctors to disease/{disease_id}", name="add_doctors_to_disease")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addDoctorsToDiseaseAction(Request $request,$disease_id){
        $em = $this->getDoctrine()->getEntityManager();
        $doctors= $em->getRepository('AppBundle:Doctor')->findAll();

        $query1 = "SELECT doc__dis.doctor_id FROM doc__dis WHERE doc__dis.disease_id = $disease_id";
        $connection =$em->getConnection();
        $st1=$connection->prepare($query1);
        $st1->execute();
        $defaults = $st1->fetchAll();
        $mark = [];
        for($i=0;$i<count($defaults); $i++){
            $doc = $em->getRepository('AppBundle:Doctor')->find($defaults[$i]['doctor_id']);
            array_push($mark, $doc);
        }

        $form = $this->createFormBuilder()
            ->add('doctorID', choiceType::class,['label'=>'Doctors',
                'choices'=>$doctors,
                'choice_label'=>function($doctor){
                    return $doctor->getName();
                },
                'data' => $mark,
                'choices_as_values'=>true,
                'multiple'=>true,
                'expanded'=>true,
            ])
            ->add('save', submitType::class,['label'=> 'Continue','attr'=>array('class'=>'btn btn-primary')])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()){
            $disease=$em->getRepository('AppBundle:Disease')->find($disease_id);
            $data=$form->getData();
            for($i=0; $i<count($data['doctorID']); $i++){
                if (!in_array($data['doctorID'][$i], $mark)){
                    $doc_dis = new Doc_Dis();
                    $doc_dis->setDoctorID($data['doctorID'][$i]);
                    $doc_dis->setDiseaseID($disease);
                    $em->persist($doc_dis);
                    $em->flush();
                }
            }
            return $this->redirectToRoute("success_message");

        }


        return $this->render('disease/addDoctors.html.twig', array(
            'form'=>$form->createView(),
        ));

    }


    
}