<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DeliveryAdressType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstname', null, array("required" => false))
                ->add('lastname', null, array("required" => false))
                ->add('email', null, array("required" => false))
                ->add('phone', null, array("required" => false))
                ->add('quartier', null, array("required" => false))
                ->add('address', null, array("required" => false))
                ->add('livraisonDomicile', ChoiceType::class, [
                    "mapped" => false,
                    "data" => true,
                    'choices'  => [
                        'livraison à domicile' => true,
                        'Livraison en magasin' => false,
                    ],
                    "expanded" =>true, 
                    "multiple" => false,
                ])
                ->add('dureeLivraison', EntityType::class, [
                    "class" => "AppBundle:DureeLivraison",
                    "mapped" => false,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('l');
                    },
                    'choice_label' => 'libelle',
                    "expanded" =>true, 
                    "multiple" => false,
                ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\DeliveryAdress'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_deliveryadress';
    }


}
