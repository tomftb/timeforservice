<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Model\TypeOfServiceEnum;
use App\Model\YesOrNoEnum;
use App\Repository\ClientPointRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class ServiceType extends AbstractType
{
    /**
     * @var ClientPointRepository
     */
    protected $clientPointRepository;
    /*
     * 
     */
    protected ?Service $service;
    
    public function __construct(ClientPointRepository $clientPointRepository)
    {
        $this->clientPointRepository = $clientPointRepository;
    }
    
    protected function getClientPointSet(array $options=[]):array{
        $clientPointSet = [];
        if($options['data']->getClientPoint() !== null){           
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
        
        /** @var Service|null $article */
        $this->service = $options['data'] ?? null;
        
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
            ->add('clientPoint',ChoiceType::class ,
            [
                'choices'  =>$this->getClientPointSet($options),
                'placeholder' => 'Choose a client point',
                'required' => true,
                'mapped'=>false
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
            ->add('files', FileType::class, [
                'label' => 'Set file/files (IMAGE/PDF)',

                'mapped' => false,
                'multiple' => true,
                'required' => false,
                'attr'  => [
                    'accept' => 'image/*',
                    'multiple' => 'multiple'
                ],
            ])
            ;
                
                
        $builder->get('clientPoint')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) {
                $form = $event->getForm();
                self::setupClientPoint(
                    $form->getParent(),
                    $form->getData()
                );
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
    
    private function setupClientPoint(FormInterface $form, ?string $clientPointId){
        foreach($this->clientPointRepository->findAllActive() as $clientPoint){
            if(intval($clientPointId,10) === $clientPoint->getId()){
                $this->service->setClientPoint($clientPoint);
            }
        }

    }
    
}
