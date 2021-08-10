<?php

/* 
 * This file is the 'preloader' that is responsible for setting everything up. 
 * Think of this as an initialization script.
 * This should be included for the web interface AND any scripts/processes that run separately, thus
 * it should only contain logic that any processes/services will need.
 */

require_once(__DIR__ . '/vendor/autoload.php'); # this autoloads all vendor packages


# Set up dotenv so environment variables loaded from .env file.
$dotenv = new \Symfony\Component\Dotenv\Dotenv();
$dotenv->overload('/.env');


# Now load the ENVIRONMENT variable from the .env file.
$environment = $_ENV["ENVIRONMENT"];
$dbName = $_ENV["POSTGRES_DB"];
$dbUser = $_ENV["POSTGRES_USER"];
$dbPassword = $_ENV["POSTGRES_PASSWORD"];

$requiredEnvVars = [
    'ENVIRONMENT',
    'POSTGRES_HOST',
    'POSTGRES_DB',
    'POSTGRES_USER',
    'POSTGRES_PASSWORD',
];

foreach ($requiredEnvVars as $requiredEnvVar)
{
    if ($_ENV[$requiredEnvVar] === FALSE)
    {
        die("Incorrect configuration: {$requiredEnvVar} not set. Please check your .env file.");
    }
}

define("ENVIRONMENT", strtolower($_ENV['ENVIRONMENT']));
define("DB_HOST", $_ENV['POSTGRES_HOST']);
define("DB_NAME", $_ENV['POSTGRES_DB']);
define("DB_USER", $_ENV['POSTGRES_USER']);
define("DB_PASSWORD", $_ENV['POSTGRES_PASSWORD']);

if (in_array(ENVIRONMENT, ["dev", "development", "staging", "release"]))
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
else
{
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
}


// Define which folders contain all of our php classes.
$classDirs = array(
    __DIR__ . '/controllers',
    __DIR__ . '/exceptions',
    __DIR__ . '/libs',
    __DIR__ . '/models',
    __DIR__ . '/models/orm',
    __DIR__ . '/views',
    __DIR__ . '/views/bootstrap',
    __DIR__ . '/views/pages',
    __DIR__ . '/views/partials',
);

$autoloader = new iRAP\Autoloader\Autoloader($classDirs);






# put your custom init stuff here....
