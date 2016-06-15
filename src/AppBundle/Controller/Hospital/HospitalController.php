<?php
namespace AppBundle\Controller\Hospital;
use AppBundle\Entity\Doc_Hos;
use AppBundle\Entity\Hospital;
use AppBundle\Form\HospitalNew;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class HospitalController extends Controller{

    /**
     * @Route("/admin/add hospital/{data_id}", defaults={"data_id" = 0},  name="add_hospital")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function selectDoctorAction(Request $request, $data_id){
        $em = $this->getDoctrine()->getManager();
        $doctors =$em->getRepository('AppBundle:Doctor')->findAll();

        if ($data_id == 0){
            $form = $this->createFormBuilder()
                ->add('selectADoctor',choiceType::class, [
                    'choices' => $doctors,
                    'choice_label'=> function($doctor){
                        return $doctor->getName()." - ".$doctor->getSpeciality();
                    },
                    'choice_attr'=>function($doctor){
                        return ['value' => $doctor->getID()];
                    },
                    'choices_as_values'=>true,
                    'placeholder'=>'Choose a doctor',
                ])
                ->add('existing', SubmitType::class, ['label'=> 'Add Existing Hospital', 'attr'=>array('class'=>'btn btn-primary')])
                ->add('new', submitType::class, ['label'=> 'Add New Hospital', 'attr'=>array('class'=>'btn btn-primary')])
                ->getForm();

        }elseif ($data_id!=0){
            $selected = $em->getRepository('AppBundle:Doctor')->find($data_id);

            $form = $this->createFormBuilder()
                ->add('selectADoctor',choiceType::class, [
                    'choices' => $doctors,
                    'choice_label'=> function($doctor){
                        return $doctor->getName();
                    },
                    'choice_attr'=>function($doctor){
                        return ['value' => $doctor->getID()];
                    },
                    'data'=>$selected,
                    'choices_as_values'=>true,
                    'placeholder'=>'Choose a doctor',
                ])
                ->add('existing', SubmitType::class, ['label'=> 'Add Existing Hospital', 'attr'=>array('class'=>'btn btn-primary')])
                ->add('new', submitType::class, ['label'=> 'Add New Hospital', 'attr'=>array('class'=>'btn btn-primary')])
                ->getForm();
        }



        $form -> handleRequest($request);

        if($form -> isValid()){
            $data= $form->getData();
            $doctor=current($data);
            $doctor_id=$doctor->getID();

            if($form->get('existing')->isClicked()){
                return $this->redirectToRoute('existing_hospital',array('doctor_id'=>$doctor_id));
            }

            if (($form->get('new')->isClicked())){
                return $this->redirectToRoute('new_hospital',array('doctor_id'=>$doctor_id));
            }
        }

        return $this->render('hospital/addHospital.html.twig', array(
            'form'=>$form->createView(),
        ));

    }

    /**
     * @Route("/admin/add hospital/existing/{doctor_id}", name="existing_hospital")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addExistingHospitalAction(Request $request,$doctor_id){
        $em = $this->getDoctrine()->getEntityManager();
        $hospitals= $em->getRepository('AppBundle:Hospital')->findAll();

        $query1 = "SELECT doc__hos.hospital_id FROM doc__hos WHERE doc__hos.doctor_id = $doctor_id";
        $connection =$em->getConnection();
        $st1=$connection->prepare($query1);
        $st1->execute();
        $defaults = $st1->fetchAll();
        $mark = [];
        for($i=0;$i<count($defaults); $i++){
            $hos = $em->getRepository('AppBundle:Hospital')->find($defaults[$i]['hospital_id']);
            array_push($mark, $hos);
        }


        $form = $this->createFormBuilder()
            ->add('hospitalID', choiceType::class,['label'=>'Hospital',
                'choices'=>$hospitals,
                'choice_label'=>function($hospital){
                    return $hospital->getName();
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
            for($i=0; $i<count($data['hospitalID']); $i++) {
                if (!in_array($data['hospitalID'][$i],$mark)){
                    $doc_hos = new Doc_Hos();
                    $doc_hos->setHospitalID($data['hospitalID'][$i]);
                    $doc_hos->setDoctorID($doctor);
                    $em->persist($doc_hos);
                    $em->flush();
                }
            }
            return $this->redirectToRoute("success_message");

        }


        return $this->render('hospital/existing.html.twig', array(
            'form'=>$form->createView(),
        ));
    }

    /**
     * @Route ("/admin/add_hospital/new/{doctor_id}", name="new_hospital")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addNewHospitalAction(Request $request, $doctor_id){
        $hospital = new Hospital();

        $form = $this->createForm(HospitalNew::class, $hospital);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($hospital);
            $em->flush();

            $doctor=$em->getRepository('AppBundle:Doctor')->find($doctor_id);
            $data=$form->getData();
            $doc_hos = new Doc_Hos();
            $doc_hos->setHospitalID($data);
            $doc_hos->setDoctorID($doctor);
            $em->persist($doc_hos);
            $em->flush();
            return $this->redirectToRoute('add_hospital',array('data_id'=>$doctor_id));

        }

        return $this->render('hospital/new.html.twig', array(
            'form'=>$form->createView(),
        ));
    }

    /**
     * @Route("/patient/find_hospital/{doctor_id}", defaults={"doctor_id"=0}, name="Find_Hospital")
     * @Security("has_role('ROLE_PATIENT')")
     */

    public function findHospitalAction(Request $request, $doctor_id){
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();

        $statement = $connection->prepare("SELECT doctor.id, doctor.name, doctor.speciality FROM doctor");
        $statement->execute();
        $doctors=$statement->fetchAll();

        if($request->isMethod(("POST"))){
            $doctor_id = $request->request->get('doctor_id');
        }

        if ($doctor_id == 0){
            return $this->render('hospital/view.html.twig', array('doctors' => $doctors,
                'doctor_id' => $doctor_id
            ));
        }

        $query1 = "SELECT hospital.name, hospital.address, hospital.telephone, hospital.email, hospital.other_details ";
        $query1 .= "FROM hospital WHERE hospital.id IN (";
        $query1 .= "SELECT doc__hos.hospital_id FROM doc__hos WHERE doc__hos.doctor_id = $doctor_id)";
        $statement1 = $connection->prepare($query1);
        $statement1->execute();
        $hospitals = $statement1->fetchAll();


        return $this->render('hospital/view.html.twig',
            array('doctors'=>$doctors,
                'doctor_id' => $doctor_id,
                'hospitals' => $hospitals
            ));
        

    }

    /**
     * @Route("/admin/add hospitals to doctors", name="hospitals_to_doctors")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function hospitalsToDoctorsAction(){
        return $this->redirectToRoute('add_hospital');
    }

    
}