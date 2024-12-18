<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Facture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sending_date', null, [
                'widget' => 'single_text',
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Envoyé' => 'Envoyé',
                    'Annulé' => 'Annulé',
                    'Payé' => 'Payé',
                ],
                'placeholder' => 'Sélectionnez un statut', // Optionnel : Texte par défaut
                'data' => 'Envoyé', // Valeur par défaut : "Envoyé"
            ])
            ->add('amount')
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'fullname', // Affiche la propriété "fullname" du client
                'placeholder' => 'Sélectionnez un client', // Optionnel : Texte par défaut
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,

        ]);
    }
}
