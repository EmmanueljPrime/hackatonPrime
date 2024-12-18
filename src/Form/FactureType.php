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
                'attr' => ['class' => 'form-control', 'placeholder' => 'Entrez la date d\'envoi'],
                'label' => 'Date d\'envoi',
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Envoyé' => 'Envoyé',
                    'Annulé' => 'Annulé',
                    'Payé' => 'Payé',
                ],
                'placeholder' => 'Sélectionnez un statut',
                'data' => 'Envoyé', // Valeur par défaut
                'attr' => ['class' => 'form-control'],
                'label' => 'Statut',
            ])
            ->add('amount', null, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Entrez le montant'],
                'label' => 'Montant',
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'fullname',
                'placeholder' => 'Sélectionnez un client',
                'attr' => ['class' => 'form-control'],
                'label' => 'Client',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,

        ]);
    }
}
