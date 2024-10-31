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
            ->add('time',null,[
                'label'=>'Time (in minutes)'
            ]) 
            ->add('route',null,[
                'label'=>'Route (in kilometers)'
            ]) 
            ->add('clientPoint', null, [
                'choice_label' =>  function ($client) {
                        return $client->getName() . ' (' .$client->getStreet().",". $client->getTown().")";
                    },
                'placeholder' => 'Choose a client point',
                'autocomplete'=> true
            ])
            ->add('user', null, [
                'choice_label' => function ($user) {
                        return $user->getFirstName() ." ".$user->getLastName(). ' (' .$user->getName().")";
                    },
                'placeholder' => 'Choose a user',
                'autocomplete'=> true
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
