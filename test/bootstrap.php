<?php declare(strict_types=1);

include __DIR__ . '/../vendor/autoload.php';

/**
 * Load env
 */
function loadTestEnv(): ?array
{
    if (! empty(getenv('CLIENT_ID')) && ! empty(getenv('CLIENT_SECRET'))) {
        return [
            'CLIENT_ID' => getenv('CLIENT_ID'),
            'CLIENT_SECRET' => getenv('CLIENT_SECRET'),
            'ACCESS_TOKEN' => getenv('ACCESS_TOKEN'),
        ];
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
                [$key, $value] = explode('=', $item, 2);
                $carry[$key] = $value;
                return $carry;
            },
            []
        );

        return $env;
    }

    return null;
}
