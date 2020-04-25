<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use AppBundle\Form\SuscriberType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('suscriber', SuscriberType::class)
                ->add('email', EmailType::class, array('label' => 'Email', "attr" => [ "class" => "form-control" ]) )
                ->add('username', null, array('label' => 'Nom d\'utilisateur', "attr" => [ "class" => "form-control" ]) )
                ->add('plainPassword', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'first_options' => array('label' => "Mot de passe", "attr" => [ "class" => "form-control" ]),
                    'second_options' => array('label' => 'Confirmation du mot de passe', "attr" => [ "class" => "form-control" ]),
                    'invalid_message' => 'fos_user.password.mismatch',
                ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'userbundle_user';
    }


}
