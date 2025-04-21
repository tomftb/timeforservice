<?php
namespace App\Service\User;

use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\UserPermission;
use App\Entity\User;
use App\Repository\PermissionRepository;
use App\Repository\UserPermissionRepository;
use Doctrine\DBAL\Connection;
/**
 * Description of Permission
 *
 * @author Tomasz Borczynski
 */
class Permission {

    private RequestStack $requestStack;
    private User $user;
    private EntityManagerInterface $entityManager;
    private UserPermissionRepository $userPermissionRepository;
    private $connection;
    
    public function __construct(RequestStack $requestStack,EntityManagerInterface $entityManager, PermissionRepository $permissionRepository, UserPermissionRepository $userPermissionRepository)
    {
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
        $this->permissionRepository =$permissionRepository;
        $this->userPermissionRepository = $userPermissionRepository;
        $this->connection = $this->entityManager->getConnection();
    }

    public function setPermission(User $user):void
    {
        $this->user = $user;
        $request = $this->requestStack->getCurrentRequest();
        
        if(!$request){
            return;
        }
        if(!$request->isMethod('POST')){
            return;
        }
        foreach ($request->request->all() as $key => $value) {
           
            self::add($value);
            
        }
    }
    private function add(array $value=[]):void
    {
        /*
         * DELETE OLD PERMISSIONS
         */
        self::delete();
        try {
            $permissions = $this->permissionRepository->findAll();
            $permissionsIds = [];
            $permissionsCodes = [];
            
            /*
             * GET FROM POST
             */
            foreach($value as $k => $v){

                if(!preg_match('/^permission\_(\d)+$/', $k)){
                    continue;
                }
                $permissionsIds[]=intval($v,10);
            }
            if(empty($permissions) || !is_array($permissions)){
                return;
            }

            $this->connection->beginTransaction();
           
            /*
             * SET NEW
             */
            foreach($permissions as $permissionRepository){
                if(!in_array($permissionRepository->getId(),$permissionsIds)){
                    continue;
                }
                $permissionsCodes[]=$permissionRepository->getCode();
                $userPermission = new UserPermission();
                $userPermission->setUser($this->user);
                $userPermission->setPermission($permissionRepository);

                $this->entityManager->persist($userPermission);
               
            }
            // Actually execute the queries (INSERT, etc.)
            $this->entityManager->flush();

            $this->connection->commit();
        }
        catch (\Exception $e) {
            //$this->entityManager->rollback();
            $this->connection->rollBack();
            throw $e;
        }
        /*
         * SET ROLES
         */
        $this->user->setRoles($permissionsCodes);
        $this->entityManager->persist($this->user);
        $this->entityManager->flush();
    }
    private function delete(){
        
        try {  
            $this->connection->beginTransaction(); // Use DBAL connection directly

            foreach($this->user->getUserPermissions() as $userPermission){
                $this->entityManager->remove($userPermission);

            }
            $this->entityManager->flush();
            $this->connection->commit();            
        }
        catch (\Exception $e) {
            //$this->entityManager->rollback();
            $this->connection->rollBack();
            throw $e;
        }
       
    }
}
