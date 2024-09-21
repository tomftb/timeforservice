<?php

namespace App\Twig\Components;

use App\Repository\ServiceRepository;
/*
 * OLD VERSION
 */
//use Symfony\UX\TwigComponent\Attribute\AsLiveComponent;
//use Symfony\UX\LiveComponent\Attribute\LiveProp;
//use Symfony\UX\LiveComponent\DefaultActionTrait;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class SearchSite
{
    use DefaultActionTrait;
    
    #[LiveProp(writable: true)]
    public string $query ='';
    
    public function __construct(private ServiceRepository $serviceRepository)
    {
       
    }
    /**
     * @return Service[]
     */
    public function services():array
    {
        if(!$this->query){
            return [];
        }
        return $this->serviceRepository->findBySearchWithClientPoint($this->query,[],10);
    }

}