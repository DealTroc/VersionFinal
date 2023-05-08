<?php

namespace App\Form;

use App\Entity\LigneFacture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;






class LigneFactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
      
       
            ->add('prixVente', NumberType::class, [
                'scale' => 2, // 2 chiffres après la virgule
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('prixLivraison', NumberType::class, [
                'scale' => 2, // 2 chiffres après la virgule
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('prixTotal', NumberType::class, [
                'scale' => 2, // 2 chiffres après la virgule
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('revenu', NumberType::class, [
                'scale' => 2, // 2 chiffres après la virgule
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
           
        ;
      
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LigneFacture::class,
        ]);
    }




    

   




}
