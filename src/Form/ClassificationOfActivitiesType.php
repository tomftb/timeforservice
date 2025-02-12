<?php

namespace App\Form;

use App\Entity\ClassificationOfActivities;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\Length;

class ClassificationOfActivitiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'required' => true,
                'constraints' => [new Length(['min' => 3])],
            ])
            ->add('code',TextType::class,[
                'required' => true,
                'constraints' => [new Length(['min' => 1])],
            ])

            ->add('price',NumberType::class,[
                'required' => true,
                'constraints' => [new Length(['min' => 1])],
            ])
            ->add('unit',TextType::class,[
                'required' => true,
                'constraints' => [new Length(['min' => 1])],
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClassificationOfActivities::class,
        ]);
    }
}
