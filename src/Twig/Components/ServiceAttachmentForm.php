<?php
namespace App\Twig\Components;

use Symfony\Component\Form\FormView;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\Component\Form\FormInterface;
use App\Entity\ServiceAttachment;
use App\Form\ServiceAttachmentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\LiveProp;


/**
 * Description of ServiceForm
 *
 * @author Tomasz
 */
#[AsLiveComponent]
class ServiceAttachmentForm extends AbstractController{
    
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?ServiceAttachment $initialFormData = null;
    
    #[LiveProp]
    public ?int $serviceId = null;
    
    #[\Override]
    protected function instantiateForm(): FormInterface
    {
        //dd($this->initialFormData);
        $serviceAttachment=$this->initialFormData;
        $serviceId=$this->serviceId;
        
        return $this->createForm(ServiceAttachmentType::class,$serviceAttachment ,[
            'action' => $this->generateUrl( 'app_service_attachment',['id'=>$serviceId] ), 
        ]);
    }
}
