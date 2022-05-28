<?php

namespace App\Helpers;
use Closure;
use Image;
use Intervention\Image\Constraint;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class FileHelper
{
    /**
     * Save the uploaded image.
     *
     * @param UploadedFile $file     Uploaded file.
     * @param int          $resizeWidth
     * @param string       $path
     * @param string       $extension
     * @param array        $watermark_params
     * @param string       $filename Custom file naming method.
     *
     * @return string File name.
     */
    public static function saveImage( $file, $resizeWidth, $path = null, $extension = null, $watermark_params = [], $filename = null)
    {
        if (!$path) {
            return null;
//            $path = config('filesystems.uploads.images');
        }

        if ($filename) {
            $fileName = $filename;
        } else {
            $fileName = self::getFileName($file,$extension);
        }

        $img = self::makeImage($file);

        if (!empty($watermark_params)){
            $img = self::addWatermark($img, $watermark_params);
        }

        if ($resizeWidth != 0) {
            $img = self::resizeImage($img, $resizeWidth);
        }
        self::uploadImage($img, $fileName, $path,$extension);

        return $fileName;
    }

    /**
     * Get uploaded file's name.
     *
     * @param UploadedFile $file
     * @param string       $extension
     *
     * @return null|string
     */
    protected static function getFileName(UploadedFile $file,$extension)
    {
        $filename = $file->getClientOriginalName();
        $ext = $extension ? $extension : pathinfo($filename, PATHINFO_EXTENSION);
        $filename = date('Ymd_His') . rand(10,99). '.' . $ext;

        return $filename;
    }

    /**
     * Create the image from upload file.
     *
     * @param UploadedFile $file
     *
     * @return \Intervention\Image\Image
     */
    protected static function makeImage(UploadedFile $file)
    {
        return Image::make($file);
    }

    /**
     * Resize image to the configured size.
     *
     * @param \Intervention\Image\Image $img
     * @param int                       $maxWidth
     *
     * @return \Intervention\Image\Image
     */
    protected static function resizeImage(\Intervention\Image\Image $img, $maxWidth = 150)
    {
        $img->resize($maxWidth, null, function (Constraint $constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        return $img;
    }

    /**
     * Save the uploaded image to the file system.
     *
     * @param \Intervention\Image\Image $img
     * @param string                    $fileName
     * @param string                    $path
     * @param string                    $extension
     */
    protected static function uploadImage($img, $fileName, $path, $extension)
    {
        if ($extension){
            $img->encode($extension)->save($path . $fileName);
        }else{
            $img->save($path . $fileName);
        }
    }



    protected static function addWatermark($img,  $params)
    {
        foreach ($params as $param){
            $img->insert(Image::make($param['icon'])->opacity(60), $param['position']);
        }

        return $img;
    }
}
