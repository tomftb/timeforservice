<?php

namespace App\DataFixtures;

use App\Entity\Permission;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Model\YesOrNoEnum;

class AppFixtures extends Fixture
{
    private $passwordHasher;
    private $permissionsList=[];

    public function __construct( UserPasswordHasherInterface $passwordHasher) {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        self::loadPermissions($manager);
        self::loadUsers($manager);
    }
    private function loadPermissions(ObjectManager $manager){

        $permissionsData=[
            [
                'code'=>'ROLE_ADMIN'
                ,'name'=>'Administrator access'
            ]
            ,[
                'code'=>'ROLE_USER'
                ,'name'=>'User access'
            ]
            ,[
                'code'=>'ROLE_APP_ACCESS'
                ,'name'=>'Application access'
            ]
            ,[
                'code'=>'ROLE_MAIN_PAGE_ACCESS'
                ,'name'=>'Application main page access'
            ]
            ,[
                'code'=>'ROLE_SERVICES_ACCESS'
                ,'name'=>'Application services page access'
            ]
            ,[
                'code'=>'ROLE_CLIENTS_ACCESS'
                ,'name'=>'Application clients page access'
            ]
            ,[
                'code'=>'ROLE_CLIENTS_POINT_ACCESS'
                ,'name'=>'Application clients points page access'
            ]
            ,[
                'code'=>'ROLE_INVOICES_ACCESS'
                ,'name'=>'Application invoices page access'
            ]
            ,[
                'code'=>'ROLE_USERS_ACCESS'
                ,'name'=>'Application user page access'
            ]
            ,[
                'code'=>'ROLE_EMPLOYEES_ACCESS'
                ,'name'=>'Application employees page access'
            ]
            ,[
                'code'=>'ROLE_CLASSIFICATION_OF_ACTIVITIES_ACCESS'
                ,'name'=>'Application classification of activities page access'
            ]
        ];

        foreach ($permissionsData as $data) {
            $permission = new Permission();
            $permission->setCode($data['code']);
            $permission->setName($data['name']);
            $manager->persist($permission);
            $this->permissionsList[]=$data['code'];
        }

        $manager->flush();
    }
    private function loadUsers(ObjectManager $manager){

        $usersData=[
            [
                'email'=>'tborczynski87@gmail.com'
                ,'password'=>'Haslo1234'
                ,'first_name'=>'Tomasz'
                ,'last_name'=>'Borczyński'
                ,'is_verified'=>1
                ,'active'=>YesOrNoEnum::YES
                ,'roles'=>$this->permissionsList
            ]
        ];

        foreach ($usersData as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $data['password']
            );
            $user->setPassword($hashedPassword);
            $user->setFirstName($data['first_name']);
            $user->setLastName($data['last_name']);
            $user->setVerified($data['is_verified']);
            $user->setActive($data['active']);
            $user->setRoles($data['roles']);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
