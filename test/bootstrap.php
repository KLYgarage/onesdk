<?php
include __DIR__ . '/../vendor/autoload.php';

function loadTestEnv()
{
    if (!empty(getenv('CLIENT_ID')) && !empty(getenv('CLIENT_SECRET'))) {
        return array(
            'CLIENT_ID' => getenv('CLIENT_ID'),
            'CLIENT_SECRET' => getenv('CLIENT_SECRET'),
            'ACCESS_TOKEN' => getenv('ACCESS_TOKEN')
        );
    }

    $envPath = realpath(__DIR__ . '/.env');

    if (file_exists($envPath)) {
        $env = array_reduce(
            array_filter(
                explode(
                    "\n",
                    file_get_contents($envPath)
                )
            ),
            function ($carry, $item) {
                list($key, $value) = explode('=', $item, 2);
                $carry[$key] = $value;
                return $carry;
            },
            array()
        );
    
        return $env;
    }

    return null;
}
