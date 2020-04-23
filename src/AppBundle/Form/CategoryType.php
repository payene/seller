<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CategoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('catname', TextType::class, array('label'=>'Designation','attr'=>array('class'=>'form-control','placeholder'=>'Nom de la categorie')))
        ->add('catdesc', TextareaType::class, array('label'=>'Description','attr'=>array('class'=>'form-control','placeholder'=>'Description de la categprie')))
        ->add('save', SubmitType::class, array('label'=>'Valider','attr'=>array('class'=>'btn btn-md btn-primary pull-right m-t-n-xs')))
        ->add('parent', EntityType::class, ['class' => 'AppBundle\Entity\Category', 'required' => false,
         'query_builder' => function ($er) { return $er->createQueryBuilder('c') ->where('c.id = c.parent')->orderBy('c.catname', 'ASC');}])
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Category'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_category';
    }


}
