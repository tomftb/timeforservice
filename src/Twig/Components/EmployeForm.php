<?php
namespace App\Twig\Components;

use Symfony\Component\Form\FormView;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\Component\Form\FormInterface;
use App\Entity\Employe;
use App\Form\EmployeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\LiveProp;


/**
 * Description of ClassificationOfActivitiesForm
 *
 * @author Tomasz
 */
#[AsLiveComponent]
class EmployeForm extends AbstractController{
    
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?Employe $initialFormData = null;
    
    #[\Override]
    protected function instantiateForm(): FormInterface
    {
        $employe=$this->initialFormData ?? new Employe();
        
        return $this->createForm(EmployeType::class,$employe ,[
            'action' => $employe->getId() ? parent::generateUrl('app_employe_edit',['id'=>$employe->getId()]) : parent::generateUrl( 'app_employe_new' ), 
        ]);
    }
}
