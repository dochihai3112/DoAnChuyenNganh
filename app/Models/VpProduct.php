<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\VpFavouriteProduct;

class VpProduct extends Model
{
    use HasFactory;
    protected $primaryKey = 'prod_id';
    protected $fillable = [
        'prod_name',
        'prod_slug',
        'prod_price',
        'prod_img',
        'prod_condition',
        'prod_status',
        'prod_description',
        'prod_featured',
        'prod_cate',
    ];

    public function favorite()
    {
        return $this->hasMany(VpFavouriteProduct::class, 'favou_product', 'prod_id');
    }

    public function category () {
        return $this->belongsTo(VpCategory::class);
    }
}
