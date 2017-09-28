<?php
/*
* UploadManagerEngine.php - Main component file
*
* This file is part of the UploadManager component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\UploadManager;

use File;
use App\Yantrana\Components\UploadManager\Repositories\UploadManagerRepository;
use App\Yantrana\Components\UploadManager\Blueprints\UploadManagerEngineBlueprint;

class UploadManagerEngine implements UploadManagerEngineBlueprint
{
    /**
     * @var UploadManagerRepository - UploadManager Repository
     */
    protected $uploadManagerRepository;

    /**
     * Constructor.
     *
     * @param UploadManagerRepository $uploadManagerRepository - UploadManager Repository
     *-----------------------------------------------------------------------*/
    public function __construct(UploadManagerRepository $uploadManagerRepository)
    {
        $this->uploadManagerRepository = $uploadManagerRepository;
    }

    /**
     * Prepare upload manager files.
     *
     * @param param
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareFiles()
    {
        $files = [];
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        $fileCollection = glob(getUploadManagerPath().'*', GLOB_BRACE);

        if (!empty($fileCollection)) {
            $uploadManagerURL = getUploadManagerURL();

            foreach ($fileCollection as $file) {
                $pathInfo = pathinfo($file);

                $originalFileName = $pathInfo['filename'].'.'.$pathInfo['extension'];

                $files[] = [
                    'name' => $originalFileName,
                    'url' => $uploadManagerURL.$originalFileName,
                    'is_image' => (in_array($pathInfo['extension'], $imageExtensions))
                                   ? true
                                   : false,
                ];
            }
        }

        return __engineReaction(1, ['files' => $files]);
    }

    /**
     * Process upload file.
     *
     * @param $uploadedFile
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processUpload($uploadedFile)
    {
        // Check if file empty or is valid
        if (empty($uploadedFile) and !$uploadedFile->isValid()) {
            return __engineReaction(3);
        }

        $fileExtension = $uploadedFile->getClientOriginalExtension();

        $fileName = $uploadedFile->getClientOriginalName();
        $fileBaseName = basename($fileName, '.'.$fileExtension);
        $path = getUploadManagerPath();

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        if (File::exists($path.'/'.$fileName)) {
            $fileName = $fileBaseName.'_'.uniqid().'.'.$fileExtension;
        }

        if ($uploadedFile->move($path, $fileName)) {
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

    /**
     * Delete file.
     *
     * @param string $fileName
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processDelete($fileName)
    {
        $path = getUploadManagerPath().$fileName;

        // Check if file exist
        if (!File::exists($path)) {
            return __engineReaction(18);
        }

        // Check if file deleted 
        if (File::delete($path)) {
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }
}
