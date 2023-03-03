<?php

namespace App\Helper;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

trait FileUploader
{
    public string $public_path = "/public/uploadedImages";
    public string $storage_path = "/storage/uploadedImages";

    public function uploadFile($file, $path, $width, $height): string
    {
        $extension = $file->getClientOriginalExtension();
        $file_name = $path . Str::random(30) . '.' . $extension;
        $url = $file->storeAs($this->public_path, $file_name);
        $public_path = public_path($this->storage_path . $file_name);
        $img = Image::make($public_path)->resize($width, $height);
        $url = preg_replace("/public/", "", $url);
        $final = $img->save($public_path) ? $url : '';
        return App::make('url')->to('/') . '/storage' . $final;
    }
}
