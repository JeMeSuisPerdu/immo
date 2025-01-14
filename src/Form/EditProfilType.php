<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EditProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('username', TextType::class, [
            'label' => 'Nom d\'utilisateur',
        ])
        ->add('email', EmailType::class, [
            'label' => 'E-mail',
        ])
        // Ajouter d'autres champs si nécessaire
        ->add('save', SubmitType::class, ['label' => 'Enregistrer votre profil']);
    }

}