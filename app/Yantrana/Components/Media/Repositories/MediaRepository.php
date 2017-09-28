<?php
/*
* MediaRepository.php - Repository file
*
* This file is part of the Media component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Media\Repositories;

use App\Yantrana\Core\BaseRepository;
use App\Yantrana\Components\Media\Models\Media as MediaModel;
use App\Yantrana\Components\Media\Blueprints\MediaRepositoryBlueprint;

class MediaRepository extends BaseRepository
                          implements MediaRepositoryBlueprint
{
    /**
     * @var MediaModel - Media Model
     */
    protected $media;

    /**
     * Constructor.
     *
     * @param MediaModel $media - Media Model
     *-----------------------------------------------------------------------*/
    public function __construct(MediaModel $media)
    {
        $this->media = $media;
    }
}
