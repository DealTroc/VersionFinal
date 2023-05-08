<?php

namespace App\Form;

use App\Entity\Facture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;


class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          
            ->add('dateFacturation',DateType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('commission', NumberType::class, [
                'scale' => 2, // 2 chiffres après la virgule
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('statut', ChoiceType::class, [
                'choices'  => [
                    '' => '',
                    'En attente' => 'En attente',
                    'Payé' => 'Payé',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
         
            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}
