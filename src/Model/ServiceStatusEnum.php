<?php

namespace App\Model;

enum ServiceStatusEnum: string
{
    case PAID = 'Zapłacone';
    case NEW = 'Nowe';
    case IN_PROGRESS = 'W trakcie';
    case COMPLETED = 'Zrealizowane';
    case WAITING = 'Oczekujące';
}