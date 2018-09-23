<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, array(
                'label' => "Titre",
            ))
            ->add('contenu', TextAreaType::class, array(
                'label' => "Contenu",
            ))
            ->add('image', FileType::class, array(
				'label' => 'Image',
				'data_class' => null,
            ))
            ->add('sondage', EntityType::class, array(
                'class' => 'App\Entity\Sondage',
                'choice_label' => 'Sondage',
                'multiple' => false,
            ))
            ->add('date')
            ->add('utilisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
