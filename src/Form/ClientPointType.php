<?php

namespace App\Form;

use App\Entity\ClientPoint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientPointType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('street')
            ->add('zipCode')
            ->add('town')
            ->add('email')
            ->add('phoneNumber')
            ->add('clientId')
                /*
            ->add('imageFilename', ChoiceType::class, [
                'choices' => [
                    'Choose an image...' => '',
                    'Planet 1' => 'planet-1.png',
                    'Planet 2' => 'planet-2.png',
                    'Planet 3' => 'planet-3.png',
                    'Planet 4' => 'planet-4.png',
                ]
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClientPoint::class,
        ]);
    }
}
