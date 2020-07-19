<?php

declare(strict_types=1);

namespace TSterker\Solarium\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin \Solarium\Client
 */
class Solarium extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'solarium';
    }
}
