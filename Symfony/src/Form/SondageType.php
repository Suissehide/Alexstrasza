<?php

namespace App\Form;

use App\Entity\Sondage;
use App\Entity\Reponse;
use App\Form\ReponseType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SondageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('titre', TextType::class, array(
                'label' => false,
                'required' => false,
                'attr' => array(
                    'placeholder' => "Quelle est la question de votre sondage ?",
                ),
            ))
            ->add('reponses', CollectionType::class, array(
                'label' => false,
                'entry_type' => ReponseType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'delete_empty' => true,
            ))
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
