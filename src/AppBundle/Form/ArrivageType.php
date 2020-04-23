<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Article;

class ArrivageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('article',EntityType::class,[
                'label' => "Article",
                'required' => true,
                'class' => Article::class,
                'query_builder' => function ($er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.designation', 'ASC')
                        ;
                },
             ])
            ->add('qte')
            ->add('prixAchat')
            ->add('tva')
            ->add('marge')
            ->add('taxes')
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Arrivage'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_arrivage';
    }


}
