<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class TypeArticleType extends AbstractType
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
            ->add('caracteristiques', EntityType::class, array(
                'class'=> 'AppBundle:Caracteristique',
                'choice_label' => 'libelle',
                'attr'=>array('class'=>'form-control'),
                'query_builder' => function ($er) {
                    return $er->createQueryBuilder('c');
                }  ,
                "multiple" => true,
                "expanded" => false,
            ))
            ->add('prixMin', TextType::class, array('label'=>'Prix minimal', 'mapped' => false, 'attr'=>array('class'=>'form-control','placeholder'=>'Prix minimal')))
            ->add('prixMax', TextType::class, array('label'=>'Prix maximal', 'mapped' => false, 'attr'=>array('class'=>'form-control','placeholder'=>'Prix maximal')))
            ->add('designation', TextType::class, array('label'=>'Designation','attr'=>array('class'=>'form-control','placeholder'=>'Nom du type d\'article')))
            ->add('description', TextareaType::class, array('label'=>'description','attr'=>array('class'=>'form-control','placeholder'=>'Description du type d\'article')))
            
            ->add('save', SubmitType::class, array('label'=>'Valider','attr'=>array('class'=>'btn btn-md btn-primary pull-right m-t-n-xs')))
            ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\TypeArticle'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_typearticle';
    }


}
