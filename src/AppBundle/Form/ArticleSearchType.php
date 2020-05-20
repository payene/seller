<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ArticleSearchType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('designation', TextType::class, array('required'=>false,'attr'=>array('class'=>'form-control','placeholder'=>'Nom de l\'article')))
            ->add('priceMin', TextType::class, array('mapped' => false,'required'=>false,'attr'=>array('class'=>'form-control','placeholder'=>'Prix compris entre')))
            ->add('priceMax', TextType::class, array('mapped' => false,'required'=>false,'attr'=>array('class'=>'form-control','placeholder'=>'Et')))
            ->add('stockMin', TextType::class, array('label'=>'Stock','required'=>false, 'mapped' => false,'attr'=>array('class'=>'form-control','placeholder'=>'Stock compris entre')))
            ->add('stockMax', TextType::class, array('label'=>'Stock','required'=>false, 'mapped' => false,'attr'=>array('class'=>'form-control','placeholder'=>'Et')))
            ->add('typeArticle', EntityType::class, array('class'=> 'AppBundle:TypeArticle', 'required' => false, 'choice_label' => 'designation','placeholder' => '--Choisir le type --','attr'=>array('class'=>'form-control')
            ))
            
            ->add('save', SubmitType::class, array('label'=>'Rechercher','attr'=>array('class'=>'btn btn-md btn-primary pull-right m-t-n-xs')))
            ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Article'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_article';
    }


}
