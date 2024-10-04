<?php
namespace App\Twig\Components;

use Symfony\Component\Form\FormView;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\Component\Form\FormInterface;
use App\Entity\ClientPoint;
use App\Form\ClientPointType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\LiveProp;


/**
 * Description of ClientPointForm
 *
 * @author Tomasz
 */
#[AsLiveComponent]
class ClientPointForm extends AbstractController{
    
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?ClientPoint $initialFormData = null;
    
    #[\Override]
    protected function instantiateForm(): FormInterface
    {
        //dd($this->initialFormData);
        $clientPoint=$this->initialFormData ?? new ClientPoint();
        
        return $this->createForm(ClientPointType::class,$clientPoint ,[
            'action' => $clientPoint->getId() ? $this->generateUrl('app_clientpoint_edit',['id'=>$clientPoint->getId()]) : $this->generateUrl( 'app_clientpoint_new' ), 
        ]);
    }
}
