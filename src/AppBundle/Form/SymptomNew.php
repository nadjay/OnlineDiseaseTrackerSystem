<?php
/**
 * Created by PhpStorm.
 * User: TOSHIBA
 * Date: 5/24/2016
 * Time: 11:38 PM
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SymptomNew extends AbstractType
{
    public function buildform(FormBuilderInterface $builder, array $options){
        $builder
            ->add('name', TextType::class,array('label'=>'Symptom Name'))
            ->add('save', SubmitType::class,array('label'=>'Add'));
    }

    /**
     * @param OptionsResolver $resolver
     */

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Symptom',
        ));
    }
}