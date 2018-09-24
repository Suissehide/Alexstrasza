<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => "Identifiant",
                ),
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array(
                    'label' => false,
                    'attr' => array(
                        'placeholder' => "Mot de passe",
                )),
                'second_options' => array(
                    'label' => false,
                    'attr' => array(
                    'placeholder' => "Confirmer le mot de passe",
                )),
            ))
            ->add('roles', ChoiceType::class, array(
                'label' => "Rôles",
                'choices' => array(
                    'ROLE_USER' => 'ROLE_USER',
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                ),
                'multiple' => true,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
