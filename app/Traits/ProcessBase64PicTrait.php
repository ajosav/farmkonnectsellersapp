<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 *
 */
trait ProcessBase64PicTrait
{

    public static function renamePicture($slug, String $public_path, $source) {
        $result = '';
        if(strpos( $source, ',')) {
            @list($residue, $result) = explode(',', $source);
        }
        $decoded_file = base64_decode($result);

        $name = $slug . explode('/', explode(':', substr($source, 0, strpos($source, ';')))[1])[1];

        Storage::put($public_path.'/'.$name, $decoded_file, 'public');

        return $name;
    }
}
