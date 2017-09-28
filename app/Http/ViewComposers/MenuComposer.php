<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Yantrana\Components\Home\MenuEngine;

class MenuComposer
{
    /**
     * The menuEngine.
     *
     * @var MenuEngine
     */
    protected $menuEngine;

    /**
     * Create a new menu composer.
     *
     * @param  MenuEngine  $menuEngine
     * @return void
     */
    public function __construct(MenuEngine $menuEngine)
    {
        // Dependencies automatically resolved by service container...
        $this->menuEngine = $menuEngine;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    { 	

		$data['sideBarCategoriesMenuData']   	=  $this->menuEngine->getSideBarCategoriesMenu();
		$data['navMenuData']    				=  $this->menuEngine->nevigationTree();
		$data['sibeBarBrandMenuData']   		=  $this->menuEngine->getSideBarBrandsData();

//		__dd($data['sibeBarBrandMenuData'] );

        $view->with('menuData', $data);
    }
}