<?php
namespace App\Twig\Components;

use Symfony\Component\Form\FormView;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Description of ServiceForm
 *
 * @author Tomasz
 */
#[AsTwigComponent]
class ServiceForm {
    public FormView $form;
    
}
