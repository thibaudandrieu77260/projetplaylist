<?php

// src/Form/playlistType.php
namespace App\Form;

use App\Entity\Playlist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaylistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la playlist',
                'required' => true, // Assurez-vous que ce champ est requis
            ])
            ->add('type', TextType::class, [
                'label' => 'Type (sad, party, ..)',
            ])
            
            ->add('taille', NumberType::class, [
                'label' => 'taille',
            ])
           
            ->add('langue', TextType::class, [
                'label' => 'langue',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Playlist::class,
        ]);
    }
}
