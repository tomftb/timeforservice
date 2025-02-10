<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Model\TypeOfServiceEnum;
use App\Model\YesOrNoEnum;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class,[
                'label'=>'Description',
                "attr" => array("rows" => 10)
            ])
            ->add('typeOfService',EnumType::class,[
                'class'=> TypeOfServiceEnum::class,
                'required' => true,
                'data_class'=>null,
            ])
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
            ->add('materialCosts',null,[
                'label'=>'Material costs (gross)'
            ]) 
            ->add('clientPoint', null, [
                'choice_label' =>  function ($clientPoint) {
                        return $clientPoint->getName() . ' (' .$clientPoint->getStreet().",". $clientPoint->getTown().")";
                    },
                'placeholder' => 'Choose a client point',
                'autocomplete'=> true
            ])
            ->add('employe', null, [
                'choice_label' => function ($employe) {
                        return "[".$employe->getId()."] ".$employe->getFirstName() ." ".$employe->getLastName();
                    },
                'placeholder' => 'Choose a employe',
                'autocomplete'=> true
            ])
            ->add('classificationOfActivities', null, [
                'label'=>'Type of service',
                'choice_label' =>  function ($classificationOfActivities) {
                        return "[".$classificationOfActivities->getCode() . '] ' .$classificationOfActivities->getName();
                    },
                'placeholder' => 'Choose type of service',
                'autocomplete'=> true
            ])
            ->add('notified',EnumType::class,[
                'class'=> YesOrNoEnum::class,
                'label'=>'Notified',
                'required' => true,
                'data_class'=>null,
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
