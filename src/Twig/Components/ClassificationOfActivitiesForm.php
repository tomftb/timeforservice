<?php
namespace App\Twig\Components;

use Symfony\Component\Form\FormView;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\Component\Form\FormInterface;
use App\Entity\ClassificationOfActivities;
use App\Form\ClassificationOfActivitiesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\LiveProp;


/**
 * Description of ClassificationOfActivitiesForm
 *
 * @author Tomasz
 */
#[AsLiveComponent]
class ClassificationOfActivitiesForm extends AbstractController{
    
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?ClassificationOfActivities $initialFormData = null;
    
    #[\Override]
    protected function instantiateForm(): FormInterface
    {
        $classificationOfActivities=$this->initialFormData ?? new ClassificationOfActivities();
        
        return $this->createForm(ClassificationOfActivitiesType::class,$classificationOfActivities ,[
            'action' => $classificationOfActivities->getId() ? parent::generateUrl('app_classificationofactivities_edit',['id'=>$classificationOfActivities->getId()]) : parent::generateUrl( 'app_classificationofactivities_new' ), 
        ]);
    }
}
