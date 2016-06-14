<?php
 namespace AppBundle\Form;


 use Symfony\Component\Form\AbstractType;
 use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
 use Symfony\Component\Form\Extension\Core\Type\SubmitType;
 use Symfony\Component\Form\Extension\Core\Type\TextType;
 use Symfony\Component\Form\FormBuilderInterface;
 use symfony\Component\OptionsResolver\OptionsResolver;

 class DoctorNew extends AbstractType{
     public function buildform(FormBuilderInterface $builder, array $options){

         $builder
             ->add('name', TextType::class, array('label'=>'Name of the Doctor'))
             ->add('gender', choiceType::class,
                 ['choices'=>['male'=>'male', 'female'=>'female'], 'choices_as_values'=>true, 'placeholder'=>'-SELECT-'])
             ->add('speciality', TextType::class)
             ->add('save', submitType::class, array('label'=>'Add','attr'=>array('class'=>'btn btn-primary')))
             ->add('continue', submitType::class, ['label'=>'Add Hospital', 'attr'=>array('class'=>'btn btn-primary')]);
     }
     
     public function configureOptions(OptionsResolver $resolver)
     {
         $resolver->setDefaults(array(
             'data_class' => 'AppBundle\Entity\Doctor',
         ));
     }
 }