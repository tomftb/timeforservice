<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class ServiceLoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('login', null, [
                'label'=>'Login:',
                'required' => true,
                // unmapped means that this field is not associated to any entity property
                'mapped' => false
            ])
            ->add('password',null, [
                'label'=>'Login:',
                'required' => true,
                // unmapped means that this field is not associated to any entity property
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
