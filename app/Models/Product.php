<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'subCategory',
        'brand',
        'title',
        'price',
        'quantity',
        'description',
        'image',
    ];
}