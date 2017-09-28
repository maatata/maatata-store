<?php
/*
* UploadManagerRepository.php - Repository file
*
* This file is part of the UploadManager component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\UploadManager\Repositories;

use App\Yantrana\Core\BaseRepository;
use App\Yantrana\Components\UploadManager\Models\UploadManager as UploadManagerModel;
use App\Yantrana\Components\UploadManager\Blueprints\UploadManagerRepositoryBlueprint;

class UploadManagerRepository extends BaseRepository
                          implements UploadManagerRepositoryBlueprint
{
    /**
     * @var UploadManagerModel - UploadManager Model
     */
    protected $uploadManagerModel;

    /**
     * Constructor.
     *
     * @param UploadManagerModel $uploadManagerModel - UploadManager Model
     *-----------------------------------------------------------------------*/
    public function __construct(UploadManagerModel $uploadManagerModel)
    {
        $this->uploadManagerModel = $uploadManagerModel;
    }
}
