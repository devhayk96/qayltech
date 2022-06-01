<?php

use App\Helpers\FileHelper;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

if (! function_exists('save_image')) {
    function save_image( $file, $maxWidth = 150, $path = null,$extension = null, $watermark_params = [], $filename = null)
    {
        return FileHelper::saveImage($file, $maxWidth, $path, $extension,$watermark_params, $filename);
    }
}

if (! function_exists('upload_path')) {
    function upload_path()
    {
        return 'uploads';
    }
}

if (!function_exists('make_directory')) {
    function make_directory($dir_names)
    {
        File::ensureDirectoryExists(upload_path(),777);
        if (is_array($dir_names)){
            $path = public_path(upload_path().'/');
            foreach ($dir_names as $dir_name){
                $path .= $dir_name.'/';
            }
        }else{
            $path = public_path(upload_path().'/'.$dir_names);
        }
        File::ensureDirectoryExists($path,777);
        return $path.'/';

    }
}

if (!function_exists('make_directory_v2')) {
    function make_directory_v2($path)
    {
        $dir_name = '';
        foreach (explode('/',$path) as $folder){
            if ($folder != '' && $folder != '/'){
                $dir_name .= $folder . '/';
                if (!File::exists(public_path($dir_name))){
                    File::makeDirectory(public_path($dir_name),0755,true);
                }
            }
        }

    }
}

if (!function_exists('image_exists')) {
    function image_exists($image,$folder)
    {
        if ($image && File::exists(public_path(upload_path()."/$folder/$image"))){
            return asset(upload_path()."/$folder/$image");
        }

        return null;
    }
}

if (! function_exists('current_user')) {
    function current_user()
    {
        return auth('api')->check()
            ? User::find(auth('api')->user()->id)->load(['role'])
            : null;
    }
}

if (! function_exists('current_user_role')) {
    function current_user_role()
    {
        return current_user() ? current_user()->role_id : null;
    }
}


if (! function_exists('is_super_admin')) {
    function is_super_admin()
    {
        return current_user_role() == Role::ALL['super_admin'];
    }
}


if (! function_exists('generate_user_password')) {
    function generate_user_password($length = 10)
    {
        return Str::random($length);
    }
}
