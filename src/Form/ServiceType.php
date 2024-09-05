<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('startedAt', DateTimeType::class, [
                'date_label' => 'Starts On',
            ]) 
            ->add('endedAt', DateTimeType::class, [
                'date_label' => 'Starts On',
            ])
            ->add('time')  
            ->add('clientPoint', null, [
                'choice_label' => 'name',
                'placeholder' => 'Choose a client point',
            ])
            ->add('user', null, [
                'choice_label' => 'name',
                'placeholder' => 'Choose a user',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
