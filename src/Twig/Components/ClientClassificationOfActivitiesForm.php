<?php
namespace App\Twig\Components;

use Symfony\Component\Form\FormView;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\Component\Form\FormInterface;
use App\Entity\Client;
use App\Entity\ClientClassificationOfActivities;
use App\Form\ClientCooperationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\LiveProp;


/**
 * Description of ClientForm
 *
 * @author Tomasz
 */
#[AsLiveComponent]
class ClientClassificationOfActivitiesForm extends AbstractController{
    
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?ClientClassificationOfActivities $initialCooperationFormData = null;
    public ?Client $initialClientFormData = null;
    
    #[\Override]
    protected function instantiateForm(): FormInterface
    {
        $clientClassificationOfActivities=$this->initialFormData ?? new ClientClassificationOfActivities();
        $client=$this->initialClientFormData ?? new Client();
        
        return $this->createForm(ClientClassificationOfActivitiesType::class,$clientClassificationOfActivities ,[
            'action' => $this->generateUrl('app_client_cooperation',['id'=>$client->getId()]), 
        ]);
    }
}
