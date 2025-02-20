<?php

namespace App\Form;

use App\Entity\ClientPoint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Validator\Constraints\Length;
use App\Model\YesOrNoEnum;

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
            ->add('sendNotify',EnumType::class,[
                'class'=> YesOrNoEnum::class,
                'required' => true,
                'data_class'=>null,
            ])
            ->add('phoneNumber')
            ->add('client', null, [
                'choice_label' => 'name',
                'placeholder' => 'Choose a client',
                'autocomplete'=> true
            ])
            ->add('distance',NumberType::class,[
                'label'=>'Distance (km)',
                'required' => true,
                'constraints' => [new Length(['min' => 0])],
            ])
            ->add('active',EnumType::class,[
                'class'=> YesOrNoEnum::class,
                'label'=>'Active',
                'required' => true
            ])
            ->add('deleted',EnumType::class,[
                'class'=> YesOrNoEnum::class,
                'label'=>'Deleted',
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClientPoint::class,
        ]);
    }
}
