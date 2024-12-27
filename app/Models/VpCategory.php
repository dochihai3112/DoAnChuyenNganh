<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VpCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'cate_id';
    protected $fillable = [
        'cate_name',
        'cate_slug',
    ];

    public function products () {
        return $this->hasMany(VpProduct::class,'prod_cate');
    }
}
