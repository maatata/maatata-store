<?php

namespace App\Yantrana\Components;

use App\Yantrana\Core\BaseController;
use JavaScript;
use App\Yantrana\Components\Pages\Models\Page;
use Breadcrumb;
use App\Yantrana\Components\Store\ManageStoreEngine;

class __Igniter extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Welcome Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders the "marketing page" for the application and
    | is configured to only allow guests. Like most of the other sample
    | controllers, you are free to modify or remove it as you desire.
    |
    */

    /**
     * @var ManageStoreEngine - ManageStore Engine
     */
    protected $manageStoreEngine;

    /**
     * @var array - Protected Views
     */
    protected $protectedViews = [
        'user.change-email',
    ];

    /**
     * Constructor.
     *
     * @param ManageStoreEngine $manageStoreEngine - ManageStore Engine
     *-----------------------------------------------------------------------*/
    public function __construct(ManageStoreEngine $manageStoreEngine)
    {
        $this->manageStoreEngine = $manageStoreEngine;
    }

    /**
     * Get component view.
     *---------------------------------------------------------------- */
    public function loadView($viewName)
    {
        $data = [
            'store_settings' => $this->manageStoreEngine->prepareStoreSettings(),
        ];

        JavaScript::put($this->prepareForBrowser());

        return __loadView($viewName, $data);
    }

    /**
     * Get public application master view template.
     *---------------------------------------------------------------- */
    public function index()
    {
        $breadCrumb['data']['breadCrumb'] = Breadcrumb::generate('home');

        return $this->loadPublicView('home', $breadCrumb['data']);
    }

    /**
     * Get manage application master view template.
     *---------------------------------------------------------------- */
    public function manageIndex()
    {
        return $this->loadView('manage-master');
    }

    /**
     * Get not found error view template.
     *---------------------------------------------------------------- */
    public function errorNotFound()
    {
        return $this->loadPublicView('errors.not-found');
    }

    /**
     * Get not found error view template.
     *---------------------------------------------------------------- */
    public function getTemplate($viewName)
    {
        if (in_array($viewName, $this->protectedViews)) {
            if (isLoggedIn() === false) {
                return __apiResponse([
                        'message' => __('Please login to complete request.'),
                        'auth_info' => getUserAuthInfo(9),
                    ], 9);
            }
        }

        if (array_key_exists($viewName, $this->protectedViews)) {
            if ($this->protectedViews[$viewName] == 1) {
                if (isAdmin() === false) {
                    return __apiResponse([
                        'message' => __('Unauthorized.'),
                        'auth_info' => getUserAuthInfo(11),
                    ], 11);
                }
            }
        }

        return view($viewName);
    }
}
