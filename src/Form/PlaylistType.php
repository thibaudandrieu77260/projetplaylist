<?php
// src/Form/PlaylistType.php

namespace App\Form;

use App\Entity\Playlist;
use App\Entity\Musique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PlaylistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom de la playlist'])
            ->add('type', TextType::class, ['label' => 'Type'])
            ->add('langue', TextType::class, ['label' => 'Langue'])
            ->add('taille', NumberType::class, ['label' => 'Taille'])
            ->add('musiques', EntityType::class, [
                'class' => Musique::class,
                'choice_label' => 'titre',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Ajouter des musiques',
            ])
            ->add('submit', SubmitType::class, ['label' => 'Créer la playlist']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Playlist::class,
        ]);
    }
}
