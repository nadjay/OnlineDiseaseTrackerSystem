<?php
namespace AppBundle\Controller\Symptom_checker;

use AppBundle\Entity\Disease;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Symptom;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class SymptomCheckerController extends Controller
{
    /**
     * @Route("/patient/enter_symptoms", name="enter_symptoms")
     * @Security("has_role('ROLE_PATIENT')")
     */
    
    public function enterSymptomsAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $symptoms = $em->getRepository('AppBundle:Symptom')->findAll();

        $form = $this->createFormBuilder()
            ->add('symptom1',choiceType::class,['choices'=> $symptoms,
                'choice_label'=>function($symptom){
                    return $symptom->getName();
                },
                'choice_attr'=>function($symptom){
                    return ['value' => $symptom->getID()];
                },
                'choices_as_values'=>true,
                'placeholder'=>'symptom 1',
            ])
            ->add('symptom2',choiceType::class,['choices'=> $symptoms,
                'choice_label'=>function($symptom){
                    return $symptom->getName();
                },
                'choice_attr'=>function($symptom){
                    return ['value' => $symptom->getID()];
                },
                'choices_as_values'=>true,
                'placeholder'=>'symptom 2',
                'required'=>false,
            ])
            ->add('symptom3',choiceType::class,['choices'=> $symptoms,
                'choice_label'=>function($symptom){
                    return $symptom->getName();
                },
                'choice_attr'=>function($symptom){
                    return ['value' => $symptom->getID()];
                },
                'choices_as_values'=>true,
                'placeholder'=>'symptom 3',
                'required'=>false,
            ])
            ->add('symptom4',choiceType::class,['choices'=> $symptoms,
                'choice_label'=>function($symptom){
                    return $symptom->getName();
                },
                'choice_attr'=>function($symptom){
                    return ['value' => $symptom->getID()];
                },
                'choices_as_values'=>true,
                'placeholder'=>'symptom 4',
                'required'=>false,
            ])
            ->add('symptom5',choiceType::class,['choices'=> $symptoms,
                'choice_label'=>function($symptom){
                    return $symptom->getName();
                },
                'choice_attr'=>function($symptom){
                    return ['value' => $symptom->getID()];
                },
                'choices_as_values'=>true,
                'placeholder'=>'symptom 5',
                'required'=>false,
            ])
            ->add('go', submitType::class, ['label'=>'Go', 'attr'=>array('class'=>'btn btn-primary')])
            ->add('more',submitType::class,['label'=>'+add more symptoms', 'attr'=>array('class'=>'btn btn-primary')])
            ->getForm();
        
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()){
                $symptoms = $form->getData();
                $symptom_ids = [];
                foreach ($symptoms as $symptom) {
                    if ($symptom!= null) {
                        $s_id = $symptom->getID();
                        array_push($symptom_ids, $s_id) ;
                    }
                }
                $var = implode(",", $symptom_ids);
                if ($form->get('go')->isClicked()){
                    return $this->redirectToRoute('possible_diagnoses', array('symptoms'=> $var));
                }elseif ($form->get('more')->isClicked()){
                    return $this->redirectToRoute('enter_symptoms2', array('data'=>$var));
                }
        }
        return $this->render('SymptomChecker/enterSymptoms.html.twig',array(
            'form'=>$form->createView()
        ));

    }


    /**
     * @Route("/patient/enter_symptoms2/{data}", name="enter_symptoms2")
     * @Security("has_role('ROLE_PATIENT')")
     */

    public function enterSymptoms2Action(Request $request, $data){
        $em = $this->getDoctrine()->getManager();
        $symptoms = $em->getRepository('AppBundle:Symptom')->findAll();

        $form = $this->createFormBuilder()
            ->add('symptom1',choiceType::class,['choices'=> $symptoms,
                'choice_label'=>function($symptom){
                    return $symptom->getName();
                },
                'choice_attr'=>function($symptom){
                    return ['value' => $symptom->getID()];
                },
                'choices_as_values'=>true,
                'placeholder'=>'symptom 1',
            ])
            ->add('symptom2',choiceType::class,['choices'=> $symptoms,
                'choice_label'=>function($symptom){
                    return $symptom->getName();
                },
                'choice_attr'=>function($symptom){
                    return ['value' => $symptom->getID()];
                },
                'choices_as_values'=>true,
                'placeholder'=>'symptom 2',
                'required'=>false,
            ])
            ->add('symptom3',choiceType::class,['choices'=> $symptoms,
                'choice_label'=>function($symptom){
                    return $symptom->getName();
                },
                'choice_attr'=>function($symptom){
                    return ['value' => $symptom->getID()];
                },
                'choices_as_values'=>true,
                'placeholder'=>'symptom 3',
                'required'=>false,
            ])
            ->add('symptom4',choiceType::class,['choices'=> $symptoms,
                'choice_label'=>function($symptom){
                    return $symptom->getName();
                },
                'choice_attr'=>function($symptom){
                    return ['value' => $symptom->getID()];
                },
                'choices_as_values'=>true,
                'placeholder'=>'symptom 4',
                'required'=>false,
            ])
            ->add('symptom5',choiceType::class,['choices'=> $symptoms,
                'choice_label'=>function($symptom){
                    return $symptom->getName();
                },
                'choice_attr'=>function($symptom){
                    return ['value' => $symptom->getID()];
                },
                'choices_as_values'=>true,
                'placeholder'=>'symptom 5',
                'required'=>false,
            ])
            ->add('go', submitType::class, ['label'=>'Go', 'attr'=>array('class'=>'btn btn-primary')])
            //->add('more',submitType::class,['label'=>'+add more symptoms', 'attr'=>array('class'=>'btn btn-primary')])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()){
            $symptoms = $form->getData();
            $symptom_ids = [];
            foreach ($symptoms as $symptom) {
                if ($symptom!= null) {
                    $s_id = $symptom->getID();
                    array_push($symptom_ids, $s_id) ;
                }
            }
            $temp= implode(",", $symptom_ids);
            $var = $data.$temp;
            if ($form->get('go')->isClicked()){
                return $this->redirectToRoute('possible_diagnoses', array('symptoms'=> $var));
            }

        }
        return $this->render('SymptomChecker/enterSymptoms.html.twig',array(
            'form'=>$form->createView()
        ));

    }

    /**
     * @Route("/patient/possible diagnoses/{symptoms}", name="possible_diagnoses")
     * @Security("has_role('ROLE_PATIENT')")
     */
    public function symptomChecker($symptoms){
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();

        $symptom_ids = []; //array containing ids of the entered symptoms
        $guess1 = [];    //array containing initial guesses of diseases
        $temp = "";
        
        //get the symptom ids into $symptom_ids array
        for ($i=0; $i < strlen($symptoms); $i++) { 
            $char = substr($symptoms, $i, 1);
            if ($char != ","){
                $temp = $temp.$char;
            }elseif ($char == ","){
                $s_id = intval($temp);
                array_push($symptom_ids, $s_id);
                $temp = "";
            }
        }
        $s_id = intval($temp);
        array_push($symptom_ids, $s_id);
       
        //creating a view to keep the details of initially guessed diseases
        $query1 = "CREATE OR REPLACE VIEW v AS (";
        $query1 .= "SELECT * FROM disease WHERE disease.id IN (";
        $query1 .= "SELECT DISTINCT dis__symp.disease_id FROM dis__symp ";
        $query1 .= "WHERE dis__symp.symptom_id IN (" .implode(',',$symptom_ids).")))";
        $st1 = $connection->prepare($query1);
        $st1->execute();

        $query2 = "SELECT * FROM v";
        $st2 = $connection->prepare($query2);
        $st2->execute();
        $guess1 = $st2->fetchAll();

        //get the details the user patient of the session into a view
        $user_id = $this->getUser()->getEmail();
        $query3 = "CREATE OR REPLACE VIEW P AS (";
        $query3 .= "SELECT * FROM patient ";
        $query3 .= "WHERE patient.email = :email)";
        $st3 = $connection->prepare($query3);
        $st3->bindValue('email', $user_id);
        $st3->execute();

        $query4 = 'SELECT * FROM P';
        $st4 = $connection->prepare($query4);
        $st4->execute();
        $user = $st4->fetchAll();

        //get the medical profile of the user into $med_prof[]
        $query6 = 'SELECT disease_id FROM medical_record WHERE patient_id = :id';
        $st6 = $connection->prepare($query6);
        $st6->bindValue('id',$user[0]['id']);
        $st6->execute();
        $med_prof = $st6->fetchAll();
        $med_prof_ids = [];
        for ($i=0; $i<count($med_prof); $i++) {
            $id = $med_prof[$i]['disease_id'];
            array_push($med_prof_ids, $id);
        }


        $high_risk = [];
        $medium_risk = [];
        $low_risk = [];
        $not_specified = [];
        
        //remove the gender not matching diseases from the guess1[]
        foreach ($guess1 as $disease) {
            if ($disease['gender'] != 'both' && $disease['gender'] != $user['gender']) {
                $key = array_search($disease, $guess1);
                unset($guess1[$key]);
            }
            
        }
        //get the age of the user
        $queryAge = "SELECT getAge(:email) AS age";
        $stAge=$connection->prepare($queryAge);
        $stAge->bindValue('email', $user_id);
        $stAge->execute();
        $age= $stAge->fetchAll();
        $user_age=$age[0]['age'];

        $probables = []; //an array to keep final guesses of object type Guess

        //downcast Disease objects to Guess objects
        foreach ($guess1 as $disease){
            $guess = new Guess();
            $guess->setID($disease['id']);
            $guess->setName($disease['name']);
            $guess->setSeverity($disease['severity']);
            $guess->setDescription($disease['description']);

            //comparing the age group of the disease with the age of the patient
            if ($disease['min_age'] != null && $disease['min_age'] > $user_age) {
                $dif = $disease['min_age'] - $user_age;
                if ($dif <= 5){
                    $guess->prob = $guess->prob - $dif*10;
                    array_push($probables,$guess);
                }elseif ($dif > 5){
                    $key = array_search($disease, $guess1);
                    unset($guess1[$key]); 
                }
                
            }
            if ($disease['max_age'] != null && $disease['max_age'] < $user_age) {
                $dif = $user_age - $disease['min_age'];
                if ($dif <= 5){
                    $guess->prob = $guess->prob - $dif*10;
                    array_push($probables,$guess);
                }elseif ($dif > 5){
                    $key = array_search($disease, $guess1);
                    unset($guess1[$key]);
                }

            }
            if ($disease['min_age'] == null && $disease['max_age']==null){
                array_push($probables,$guess);
            }

            //considering the prerequisites for the disease
            $query5 = "SELECT prereq_id FROM pre_req WHERE disease_id = :id";
            $st5 = $connection->prepare($query5);
            $st5->bindValue('id', $disease['id']);
            $st5->execute();
            $pre_req = $st5->fetchAll();
            foreach ($pre_req as $value) {
                if (!in_array($value['prereq_id'], $med_prof_ids)) {
                    $guess->prob = $guess->prob-10;
                }
            }
        }

        // separating final probables into  arrays according to their risk
       foreach ($probables as $disease){
            if ($disease->getSeverity() == 'High') {
                array_push($high_risk, $disease);
            }
            elseif ($disease->getSeverity() == 'Medium') {
                array_push($medium_risk, $disease);
            }
            elseif ($disease->getSeverity() == 'Low') {
               array_push($low_risk, $disease);
           }
           else{
                array_push($not_specified, $disease);
           }

        }

       return $this->render('SymptomChecker/PossibleDiagnoses.html.twig', array(
                'HIGH_RISK'=>$high_risk,
                'MEDIUM_RISK'=>$medium_risk,
                'LOW_RISK'=>$low_risk,
                'NOT_SPECIFIED'=>$not_specified
        )); 

    }

   
   
}

class Guess extends Disease{
    var $prob = 98;
}