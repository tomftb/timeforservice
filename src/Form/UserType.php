<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use App\Repository\PermissionRepository;
use App\Repository\UserPermissionRepository;

class UserType extends AbstractType
{
    private PermissionRepository $permissionRepository;
    private $userPermissions;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       // dd($options['action']);
        $this->userPermissions = $options['data']->getUserPermissions();

        $builder
           ->add('email', EmailType::class, [
                'label' => 'Adres email*',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please provide your email address.',
                    ]),
                    new Email([
                        'message' => 'Please provide proper email address.',
                        'mode' => 'html5',
                    ]),
                    new Length([
                        'max' => 180,
                        'maxMessage' => 'Email address cannot be longer than {{ limit }} characters.',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'twój@email.com',
                    'maxlength' => 180,
                ],
            ])
            ->add('firstName')
            ->add('lastName')
        ;
        /*
         * CHECK IS NEW OR EDIT
         * PASSWORD ONLY FOR NEW
         */
        if($options['action']==='/user/new'){
            $builder->add('password', PasswordType::class, [
                'label' => 'Password*',
                'always_empty'=>false,
                'toggle' => true,
                'empty_data' => $options['data']->getPassword(),
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please provide your uniqe password.',
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Password cannot be longer than {{ limit }} characters.',
                    ]),
                ],
                'attr' => [
                    'placeholder' => '',
                    'maxlength' => 255,
                ],
            ]);
        }
        foreach ($this->permissionRepository->findAll() as $perrmission){
            $builder->add('permission_'.$perrmission->getId(), CheckboxType::class, [
                'label' => $perrmission->getName(),
                'required' => false,
                'mapped'=>false,
                'value'=>$perrmission->getId()
                ,'attr' => [
                    'checked'=>self::checkUserPermission($perrmission->getId())
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    private function checkUserPermission(int $permissionId=0):bool
    {

        foreach($this->userPermissions as $userPermission){
            //dd($userPermission->getPermission()->getId());
            if($userPermission->getPermission()->getId() === $permissionId){
                return true;
            }
         }
         return false;
    }

}
