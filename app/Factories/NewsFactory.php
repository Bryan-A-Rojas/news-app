<?php

namespace App\Factories;

use App\Interfaces\NewsFactoryInterface;
use App\Services\TheGuardianService;
use App\Interfaces\NewsService;

class NewsFactory implements NewsFactoryInterface
{
    public static function create($platform): NewsService {
        switch ($platform) {
            case 'the_guardian':
                return new TheGuardianService(
                    config('services.the_guardian.url'),
                    config('services.the_guardian.key')
                );
                break;
            default:
                throw new Exception('News Service not supported');
                break;
        }
    }
}
