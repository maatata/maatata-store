<?php
/*
* MediaEngine.php - Main component file
*
* This file is part of the Media component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Media;

use Auth;
use File;
use config;
use App\Yantrana\Components\Media\Repositories\MediaRepository;
use App\Yantrana\Components\Media\Blueprints\MediaEngineBlueprint;
use ImageIntervention;

class MediaEngine implements MediaEngineBlueprint
{
    /**
     * @var MediaRepository - Media Repository
     */
    protected $mediaRepository;

    /**
     * Constructor.
     *
     * @param MediaRepository $mediaRepository - Media Repository
     *-----------------------------------------------------------------------*/
    public function __construct(MediaRepository $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
    }

    /**
     * Process on uploaded image file.
     *
     * @param object $imageFile
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processUploadedImage($imageFile)
    {
        // Check if file empty or is valid
        if (empty($imageFile) and !$imageFile->isValid()) {
            return __engineReaction(2);
        }

        $allowedExtensions = ['png', 'jpeg', 'jpg'];
        $fileExtension = $imageFile->getClientOriginalExtension();

        if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
            return __engineReaction(3);
        }

        $fileName = $imageFile->getClientOriginalName();
        $fileBaseName = str_slug(basename($fileName, '.'.$fileExtension));
        $path = getLoggedInUserTempMediaPath();

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        $fileName = $fileBaseName.'_'.uniqid().'.'.$fileExtension;

        if ($imageFile->move($path, $fileName)) {
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

    /**
     * Prepare uploaded images files form temp media storage.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareUploadedImagesFiles()
    {
        $userID = Auth::id();
        $files = [];
        $tempFiles = glob(getLoggedInUserTempMediaPath().'*.{jpg,jpeg,png,gif}', GLOB_BRACE);

        if (!empty($tempFiles)) {
            foreach ($tempFiles as $tempFile) {
                $pathInfo = pathinfo($tempFile);

                extract($pathInfo);

                $imageName = $filename.'.'.$extension;

                $files[] = [
                    'name' => $imageName,
                    'path' => getUserTempImageURL($imageName, $userID),
                ];
            }
        }

        return __engineReaction(1, ['files' => $files]);
    }

    /**
     * Check if provied media exist in user temp media.
     *
     * @param string $fileName
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function isUserTempMedia($fileName)
    {
        return File::exists(getLoggedInUserTempMediaPath().$fileName);
    }

    /**
     * Check if provied media exist in setting logo media.
     *
     * @param string $fileName
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function isStoreTempMedia($fileName)
    {
        return File::exists(getLogoMediaPath().'/'.$fileName);
    }

    /**
     * Store product media.
     *
     * @param string $fileName
     * @param number $productID
     * @param string $removeExistingFileName
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function storeProductMedia($fileName, $productID,
     $removeExistingFileName = null, $generateThumbnail = false)
    {
        $productMediaPath = getProductMediaPath($productID);
        $sourcePath = getLoggedInUserTempMediaPath().$fileName;
        $destinationPath = $productMediaPath.'/'.$fileName;

        //Check if product media directive exist
        if (!File::isDirectory($productMediaPath)) {
            File::makeDirectory($productMediaPath, $mode = 0777, true, true);
        }

        // Check if media file already exist
        if (File::exists($destinationPath)) {
            $filePathInfo = pathinfo($destinationPath);
            $fileName = $filePathInfo['filename'].'_'.uniqid()
                                    .'.'.$filePathInfo['extension'];
            $destinationPath = $productMediaPath.'/'.$fileName;
        }

        if (File::move($sourcePath, $destinationPath)) {
            if (!empty($removeExistingFileName)
                and File::exists($productMediaPath.'/'.$removeExistingFileName)) {
                if (!File::delete($productMediaPath.'/'.$removeExistingFileName)) {
                    return false;
                }
            }

            if ($generateThumbnail) {
                $width = 300;
                $height = 300;

                if ($generateThumbnail === 'productSliderImage') {
                    $width = 600;
                    $height = 600;
                }

                // open an image file
                $thumbnail = ImageIntervention::make($destinationPath);
                // now you are able to resize the instance
                $thumbnail->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                     $constraint->upsize();
                });
                // finally we save the image as a new image
                $thumbnail->save($destinationPath);
            }

            return $fileName;
        }

        return false;
    }

    /**
     * Delete user temp media file.
     *
     * @param string $fileName
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processDeleteTempMedia($fileName)
    {
        $path = getLoggedInUserTempMediaPath().$fileName;

        // Check if file exist
        if (!File::exists($path)) {
            return __engineReaction(3);
        }

        // Check if file deleted 
        if (File::delete($path)) {
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

    /**
     * Delete user temp media file.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processDeleteMultipleTempMedia($files)
    {
        // Check if file empty
        if (empty($files)) {
            return __engineReaction(2);
        }

        $deletedFileCount = 0;
        $userTempMediaPath = getLoggedInUserTempMediaPath();

        foreach ($files as $fileName) {
            $sourcePath = $userTempMediaPath.$fileName;

            // Check if file exist
            if (File::exists($sourcePath) and File::delete($sourcePath)) {
                ++$deletedFileCount;
            }
        }

        if ($deletedFileCount > 0) {
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

    /**
     * Delete product medias using product id.
     *
     * @param number $productID
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function processDeleteProductMedias($productID)
    {
        $productMediaPath = getProductMediaPath($productID);

        // Check if product media directory exist & is deleted successfully
        if (File::isDirectory($productMediaPath)
            and File::deleteDirectory($productMediaPath)) {
            return true;
        }

        return false;
    }

    /**
     * Delete product media image using product id & image file name.
     *
     * @param number $productID
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function processDeleteProductMediaImage($productID, $image_file_name)
    {
        $imageMediaPath = getProductMediaPath($productID).'/'.$image_file_name;

        // Check if product image media exist & is deleted successfully
        if (File::exists($imageMediaPath) and File::delete($imageMediaPath)) {
            return true;
        }

        return false;
    }

    /**
     * Process store logo media.
     *
     * @param string $logoImageFile
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function processStoreSettingLogoMedia($logoImageFile)
    {
        $sourcePath = getLoggedInUserTempMediaPath().$logoImageFile;

        // If Logo Image File Not Exist Then Return False
        if (!File::exists($sourcePath)) {
            return false;
        }

        // Set extension for logo image
        $allowedExtensions = 'png';

        // Get path Info about selected logo
        $logoImageInfo = pathinfo($sourcePath);

        // Get extension of selected logo
        $fileExtension = $logoImageInfo['extension'];

        // Check if logo extention
        if (strtolower($fileExtension) != $allowedExtensions) {
            return false;
        }

        // Get source and media path
        $logoMediaPath = getLogoMediaPath();

        //Check if logo media directory exist
        if (!File::isDirectory($logoMediaPath)) {
            File::makeDirectory($logoMediaPath, $mode = 0777, true, true);
        }

        // Default Logo Image File Name
        $logoName = config('__tech.logoName');

        // Set name for image
        $destinationPath = $logoMediaPath.'/'.$logoName;

       // File::cleanDirectory($logoMediaPath);

        // If Logo Image File Moved to Logo Media Storage Then Return Image File Name
        if (File::move($sourcePath, $destinationPath)) {
            return $logoName;
        }

        return false;
    }

    /**
     * Process on uploaded image file.
     *
     * @param object $imageFile
     *
     * @return array
     *---------------------------------------------------------------- */
    public function storeBrandLogoMedia($fileName, $brandID,
     $removeExistingFileName = null, $generateThumbnail = false)
    {
        $brandMediaPath = getBrandMediaUrl($brandID);
        $sourcePath = getLoggedInUserTempMediaPath().$fileName;
        $destinationPath = $brandMediaPath.'/'.$fileName;

        //Check if product media directive exist
        if (!File::isDirectory($brandMediaPath)) {
            File::makeDirectory($brandMediaPath, $mode = 0777, true, true);
        }

        // Check if media file already exist
        if (File::exists($destinationPath)) {
            $filePathInfo = pathinfo($destinationPath);
            $fileName = $filePathInfo['filename'].'_'.uniqid()
                                    .'.'.$filePathInfo['extension'];
            $destinationPath = $brandMediaPath.'/'.$fileName;
        }

        if (File::move($sourcePath, $destinationPath)) {
            if (!empty($removeExistingFileName)
                and File::exists($brandMediaPath.'/'.$removeExistingFileName)) {
                if (!File::delete($brandMediaPath.'/'.$removeExistingFileName)) {
                    return false;
                }
            }

            if ($generateThumbnail) {
                // open an image file
                $thumbnail = ImageIntervention::make($destinationPath);
                // now you are able to resize the instance
                $thumbnail->resize(200, 200, function ($constraint) {
                    $constraint->aspectRatio();
                     $constraint->upsize();
                });
                // finally we save the image as a new image
                $thumbnail->save($destinationPath);
            }

            return $fileName;
        }

        return false;
    }

    /**
     * Delete product medias using product id.
     *
     * @param number $productID
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function processDeleteBrandMedias($brandID)
    {
        $brandMediaPath = getBrandMediaUrl($brandID);

        // Check if product media directory exist & is deleted successfully
        if (File::isDirectory($brandMediaPath)
            and File::deleteDirectory($brandMediaPath)) {
            return true;
        }

        return false;
    }
}
