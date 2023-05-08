<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('idUtilisateur', NumberType::class, [
            //     'attr' => [
            //         'class' => 'form-control',
            //     ],
            // ])
            // ->add('idProduit', NumberType::class, [
            //     'attr' => [
            //         'class' => 'form-control',
            //     ],
            // ])
            // ->add('date', DateType::class, [
            //     'attr' => [
            //         'class' => 'form-control',
            //     ],
            // ])
            ->add('role', ChoiceType::class, [
                'choices'  => [
                    ''=>'',
                    'Echange' => 'echange',
                    'Achat' => 'achat',

                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            
            ])
            // ->add('status', TextType::class, [
            //     'attr' => [
            //         'class' => 'form-control',
            //     ],
            // ])
            // ->add('idLivreur', NumberType::class, [
            //     'attr' => [
            //         'class' => 'form-control',
            //     ],
            // ])
            // ->add('idUtilisateura', NumberType::class, [
            //     'attr' => [
            //         'class' => 'form-control',
            //     ],
            // ])
            ->add('dateLivraison', DateType::class, [
                'attr' => [
                    
                    'class' => 'form-control',
                ],
            
            ])
            // ->add('dateConfirmation', DateType::class, [
            //     'attr' => [
            //         'class' => 'form-control',
            //     ],
            // ])
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
