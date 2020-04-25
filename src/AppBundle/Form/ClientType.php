<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ClientType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nom', TextType::class, array('label'=>'Nom','attr'=>array('class'=>'form-control','placeholder'=>'Nom du client')))
            ->add('prenom', TextType::class, array('label'=>'Prenom','attr'=>array('class'=>'form-control','placeholder'=>'Prenom du client')))
            ->add('raisoc', TextareaType::class, array('label'=>'Raisoc','attr'=>array('class'=>'form-control','placeholder'=>'Raison ')))
            ->add('adresse', TextareaType::class, array('label'=>'Adresse','attr'=>array('class'=>'form-control','placeholder'=>'Adresse client')))
            ->add('telephone', TextType::class, array('label'=>'Telephone','attr'=>array('class'=>'form-control','placeholder'=>'Telephone client')))
            /*->add('email', TextType::class, array('label'=>'E-mail','attr'=>array('class'=>'form-control','placeholder'=>'E-mail client')))*/
            ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Client'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_client';
    }


}
