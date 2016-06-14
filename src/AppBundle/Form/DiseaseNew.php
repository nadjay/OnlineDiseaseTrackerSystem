<?php
/**
 * Created by PhpStorm.
 * User: TOSHIBA
 * Date: 5/18/2016
 * Time: 9:15 PM
 */

namespace AppBundle\Form;



use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DiseaseNew extends AbstractType
{
    public function buildform(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('gender', ChoiceType::class,
                ['choices'=>['Male' => 'Male', 'Female' => 'Female', 'both'=>'both'], 'choices_as_values' => true, 'placeholder' =>'-SELECT-'])
            ->add('minAge', IntegerType::class, ['required'=>false])
            ->add('maxAge', IntegerType::class, ['required'=>false])
            ->add('severity', ChoiceType::class,
                ['choices'=>['High'=>'High', 'Medium'=>'Medium', 'Low'=>'Low'], 'choices_as_values'=>true, 'placeholder'=>'-SELECT-'])
            ->add('description',TextareaType::class)
            ->add('addSymptoms', submitType::class,array('label'=>'Add Symptoms', 'attr'=>array('class'=>"btn btn-primary")))
            ->add('finish', submitType::class, array('label'=>'Finish', 'attr'=>array('class'=>"btn btn-primary")))
            ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Disease',
        ));
    }

}