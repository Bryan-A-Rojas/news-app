<?php

namespace App\Interfaces;

interface NewsFactoryInterface
{
    public static function create($platform): NewsService;
}
