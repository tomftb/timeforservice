<?php
namespace App\Twig\Components;

use Symfony\Component\Form\FormView;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\Component\Form\FormInterface;
use App\Entity\Client;
use App\Form\ClientType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\LiveProp;


/**
 * Description of ClientForm
 *
 * @author Tomasz
 */
#[AsLiveComponent]
class ClientForm extends AbstractController{
    
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?Client $initialFormData = null;
    
    #[\Override]
    protected function instantiateForm(): FormInterface
    {
        //dd($this->initialFormData);
        $client=$this->initialFormData ?? new Client();
        
        return $this->createForm(ClientType::class,$client ,[
            'action' => $client->getId() ? $this->generateUrl('app_client_edit',['id'=>$client->getId()]) : $this->generateUrl( 'app_client_new' ), 
        ]);
    }
}
