<?php

namespace App\Service\Service;
use App\Model\YesOrNoEnum;
/**
 * Description of Filter
 *
 * @author Tomasz Borczynski
 */
class Filter {
    public function getAll(){
        return [
            'notified'=>[
                'id'=>'notified'
                ,'name'=>'Notified'
                ,'value'=>YesOrNoEnum::YES
            ]
            ,'paided'=>[
                'id'=>'paided'
                ,'name'=>'Paided'
                ,'value'=>YesOrNoEnum::YES
            ]
        ];   
    }
}
