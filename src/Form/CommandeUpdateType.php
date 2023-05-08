<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Livreur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class CommandeUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
       
            ->add('idProduit', NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
           
            ->add('status',ChoiceType::class, [
            'choices'  => [
                '' => '',             
                'Non confirmé' => '0',
                'Confirmé' => '1',
                ],
            ])

            ->add('idLivreur', ChoiceType::class, [
            'choices'  => [
                '' => '',             
                'Ali' => '1',
                'Mohamed' => '2',
                'Samir' => '3',
                'Mohsen' => '4',
                'Mokhtar' => '5',
                'Rania' => '5',
                'Amira' => '5',
                'Iheb' => '5',

                ],
            ])

            ->add('dateConfirmation', DateType::class, [
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
            'data_class' => Commande::class,
        ]);
    }
}
