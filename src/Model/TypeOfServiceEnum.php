<?php

namespace App\Model;

enum TypeOfServiceEnum: string
{
    case Service = 'Usługa';
    case Dysfunction = 'Dysfunkcja';
    case Malfunction = 'Awaria';
}