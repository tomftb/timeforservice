<?php
namespace App\Twig\Components;

use Symfony\Component\Form\FormView;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\Component\Form\FormInterface;
use App\Entity\Service;
use App\Form\ServiceNotifyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\LiveProp;


/**
 * Description of ServiceForm
 *
 * @author Tomasz
 */
#[AsLiveComponent]
class ServiceNotifyForm extends AbstractController{
    
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?Service $initialFormData = null;
    
    #[\Override]
    protected function instantiateForm(): FormInterface
    {
        $service=$this->initialFormData;
        return parent::createForm(ServiceNotifyType::class,$service ,[
            'action' => $this->generateUrl('app_service_notify',['id'=>$service->getId()])
        ]);
    }
}
