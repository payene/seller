<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ArticleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('typeArticle', EntityType::class, array(
                'class'=> 'AppBundle:TypeArticle',
                'choice_label' => 'designation',
                'placeholder' => '--Choisir le type --',
                'attr'=>array('class'=>'form-control'),
                'query_builder' => function ($er) {
                    return $er->createQueryBuilder('t')
                    ->orderBy("t.designation", "ASC");
                }
            ))
            /*->addEventListener(
                FormEvents::PRE_SET_DATA,
                [$this, 'onPreSetData']
            )*/
            ->add('caracteristiques', EntityType::class, array(
                "mapped" => false,
                'class'=> 'AppBundle:Caracteristique',
                'choice_label' => 'libelle',
                'attr'=>array('class'=>'form-control'),
                'query_builder' => function ($er) use ($options) {
                    $qb =  $er->createQueryBuilder('c')
                              ->join('c.typeArticles', 't', 'WITH', 't.id = 0');
                    return $qb;
                }  
            ))
            ->add('defaultPrice', TextType::class, array('label'=>'Prix','attr'=>array('class'=>'form-control','placeholder'=>'Prix de l\'article')))
            ->add('designation', TextType::class, array('label'=>'Designation','attr'=>array('class'=>'form-control','placeholder'=>'Nom de l\'article')))
            ->add('description', TextareaType::class, array('label'=>'description','attr'=>array('class'=>'form-control','placeholder'=>'Description de l\'article')))
            ->add('stock', TextType::class, array('label'=>'Stock','attr'=>array('class'=>'form-control','placeholder'=>'Stock de l\'article')))
            
            ;
    }
    
    /*public function onPreSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $type = $event->getData();
        dump($type); exit;
        $form->add('caracteristiques', EntityType::class, array(
            "mapped" => false,
            'class'=> 'AppBundle:Caracteristique',
            'choice_label' => 'libelle',
            'attr'=>array('class'=>'form-control'),
            'query_builder' => function ($er) use ($type) {
                $qb =  $er->createQueryBuilder('c');
                        if($type != null){
                            $qb->join('c.typeArticles', 't', 'WITH', 't.id = ' . $type->getId());
                        }
                        else{
                            $qb->join('c.typeArticles', 't', 'WITH', 't.id = 0');
                        }
                return $qb;
            }  
        ));
    }*/
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Article',
            'type' => null,
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
