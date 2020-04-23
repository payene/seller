<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ArticleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, array(
                'class'=> 'AppBundle:Category',
                'choice_label' => 'catname',
                'placeholder' => '--Choisir la categorie--',
                'attr'=>array('class'=>'form-control'),
                'query_builder' => function ($er) {
                    return $er->createQueryBuilder('c')
                        ->andwhere('c.parent != c.id')
                        ->orderBy('c.catname', 'ASC');
                }  
            ))
            ->add('defaultPrice', TextType::class, array('label'=>'Prix','attr'=>array('class'=>'form-control','placeholder'=>'Prix de l\'article')))
            ->add('designation', TextType::class, array('label'=>'Designation','attr'=>array('class'=>'form-control','placeholder'=>'Nom de l\'article')))
            ->add('description', TextareaType::class, array('label'=>'description','attr'=>array('class'=>'form-control','placeholder'=>'Description de l\'article')))
            ->add('stock', TextType::class, array('label'=>'Stock','attr'=>array('class'=>'form-control','placeholder'=>'Stock de l\'article')))
            
            ->add('save', SubmitType::class, array('label'=>'Valider','attr'=>array('class'=>'btn btn-md btn-primary pull-right m-t-n-xs')))
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
