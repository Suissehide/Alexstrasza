<?php

namespace App\Form;

use App\Entity\Article;
use App\Form\SondageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, array(
                'label' => "Titre",
                'attr' => array(
                    'placeholder' => "Titre",
                ),
            ))
            ->add('contenu', TextAreaType::class, array(
                'label' => "Contenu",
                'attr' => array(
                    'placeholder' => "Contenu",
                ),
            ))
            ->add('file', FileType::class, array(
                'label' => false,
                'multiple' => true,
                'data_class' => null,
                "mapped" => false,
            ))
            ->add('paths', CollectionType::class, array(
                'label' => false,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ))
            ->add('sondage', SondageType::class, array(
                'label' => false,
                "attr" => array(
                    'class' => "hidden"
                ),
            ))
            // ->add('date')
            // ->add('utilisateur')
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
