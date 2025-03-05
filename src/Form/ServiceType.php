<?php

namespace App\Form;

use App\Entity\Service;
use App\Entity\ClientPoint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Model\TypeOfServiceEnum;
use App\Model\YesOrNoEnum;
use App\Repository\ClientPointRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ServiceType extends AbstractType
{
    /**
     * @var ClientPointRepository
     */
    protected $clientPointRepository;
    
    public function __construct(ClientPointRepository $clientPointRepository)
    {
        $this->clientPointRepository = $clientPointRepository;
        
    }
    
    protected function getClientPointSet(array $options=[]):array{
        //dd($this->clientPointRepository->findAllActive());
        $clientPointSet = [];
        if($options['data']->getClientPoint() !== null){
            
            //dd($options['data']);
            //dd($options['data']->getClientPoint()->getId());
            
            foreach($this->clientPointRepository->getSelected($options['data']->getClientPoint()->getId()) as $selectedClientPoint){
                $clientPointSet[$selectedClientPoint->getName()." (".$selectedClientPoint->getStreet().", ".$selectedClientPoint->getTown().")"] = $selectedClientPoint->getId();
            }
        }        
        foreach($this->clientPointRepository->findAllActive() as $clientPoint){
            $clientPointSet[$clientPoint->getName()." (".$clientPoint->getStreet().", ".$clientPoint->getTown().")"] = $clientPoint->getId();
        }
        return $clientPointSet;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class,[
                'label'=>'Description',
                "attr" => array("rows" => 10)
            ])
            ->add('typeOfService',EnumType::class,[
                'class'=> TypeOfServiceEnum::class,
                'required' => true,
                'data_class'=>null,
            ])
            ->add('startedAt', DateTimeType::class, [
                'date_label' => 'Starts On',
            ]) 
            ->add('endedAt', DateTimeType::class, [
                'date_label' => 'Starts On',
            ])
            ->add('time',null,[
                'label'=>'Time (in minutes)'
            ]) 
            ->add('route',null,[
                'label'=>'Route (in kilometers)'
            ])
            ->add('materialCosts',null,[
                'label'=>'Material costs (gross)'
            ]) 
            ->add('clientPoint', null, [
                'choice_label' =>  function ($clientPoint) {
                        return $clientPoint->getName() . ' (' .$clientPoint->getStreet().",". $clientPoint->getTown().")";
                },
                'placeholder' => 'Choose a client point',
                'autocomplete'=> true
            ])
            ->add('employe', null, [
                'choice_label' => function ($employe) {
                        return "[".$employe->getId()."] ".$employe->getFirstName() ." ".$employe->getLastName();
                    },
                'placeholder' => 'Choose a employe',
                'autocomplete'=> true
            ])
            ->add('classificationOfActivities', null, [
                'label'=>'Type of service',
                'choice_label' =>  function ($classificationOfActivities) {
                        return "[".$classificationOfActivities->getCode() . '] ' .$classificationOfActivities->getName();
                    },
                'placeholder' => 'Choose type of service',
                'autocomplete'=> true
            ])
            ->add('notified',EnumType::class,[
                'class'=> YesOrNoEnum::class,
                'label'=>'Notified',
                'required' => true
            ])
            ->add('paided',EnumType::class,[
                'class'=> YesOrNoEnum::class,
                'label'=>'Paided',
                'required' => true
            ])
                            /*
            ->add('clientPointNew',EntityType::class ,
            [
                'class' => ClientPoint::class,
                'query_builder'=> function(ClientPointRepository $clientPointRepository) use ($options){
                    return $clientPointRepository->findAllActive();
                },
                'required' => true,
                'mapped'=>false
            ])*/
            ->add('clientPointNew',ChoiceType::class ,
            [
                'choices'  =>$this->getClientPointSet($options),
                'required' => true,
                'mapped'=>false
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
