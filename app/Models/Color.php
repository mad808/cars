<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $guarded = [
        'id',
    ];

    public $timestamps = false;


    public function cars()
    {
        return $this->hasMany(Car::class);
    }


    public function getName()
    {
        $locale = app()->getLocale();
        if ($locale == 'tm') {
            return $this->name;
        }

        elseif ($locale == 'tr') {
            return $this->name_tr ?: $this->name;
        }

        elseif ($locale == 'ru') {
            return $this->name_ru ?: $this->name;
        }

        elseif ($locale == 'uk') {
            return $this->name_uk ?: $this->name;
        }

        elseif ($locale == 'en') {
            return $this->name_en ?: $this->name;
        }

        else {
            return $this->name;
        }
    }
}
