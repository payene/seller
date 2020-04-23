<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class FournisseurType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        /*->add('user', EntityType::class, array(
                'class'=> 'UserBundle:User',
                'choice_label' => 'id',
                'placeholder' => '--Choisir la categorie--',
                'attr'=>array('class'=>'form-control')
            ))*/
        ->add('raisoc', TextType::class, array('label'=>'Raison sociale','attr'=>array('class'=>'form-control','placeholder'=>'Raison sociale du fournisseur')))
            ->add('adresse', TextareaType::class, array('label'=>'Adresse','attr'=>array('class'=>'form-control','placeholder'=>'Adresse fournisseur')))
            ->add('telephone', TextType::class, array('label'=>'Telephone','attr'=>array('class'=>'form-control','placeholder'=>'Telephone fournisseur')))
            ->add('email', TextType::class, array('label'=>'E-mail','attr'=>array('class'=>'form-control','placeholder'=>'E-mail fournisseur')))
            
            ->add('save', SubmitType::class, array('label'=>'Valider','attr'=>array('class'=>'btn btn-md btn-primary pull-right m-t-n-xs')));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Fournisseur'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_fournisseur';
    }


}
