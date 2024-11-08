<?php

namespace App\Model;

enum ClientStatusEnum: string
{
    case NEW = 'Nowy';
    case TO_BE_SETTLED='Do rozliczenia';
    case SETTLED='Rozliczony';
}