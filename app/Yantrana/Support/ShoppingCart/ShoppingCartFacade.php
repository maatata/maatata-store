<?php

namespace App\Yantrana\Support\ShoppingCart;

/*
 * Facade for ShoppingCart
 *-------------------------------------------------------- */

use Illuminate\Support\Facades\Facade;

/**
 * ShoppingCart.
 *-------------------------------------------------------------------------- */
class ShoppingCartFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ShoppingCart';
    }
}
