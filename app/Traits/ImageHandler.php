<?php

namespace App\Traits;
use Intervention\Image\Facades\Image as Image;
use Illuminate\Support\Facades\File;

trait ImageHandler
{
    public function saveImage($file, $folder)
    {
        $makeImage = Image::make($file);
        $path = public_path().'/storage/images/'.$folder.'/';
        $image = time().$file->getClientOriginalName();
        $makeImage->save($path.$image, 72);
        return $image;
    }

    public function retrieveImage($fileName)
    {
        // $path = storage_path('public/' . $filename);
    }

    public function deleteImage($fileName, $folder)
    {
        $image_path = public_path().'/storage/images/'.$folder.'/'.$fileName;
        if(File::exists($image_path)) {
            File::delete($image_path);
        }
    }
}
