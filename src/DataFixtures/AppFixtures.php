<?php

namespace App\DataFixtures;

use App\Entity\Permission;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        self::loadPermissions($manager);
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
                'code'=>'APP_ACCESS'
                ,'name'=>'Application access'
            ]
            ,[
                'code'=>'MAIN_PAGE_ACCESS'
                ,'name'=>'Application main page access'
            ]
            ,[
                'code'=>'SERVICES_ACCESS'
                ,'name'=>'Application services page access'
            ]
            ,[
                'code'=>'CLIENTS_ACCESS'
                ,'name'=>'Application clients page access'
            ]
            ,[
                'code'=>'CLIENTS_POINT_ACCESS'
                ,'name'=>'Application clients points page access'
            ]
            ,[
                'code'=>'INVOICES_ACCESS'
                ,'name'=>'Application invoices page access'
            ]
            ,[
                'code'=>'USERS_ACCESS'
                ,'name'=>'Application user page access'
            ]
            ,[
                'code'=>'EMPLOYEES_ACCESS'
                ,'name'=>'Application employees page access'
            ]
            ,[
                'code'=>'CLASSIFICATION_OF_ACTIVITIES_ACCESS'
                ,'name'=>'Application classification of activities page access'
            ]
        ];

        foreach ($permissionsData as $data) {
            $permission = new Permission();
            $permission->setCode($data['code']);
            $permission->setName($data['name']);
            $manager->persist($permission);
        }

        $manager->flush();
    }
}
