<?php

namespace App\Yantrana\Support\Breadcrumb;

/*
 * Facade for Breadcrumb
 *-------------------------------------------------------- */

use Illuminate\Support\Facades\Facade;

/**
 * Breadcrumb.
 *-------------------------------------------------------------------------- */
class BreadcrumbFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'breadcrumb';
    }
}
