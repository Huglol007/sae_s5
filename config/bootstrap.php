<?php

use Symfony\Component\Dotenv\Dotenv;

// Crée une instance de Dotenv
$dotenv = new Dotenv();

// Charger le fichier .env
$dotenv->load(__DIR__.'/.env');

