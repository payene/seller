<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class PrixType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

        ->add('montant', TextType::class, array('label'=>'Montant','attr'=>array('class'=>'form-control','placeholder'=>'Montant')))
        ->add('dateDebut', Texttype::class, array('label'=>'date Debut','attr'=>array('class'=>'form-control', 'autocomplete' => 'off')))
        ->add('dateFin', TextType::class, array('label'=>'date Fin','attr'=>array('class'=>'form-control', 'autocomplete' => 'off')))
        ->add('article', EntityType::class, array(
                'class'=> 'AppBundle:Article',
                'choice_label' => 'Designation',
                'placeholder' => '--Choisir l\'article--',
                'attr'=>array('class'=>'form-control')
            ))
        ->add('client', EntityType::class, array(
                'class'=> 'AppBundle:Client',
                'choice_label' => 'nom',
                'placeholder' => '--Choisir le client--',
                'attr'=>array('class'=>'form-control')
            ))
            
            ->add('save', SubmitType::class, array('label'=>'Valider','attr'=>array('class'=>'btn btn-md btn-primary pull-right m-t-n-xs')))
            ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Prix'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_prix';
    }


}
