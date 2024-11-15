<?php

namespace App\Form;

use App\Entity\ServiceAttachment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class ServiceAttachmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('files', FileType::class, [
                'label' => 'Set file/files (IMAGE/PDF)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                'multiple' => true,
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using attributes
                // in the associated entity, so you can use the PHP constraint classes
                /* NOT WORKING WITH multiple = true
                'constraints' => [
                    new File([
                        'maxSize' => '8096k',

                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                            "image/*",
                        ],
                        'mimeTypesMessage' => 'Please upload a valid IMAGE/PDF file',
                    ])
                ],
                 */
                'attr'  => [
                    'accept' => 'image/*',
                    'multiple' => 'multiple'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ServiceAttachment::class,
        ]);
    }
}
