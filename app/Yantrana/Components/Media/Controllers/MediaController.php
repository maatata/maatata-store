<?php
/*
* MediaController.php - Controller file
*
* This file is part of the Media component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Media\Controllers;

use App\Yantrana\Support\CommonPostRequest as Request;
use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\Media\MediaEngine;

class MediaController extends BaseController
{
    /**
     * @var MediaEngine - Media Engine
     */
    protected $mediaEngine;

    /**
     * Constructor.
     *
     * @param MediaEngine $mediaEngine - Media Engine
     *-----------------------------------------------------------------------*/
    public function __construct(MediaEngine $mediaEngine)
    {
        $this->mediaEngine = $mediaEngine;
    }

    /**
     * Upload image files by user.
     *
     * @param object Request $request
     * 
     * @return json object
     *---------------------------------------------------------------- */
    public function uploadImage(Request $request)
    {
        $processReaction = $this->mediaEngine
                                ->processUploadedImage($request->file('file'));

        return __processResponse($processReaction, [
                2 => __('File not uploaded.'),
                3 => __('File has an invalid extension, it should be png, jpeg, jpg.'),
            ]);
    }

    /**
     * Handle uploaded images files request.
     * 
     * @return json object
     *---------------------------------------------------------------- */
    public function imagesFiles()
    {
        $processReaction = $this->mediaEngine->prepareUploadedImagesFiles();

        return __processResponse($processReaction, [
            ], $processReaction['data']);
    }

    /**
     * Handle user temp media delete request.
     *
     * @param string $fileName
     * 
     * @return json object
     *---------------------------------------------------------------- */
    public function delete($fileName, Request $request)
    {
        $processReaction = $this->mediaEngine
                                ->processDeleteTempMedia($fileName);

        return __processResponse($processReaction, [
                1 => __('File deleted.'),
                2 => __('File not deleted.'),
                3 => __('File does not exist.'),
            ]);
    }

    /**
     * Handle delete multiple user temp media.
     *
     * @param object Request $request
     * 
     * @return json object
     *---------------------------------------------------------------- */
    public function multipleDelete(Request $request)
    {
        $processReaction = $this->mediaEngine
                                ->processDeleteMultipleTempMedia(
                                    $request->input('files')
                                );

        return __processResponse($processReaction, [
                1 => __('Files deleted.'),
                2 => __('Files not deleted.'),
            ]);
    }
}
