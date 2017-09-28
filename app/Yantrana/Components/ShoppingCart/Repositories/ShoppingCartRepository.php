<?php
/*
* ShoppingCartRepository.php - Repository file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart\Repositories;

use App\Yantrana\Core\BaseRepository;
use App\Yantrana\Components\ShoppingCart\Models\ShoppingCart as ShoppingCartModel;
use App\Yantrana\Components\ShoppingCart\Blueprints\ShoppingCartRepositoryBlueprint;

class ShoppingCartRepository extends BaseRepository
                          implements ShoppingCartRepositoryBlueprint
{
    /**
     * @var ShoppingCartModel - ShoppingCart Model
     */
    protected $shoppingCart;

    /**
     * Constructor.
     *
     * @param ShoppingCartModel $shoppingCart - ShoppingCart Model
     *-----------------------------------------------------------------------*/
    public function __construct(ShoppingCartModel $shoppingCart)
    {
        $this->shoppingCart = $shoppingCart;
    }
}
