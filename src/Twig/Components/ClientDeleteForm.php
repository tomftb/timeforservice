<?php
namespace App\Twig\Components;

use Symfony\Component\Form\FormView;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\Component\Form\FormInterface;
use App\Entity\Client;
use App\Form\ClientDeleteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\LiveProp;


/**
 * Description of ServiceForm
 *
 * @author Tomasz
 */
#[AsLiveComponent]
class ClientDeleteForm extends AbstractController{
    
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?Client $initialFormData = null;
    
    #[\Override]
    protected function instantiateForm(): FormInterface
    {
        $client=$this->initialFormData;
        return parent::createForm(ClientDeleteType::class,$client ,[
            'action' => $this->generateUrl('app_client_delete',['id'=>$client->getId()])
        ]);
    }
}
