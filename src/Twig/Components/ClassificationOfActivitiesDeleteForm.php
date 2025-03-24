<?php
namespace App\Twig\Components;

use Symfony\Component\Form\FormView;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\Component\Form\FormInterface;
use App\Entity\ClassificationOfActivities;
use App\Form\ClassificationOfActivitiesDeleteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\LiveProp;


/**
 * Description of ServiceForm
 *
 * @author Tomasz
 */
#[AsLiveComponent]
class ClassificationOfActivitiesDeleteForm extends AbstractController{
    
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?ClassificationOfActivities $initialFormData = null;
    
    #[\Override]
    protected function instantiateForm(): FormInterface
    {
        $classificationOfActivities=$this->initialFormData;
        return parent::createForm(ClassificationOfActivitiesDeleteType::class,$classificationOfActivities ,[
            'action' => $this->generateUrl('app_classificationofactivities_delete',['id'=>$classificationOfActivities->getId()])
        ]);
    }
}
