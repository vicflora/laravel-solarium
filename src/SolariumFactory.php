<?php

declare(strict_types=1);

namespace TSterker\Solarium;

use InvalidArgumentException;
use Solarium\Client;
use Solarium\Core\Client\Adapter\Curl;
use Symfony\Component\EventDispatcher\EventDispatcher;

class SolariumFactory
{

    /**
     * @param mixed[] $config
     *
     * @return Client
     */
    public function make(array $config): Client
    {
        $adapter = new Curl;
        $eventDispatcher = new EventDispatcher;

        if (!isset($config['host'])) {
            throw new InvalidArgumentException('A host must be specified.');
        }

        if (!isset($config['port'])) {
            throw new InvalidArgumentException('A port must be specified.');
        }

        if (!isset($config['path'])) {
            throw new InvalidArgumentException('A path must be specified.');
        }

        if (method_exists($adapter, 'setTimeout') && isset($config['timeout'])) {
            $adapter->setTimeout($config['timeout']);
        }

        $config = [
            'endpoint' => [
                'default' => $config
            ],
        ];

        return new Client($adapter, $eventDispatcher, $config);
    }
}
