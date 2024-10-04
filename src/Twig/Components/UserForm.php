<?php
namespace App\Twig\Components;

use Symfony\Component\Form\FormView;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\Component\Form\FormInterface;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\LiveProp;


/**
 * Description of UserForm
 *
 * @author Tomasz
 */
#[AsLiveComponent]
class UserForm extends AbstractController{
    
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?User $initialFormData = null;
    
    #[\Override]
    protected function instantiateForm(): FormInterface
    {
        $user=$this->initialFormData ?? new User();   
        return $this->createForm(UserType::class,$user ,[
            'action' => $user->getId() ? $this->generateUrl('app_user_edit',['id'=>$user->getId()]) : $this->generateUrl( 'app_user_new' ), 
        ]);
    }
}
