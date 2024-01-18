<?php

namespace App\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CryptoAnalyseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('asset', ChoiceType::class, [
                'label'=> 'Asset',
                'choices' => [
                    'BTC' => 'btc',
                    'ETH' => 'eth',
                    // Add more options as needed
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'Address',
                'attr' => [
                    'placeholder' => 'Enter the address to analyze',
                ],
            ])
            ->add('fromDate', DateType::class, [
                'label' => 'From Date',
                'widget' => 'single_text',
                'attr' => [
                    'max' => (new \DateTime())->format('Y-m-d'),
                ],
                'required' => false,
                'mapped' => false

                          
            ])
             ->add('toDate', DateType::class, [ 
                'label'=> 'To Date',
                'widget' => 'single_text',
                'attr' => [
                    'max' => (new \DateTime())->format('Y-m-d'),
                ],
                'required' => false,
                'mapped' => false


                ])         

            ->add('save', SubmitType::class, [
                'label' => 'Analyze',
            ])->add('reset', ResetType::class, [
                'label' => 'Reset'
            ])  
        ;
    }

    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Transaction',
        ]);
    }
}   