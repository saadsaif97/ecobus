<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [ 'name', 'price', 'image', 'description', ];

    /**
     * Delete the image associated with product
     * 
     */

     public function deleteImage()
     {
        // image in database is stored as:
        // storage/products/46f02s5B7RWYJZAvz354bitvvNJatUvZ70WuZqfg.png
        // to delete we want products/46f02s5B7RWYJZAvz354bitvvNJatUvZ70WuZqfg.png

        $imageExplode = explode("/",$this->image);

         Storage::delete("$imageExplode[1]/$imageExplode[2]");
     }
}
