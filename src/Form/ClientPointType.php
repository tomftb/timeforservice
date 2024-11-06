<?php

namespace App\Form;

use App\Entity\ClientPoint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\Length;

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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClientPoint::class,
        ]);
    }
}
