<?php

namespace App\Interfaces;

abstract class NewsService
{
    public abstract function __construct($url, $apiKey);

    public abstract function search($term);

    public abstract function fetch($parameters);
}
