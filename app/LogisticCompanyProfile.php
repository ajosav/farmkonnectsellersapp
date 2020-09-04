<?php

namespace App;

use App\Traits\ProcessBase64PicTrait;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class LogisticCompanyProfile extends Model
{
    use HasUUID, SoftDeletes, ProcessBase64PicTrait;
    protected $guarded = [];

    public static function createProfile($request)
    {
        $owner = $request->user()->logisticCompanyProfile;
        if ($owner == null || $owner->driving_license != $request->driving_license) {
            $license_slug = Str::of($request->name)->before(' ') . '_' . $request->chasis_no . '-license' . rand(0000, 9999) . '.';
            $license = self::renamePicture($license_slug, 'public/logistic_docs', $request->driving_license);
            $currentPhoto = $owner ? $owner->driving_license : null;
            if (Storage::exists('public/logistic_docs/' . $currentPhoto)) {

                Storage::delete('public/logistic_docs/' . $currentPhoto);
            }
        } else {
            $license = $request->driving_license;
        }

        $other_fields = $request->other_fields;
        $user_str = substr($request->user()->uuid, 0, 5);

        if ($owner == null) {
            foreach ($other_fields as $index => $field) {
                $doc_slug = Str::of($request->name)->before(' ') . '_' . $field['title'] . '_' . $user_str . '.';
                $doc_name = self::renamePicture($doc_slug, 'public/logistic_docs', $field['document']);
                $other_fields[$index]['document'] = $doc_name;
            }
        } elseif ($owner->valid_vehicle_doc) {
            $existing_doc = json_decode($owner->valid_vehicle_doc);
            foreach ($other_fields as $index => $field) {
                $in_base64 = Str::startsWith($field['document'], 'data:');

                if ($in_base64) {
                    $doc_slug = Str::of($request->name, ' ') . '_' . $field['title'] . '_' . $user_str . '.';
                    $doc_name = self::renamePicture($doc_slug, 'public/logistic_docs', $field['document']);
                    $other_fields[$index]['document'] = $doc_name;
                    continue;
                }
            }
        }

        $other_fields = json_encode($other_fields);
        self::updateOrCreate(
            [
                'user_uuid' => $request->user()->uuid
            ],
            [
                'name' => $request->name,
                'rate' => $request->rate,
                'state' => $request->state,
                'lg' => $request->lg,
                'address' => $request->address,
                'location' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'driving_license' => $license,
                'license_expiration' => $request->license_expiration,
                'vehicle_reg_no' => $request->registration_no,
                'valid_vehicle_doc' => $other_fields,
                'chasis' => $request->chasis_no,
                'other_info' => $request->other_info,
            ]
        );
    }
}