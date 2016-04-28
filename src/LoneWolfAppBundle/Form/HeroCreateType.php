<?php

namespace LoneWolfAppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HeroCreateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('combatSkill', IntegerType::class, array(
                'attr' => array('min' => 1, 'max' => 999),
            ))
            ->add('enduranceMax', IntegerType::class, array(
                'attr' => array('min' => 1, 'max' => 999),
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LoneWolfAppBundle\Entity\Hero'
        ));
    }
}
