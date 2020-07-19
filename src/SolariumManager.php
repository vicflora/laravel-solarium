<?php

declare(strict_types=1);

namespace TSterker\Solarium;

use GrahamCampbell\Manager\AbstractManager;
use Illuminate\Contracts\Config\Repository;
use InvalidArgumentException;
use Solarium\Client;

class SolariumManager extends AbstractManager
{

    /** @var SolariumFactory */
    protected $factory;

    /**
     * Create a new solarium manager instance.
     *
     * @param \Illuminate\Contracts\Config\Repository $config
     * @param SolariumFactory    $factory
     *
     * @return void
     */
    public function __construct(Repository $config, SolariumFactory $factory)
    {
        parent::__construct($config);
        $this->factory = $factory;
    }

    /**
     * Get the configuration for a connection.
     *
     * @param string|null $name
     *
     * @throws \InvalidArgumentException
     *
     * @return mixed[]
     */
    public function getConnectionConfig(?string $name = null)
    {
        return parent::getConnectionConfig($name);
    }

    /**
     * Create the connection instance.
     *
     * @param mixed[] $config
     *
     * @return Client
     */
    protected function createConnection(array $config): Client
    {
        return $this->factory->make($config);
    }

    /**
     * Get the configuration name.
     *
     * @return string
     */
    protected function getConfigName()
    {
        return 'solarium';
    }
}
