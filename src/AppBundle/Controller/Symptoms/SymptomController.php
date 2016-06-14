<?php

namespace AppBundle\Controller\Symptoms;
use AppBundle\Entity\Dis_Symp;
use AppBundle\Entity\Symptom;
use AppBundle\Form\SymptomNew;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class SymptomController extends Controller
{
    /**
     * @Route("/admin/add_symptoms/{data_id}", defaults={"data_id" = 0}, name="add_symptoms")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function SelectDiseaseAction( Request $request,$data_id){
        
        $em = $this->getDoctrine()->getManager();
        $diseases =$em->getRepository('AppBundle:Disease')->findAll();
        if ($data_id==0){
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
                    'placeholder'=>'Choose a disease',

                ])
                ->add('existing', SubmitType::class, ['label'=> 'Add Existing Symptoms', 'attr'=>array('class'=>'btn btn-primary')])
                ->add('new', submitType::class, ['label'=> 'Add New Symptom', 'attr'=>array('class'=>'btn btn-primary')])
                ->getForm();

        } elseif ($data_id != 0){
            $selected = $em->getRepository('AppBundle:Disease')->find($data_id);

            $form = $this->createFormBuilder()
                ->add('selectADisease',choiceType::class, [
                    'choices' => $diseases,
                    'choice_label'=> function($disease){
                        return $disease->getName();
                    },
                    'choice_attr'=>function($disease){
                        return ['value' => $disease->getID()];
                    },
                    'data'=>$selected,
                    'choices_as_values'=>true,
                    'placeholder'=>'Choose a disease',

                ])
                ->add('existing', SubmitType::class, ['label'=> 'Add Existing Symptoms', 'attr'=>array('class'=>'btn btn-primary')])
                ->add('new', submitType::class, ['label'=> 'Add New Symptom', 'attr'=>array('class'=>'btn btn-primary')])
                ->getForm();
        }

        $form -> handleRequest($request);
        
        if($form -> isValid()){
            $data= $form->getData();
            $disease=current($data);
            $disease_id=$disease->getID();
            
            if($form->get('existing')->isClicked()){
                return $this->redirectToRoute('existing_symptoms',array('disease_id'=>$disease_id));
            }
            
            if (($form->get('new')->isClicked())){
                return $this->redirectToRoute('new_symptoms',array('disease_id'=>$disease_id));
            }
        }
        
        return $this->render('disease/addSymptoms.html.twig', array(
            'form'=>$form->createView(),
        ));
        
    }

    /**
     * @Route("/admin/existing_symptoms/{disease_id}", name="existing_symptoms")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addExistingSymptomsAction(Request $request,$disease_id){
        $em = $this->getDoctrine()->getEntityManager();
        $symptoms= $em->getRepository('AppBundle:Symptom')->findAll();

        $query1 = "SELECT dis__symp.symptom_id FROM dis__symp WHERE dis__symp.disease_id= $disease_id";
        $connection =$em->getConnection();
        $st1=$connection->prepare($query1);
        $st1->execute();
        $defaults = $st1->fetchAll();
        $mark = [];
        for($i=0;$i<count($defaults); $i++){
            $symp = $em->getRepository('AppBundle:Symptom')->find($defaults[$i]['symptom_id']);
            array_push($mark, $symp);
        }


        $form = $this->createFormBuilder()
            ->add('symptomID', choiceType::class,['label'=>'Symptoms',
                'choices'=>$symptoms,
                'choice_label'=>function($symptom){
                    return $symptom->getName();
                },
                'data'=>$mark,
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
            for ($i=0; $i<count($data['symptomID']); $i++) {
                if (!in_array($data['symptomID'][$i], $mark)) {
                    $dis_symp = new Dis_Symp();
                    $dis_symp->setSymptomID($data['symptomID'][$i]);
                    $dis_symp->setDiseaseID($disease);
                    $em->persist($dis_symp);
                    $em->flush();
                }
            }

            return $this->redirectToRoute("success_message");

        }
        
        
       return $this->render('symptom/existing.html.twig', array(
           'form'=>$form->createView(),
       ));
    }

    /**
     * @Route("/admin/new_symptoms/{disease_id}", name="new_symptoms")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addNewSymptomsAction(Request $request, $disease_id){
        
            $form = $this->createFormBuilder()
                ->add('name1', TextType::class,array('label'=>'Symptom Name'))
                ->add('save1', SubmitType::class,array('label'=>'Add'))
                ->add('name2', TextType::class,array('label'=>'Symptom Name','required'=>false))
                ->add('save2', SubmitType::class,array('label'=>'Add'))
                ->add('name3', TextType::class,array('label'=>'Symptom Name','required'=>false))
                ->add('save3', SubmitType::class,array('label'=>'Add'))
                ->add('name4', TextType::class,array('label'=>'Symptom Name','required'=>false))
                ->add('save4', SubmitType::class,array('label'=>'Add'))
                ->add('name5', TextType::class,array('label'=>'Symptom Name','required'=>false))
                ->add('save5', SubmitType::class,array('label'=>'Add'))
                ->add('addmore', SubmitType::class, array('label'=>'Add more symptoms','attr'=>array('class'=>'btn btn-primary')))
                ->add('finish', SubmitType::class, array('label'=>'Finish','attr'=>array('class'=>'btn btn-primary')))
                ->getForm();


            $form->handleRequest($request);
            if ($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $disease=$em->getRepository('AppBundle:Disease')->find($disease_id);
                $data=$form->getData();
                
                if ($form->get('save1')->isClicked()){
                    $symptom = new Symptom();
                    $symptom->setName($data['name1']);
                    $em->persist($symptom);
                    $em->flush();

                    $dis_symp = new Dis_Symp();
                    $dis_symp->setDiseaseID($disease);
                    $dis_symp->setSymptomID($symptom);
                    $em->persist($dis_symp);
                    $em->flush();
                }

                if ($form->get('save2')->isClicked()){
                    $symptom = new Symptom();
                    $symptom->setName($data['name2']);
                    $em->persist($symptom);
                    $em->flush();

                    $dis_symp = new Dis_Symp();
                    $dis_symp->setDiseaseID($disease);
                    $dis_symp->setSymptomID($symptom);
                    $em->persist($dis_symp);
                    $em->flush();
                }

                if ($form->get('save3')->isClicked()){
                    $symptom = new Symptom();
                    $symptom->setName($data['name3']);
                    $em->persist($symptom);
                    $em->flush();

                    $dis_symp = new Dis_Symp();
                    $dis_symp->setDiseaseID($disease);
                    $dis_symp->setSymptomID($symptom);
                    $em->persist($dis_symp);
                    $em->flush();
                }

                if ($form->get('save4')->isClicked()){
                    $symptom = new Symptom();
                    $symptom->setName($data['name4']);
                    $em->persist($symptom);
                    $em->flush();

                    $dis_symp = new Dis_Symp();
                    $dis_symp->setDiseaseID($disease);
                    $dis_symp->setSymptomID($symptom);
                    $em->persist($dis_symp);
                    $em->flush();
                }

                if ($form->get('save5')->isClicked()){
                    $symptom = new Symptom();
                    $symptom->setName($data['name5']);
                    $em->persist($symptom);
                    $em->flush();

                    $dis_symp = new Dis_Symp();
                    $dis_symp->setDiseaseID($disease);
                    $dis_symp->setSymptomID($symptom);
                    $em->persist($dis_symp);
                    $em->flush();
                }
                
                if ($form->get('addmore')->isClicked()){
                    return $this->redirectToRoute('new_symptoms', array('disease_id'=>$disease_id));
                }
                if ($form->get('finish')->isClicked()){
                    return $this->redirectToRoute('success_message');
                }
            }

            return $this->render('symptom/new.html.twig', array(
                'form'=>$form->createView(),
            ));


    }




}