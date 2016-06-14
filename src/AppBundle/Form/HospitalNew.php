<?php
 namespace AppBundle\Form;



 use Symfony\Component\Form\AbstractType;
 use Symfony\Component\Form\Extension\Core\Type\SubmitType;
 use Symfony\Component\Form\Extension\Core\Type\TextareaType;
 use Symfony\Component\Form\Extension\Core\Type\TextType;
 use Symfony\Component\Form\FormBuilderInterface;
 use Symfony\Component\OptionsResolver\OptionsResolver;

 class HospitalNew extends AbstractType{
     public function buildform(FormBuilderInterface $builder, array $options){
         
         $builder
             ->add('name', TextType::class)
             ->add('district',TextType::class )
             ->add('address', TextType::class)
             ->add('telephone', TextType::class)
             ->add('email', TextType::class, array('required'=>false))
             ->add('otherDetails', TextareaType::class, array('required'=>false))
             ->add('save', submitType::class, array('label'=>'Add hospital','attr'=>array('class'=>'btn btn-primary')))
             ->add('addmore', submitType::class, array('label'=>'Add more hospitals','attr'=>array('class'=>'btn btn-primary')));
         
     }
     
     public function configureOptions(OptionsResolver $resolver)
     {
         $resolver->setDefaults(array(
             'data_class' => 'AppBundle\Entity\Hospital',
         ));
     }
 }