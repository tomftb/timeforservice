<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\Length;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'required' => true,
                'constraints' => [new Length(['min' => 3])],
            ])
            ->add('street',TextType::class,[
                'required' => true,
                'constraints' => [new Length(['min' => 3])],
            ])
            ->add('zipCode',TextType::class,[
                'required' => true,
                'constraints' => [new Length(['min' => 5])],
            ])
            ->add('town',TextType::class,[
                'required' => true,
                'constraints' => [new Length(['min' => 3])],
            ])
            ->add('nin',TextType::class,[
                'required' => true,
                'constraints' => [new Length(['min' => 10])],
            ])
            ->add('hourlyRate',NumberType::class,[
                'required' => true,
                'constraints' => [new Length(['min' => 1])],
            ])
            ->add('kilometerRate',NumberType::class,[
                'required' => true,
                'constraints' => [new Length(['min' => 1])],
            ])
            ->add('mileageCode',TextType::class,[
                'required' => true,
                'constraints' => [new Length(['min' => 3])],
            ])
            ->add('mileageName',TextType::class,[
                'required' => true,
                'constraints' => [new Length(['min' => 3])],
            ])
            ->add('mileageUnit',TextType::class,[
                'required' => true,
                'constraints' => [new Length(['min' => 1])],
            ])
            ->add('mileageRate',NumberType::class,[
                'required' => true,
                'constraints' => [new Length(['min' => 1])],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
