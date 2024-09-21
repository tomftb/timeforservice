<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use App\Repository\ServiceRepository;

#[AsTwigComponent]
final class SearchSite
{
    public function __construct(private ServiceRepository $serviceRepository)
    {
        
    }
    /**
     * @return Service[]
     */
    public function services():array
    {
        return $this->serviceRepository->findBySearchWithClientPoint(null,[],10);
    }

}