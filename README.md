# Laravel Solarium

A [Solarium](https://github.com/solariumphp/solarium) connection manager for [Laravel](https://laravel.com/).
It utilizes the [Laravel Manager](https://github.com/GrahamCampbell/Laravel-DigitalOcean) package by [Graham Campbell](https://github.com/GrahamCampbell).

# Installation

```sh
$ composer require tsterker/solarium
```

Once installed, if you are not using automatic package discovery, then you need to register the `TSterker\Solarium\SolariumServiceProvider` service provider in your config/app.php.

You can also optionally alias our facade:

```php
        'Solarium' => TSterker\Solarium\Facades\GitHub::class,
```

# Configuration

Laravel Solarium requires connection configuration. The default configuration of this package uses the following environment variables and defaults:

```sh
SOLR_CONNECTION=main  # Publish config/solarium.php to configure multiple connections
SOLR_TIMEOUT=60
SOLR_HOST=localhost
SOLR_PORT=8983
SOLR_PATH=/
```


For more control you should publish the solarium configuration:

```sh
$ php artisan vendor:publish --provider TSterker\\Solarium\\SolariumServiceProvider
```

This will create a `config/solarium.php` file in your app that you can modify to set your configuration. Also, make sure you check for changes to the original config file in this package between releases.

There are two main config options:

**Default Connection Name**

This option (`'default'`) is where you may specify which of the connections below you wish to use as your default connection for all work. Of course, you may use many connections at once using the manager class. The default value for this setting is 'main'.

**Solarium Connections**

This option (`'connections'`) is where each of the connections are setup for your application. The relevant fields for a connection are `host`, `port`, `path`. Optionally you can provide `timeout` and `core` (or `collection` when using solr cloud). Check the [Solarium documentation](https://solarium.readthedocs.io/en/stable/getting-started/#basic-usage) for details.
# Usage

**SolariumManager**

This is the class of most interest. It is bound to the ioc container as `'solarium'` and can be accessed using the `Facades\Solarium` facade. This class implements the `ManagerInterface` by extending `AbstractManager` from the [Laravel Manager](https://github.com/GrahamCampbell/Laravel-DigitalOcean) package. Note that the connection class returned will always be an instance of [`Solarium\Client`](https://github.com/solariumphp/solarium/blob/master/src/Client.php).

**Facades\Solarium**

This facade will dynamically pass static method calls to the `'solarium'` object in the ioc container which by default is the `SolariumManager` class.


## Example: Using Facade

```php
use TSterker\Solarium\Facades\Solarium;
// you can alias this in config/app.php if you like

Solarium::getEndpoint()->setCollection($this->collection);
// or configure a default core/collection in the config/solarium.php

$select = Solarium::createSelect();

$docs = Solarium::select($select)->getDocuments();
```


## Example: Using Dependency Injection

If you prefer to use dependency injection over facades, then you can easily inject the manager like so:

```php
use TSterker\Solarium\SolariumManager;

class Searcher
{
    protected $solarium;

    protected $collection = 'default-collection';

    public function __construct(SolariumManager $solarium)
    {
        $solarium->getEndpoint()->setCollection($this->collection);
        $this->solarium = $solarium;
    }

    /* @return \Solarium\QueryType\Select\Result\Document[] */
    public function all(): array
    {
        $select = $this->solarium->createSelect();

        return $this->solarium->select($select)->getDocuments();
    }
}

app(Searcher::class)->all();
```