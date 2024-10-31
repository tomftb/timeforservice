<?php

namespace App\Form;

use App\Entity\ClientClassificationOfActivities;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\Length;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;

//use App\Repository\ClassificationOfActivitiesRepository;

class ClientClassificationOfActivitiesType extends AbstractType
{
    //private array $classificationOfActivities = [];
    public function __construct()//ClassificationOfActivitiesRepository $classificationOfActivitiesRepository
    {
        //$this->classificationOfActivities = $classificationOfActivitiesRepository->findAll();
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
         $builder->add('classificationOfActivities', CollectionType::class, [
            'entry_type' => ClassificationOfActivitiesType::class,
            'entry_options' => ['label' => false],
        ]);
         
        //dd($this->classificationOfActivities);
        //$builder->add('classificationOfActivities', CollectionType::class);
       //$builder->add('classificationOfActivities', CollectionType::class, [
       //     'entry_type' => ClassificationOfActivitiesType::class,
        //    'entry_options' => ['label' => false],
        //]);
        
        //foreach($this->classificationOfActivities as $key =>$value){
            //dd($value);
            //$builder->get('classificationOfActivities')->add('classification_'.$key, ClassificationOfActivitiesType::class,[
            //    'code'=>$value->getCode(),
            //    'name'=>$value->getName(),
            //]);
            //$builder
           // ->add('classificationOfActivities',TextType::class,[
            //    'required' => true,
            //    'constraints' => [new Length(['min' => 3])],
            //]);
            //$builder->get('clientClassificationOfActivities')->add('classification_'.$key);
        //}
       //dd($builder->get('classificationOfActivities'));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClientClassificationOfActivities::class,
        ]);
    }
}
