<?php

namespace App\Form;

use App\Entity\Sondage;
use App\Entity\Option;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SondageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('titre', TextType::class, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => "Quelle est la question de votre sondage ?",
                ),
            ))
            // ->add('option', OptionType::class)
            // ->add('article')
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sondage::class,
        ]);
    }
}
