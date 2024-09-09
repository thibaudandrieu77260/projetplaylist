<?php
// src/Form/MusiqueType.php

namespace App\Form;

use App\Entity\Musique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MusiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, ['label' => 'Titre de la musique'])
            ->add('artiste', TextType::class, ['label' => 'Artiste'])
            ->add('album', TextType::class, ['label' => 'Album'])
            ->add('duree', IntegerType::class, ['label' => 'Durée (en secondes)'])
            ->add('youtubeUrl', TextType::class, [
                'label' => 'URL YouTube',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Musique::class,
        ]);
    }
}
