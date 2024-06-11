<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Car extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }


    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }


    public function year(): BelongsTo
    {
        return $this->belongsTo(Year::class);
    }


    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }


    public function values()
    {
        return $this->belongsToMany(Value::class, 'car_values')
            ->orderBy('car_values.sort_order');
    }


    public function image()
    {
        if ($this->image) {
            return Storage::url('public/cars/' . $this->image);
        } else {
            return $this->image ? asset('img/cars/'. $this->image) : asset('img/cars/7.jpg');
        }
    }
}
