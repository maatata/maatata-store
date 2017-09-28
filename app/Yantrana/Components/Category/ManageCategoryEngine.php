<?php
/*
* ManageCategoryEngine.php - Main component file
*
* This file is part of the Category component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Category;

use App\Yantrana\Components\Category\Repositories\ManageCategoryRepository;

class ManageCategoryEngine
{
    /**
     * @var - Category Repository
     */
    protected $manageCategoryRepository;

    /**
     * Constructor.
     *
     * @param ManageCategoryRepository $manageCategoryRepository - Category Repository
     *-----------------------------------------------------------------------*/
    public function __construct(ManageCategoryRepository $manageCategoryRepository)
    {
        $this->manageCategoryRepository = $manageCategoryRepository;
    }

    /**
     * get all categores list.
     *
     * @param int $categoryID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getCategories($categoryID, $searchText = null)
    {
        return $this->manageCategoryRepository
                        ->fetchCategories(
                                $categoryID,
                                $searchText
                            );
    }

    /**
     * description.
     *
     * @param $categoryID
     * @param $input
     *
     * @return reaction code number
     *---------------------------------------------------------------- */
    public function updateStatus($categoryID, $input)
    {
        // fetch category record
        $repoResponse = $this->manageCategoryRepository->fetch($categoryID);

        // if check reaction of request
        if (empty($repoResponse)) {
            return __engineReaction(2);
        }

        $updateResponse = $this->manageCategoryRepository
                               ->updateStatus($repoResponse, $input);
        // response reaction from repository	 				   
        return __engineReaction(1, $updateResponse);
    }

    /**
     * process delete category.
     *
     * @param int $categoryID
     *
     * @return engine rection
     *---------------------------------------------------------------- */
    public function processDelete($categoryID, $input)
    {
        // fetch category record
        $category = $this->manageCategoryRepository->fetch($categoryID);

        // if check reaction of request
        if (empty($category)) {
            return __engineReaction(18);
        }

        $deleteResponse = $this->manageCategoryRepository
                               ->delete($category, $input);

        if ($deleteResponse == 1) {
            // response on request delete 				   
            return __engineReaction(1);
        }

        return __engineReaction(3);
    }

    /**
     * get all categories list.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getAll()
    {
        // fetch category record
        return $this->manageCategoryRepository->fetchWithoutCacheAll();
    }

    /**
     * get all categories list.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getAllCategories()
    {
        return __engineReaction(1, [
                'categories' => fancytreeSource($this->getAll()),
            ]);
    }

    /**
     * Process add caregory.
     *
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processAdd($inputData)
    {
        if (isset($inputData['parent_cat'])
            and !__isEmpty($inputData['parent_cat'])
            and !$inputData['parent_cat'] == 0) {
            $parentCategoryID = $inputData['parent_cat'];

            $parentCategory = $this->manageCategoryRepository
                                     ->findByID($parentCategoryID);

            // Check if parent category empty
            if (__isEmpty($parentCategory)) {
                return __engineReaction(18);
            }
        } else {
            $inputData['parent_cat'] = null;
        }

        // Check if category addded
        if ($this->manageCategoryRepository->store($inputData)) {
            return __engineReaction(1);
        }

        return __engineReaction(18);
    }

    /**
     * get detail of category.
     *
     * @param int $categoryID
     *---------------------------------------------------------------- */
    public function getSupportData($categoryID)
    {
        // fetch category record
        $repoResponse = $this->manageCategoryRepository->fetch($categoryID);

        if (__isEmpty($repoResponse)) {
            return __engineReaction(18);
        }

        // if check reaction of request
        return __engineReaction(1, $repoResponse);
    }

    /**
     * get detail of category.
     *
     * @param int $categoryID
     *---------------------------------------------------------------- */
    public function getDetails($categoryID)
    {
        // fetch category record
        $repoResponse = $this->manageCategoryRepository->fetch($categoryID);

        if (__isEmpty($repoResponse)) {
            return __engineReaction(18);
        }

        $repoResponse->active = ($repoResponse->status == 1) ? true : false;

        $repoResponse['categories'] = fancytreeSource($this->getAll());

        // if check reaction of request
        if ($repoResponse) {
            return __engineReaction(1, $repoResponse);
        }
    }

    /**
     * process update of category data.
     *
     * @param array $input
     * @param int   $catID
     *
     * @return engine response
     *---------------------------------------------------------------- */
    public function processUpdate($input, $catID)
    {
        // fetch category record
        $category = $this->manageCategoryRepository->fetch($catID);

        // if check reaction of request
        if (empty($category)) {
            return __engineReaction(18);
        }

        $status = 0;
        if ($input['active'] == true) {
            $status = 1;
        }

        $input['status'] = $status;
        // check in database input name is unique or not
        $uniqueCategory = $this->manageCategoryRepository
                                ->checkUniqueRecord($input, $category['id']);

        // check response	
        if (empty($uniqueCategory)) {
            return __engineReaction(3);
        }

         // updated record response
        $repoResponse = $this->manageCategoryRepository
                              ->update($input, $category);

           // if check reaction of request
        if ($repoResponse) {
            return __engineReaction(1, $repoResponse);
        }

        return __engineReaction(14);
    }

    /**
     * get categories products.
     *
     * @param int $categoryID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getCategoriesProducts($categoryID)
    {
        // fetch categories products
        return $this->manageCategoryRepository
                    ->fetchCategoriesProducts($categoryID);
    }

    /**
     * get current categories products.
     *
     * @param int $categoryID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getCurrentCategoryProducts($categoryID)
    {
        // fetch categories products
        return $this->manageCategoryRepository
                    ->fetchCurrentCategoryProducts($categoryID);
    }
}
