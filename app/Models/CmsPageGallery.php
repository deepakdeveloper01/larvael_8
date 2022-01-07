<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CmsPageGallery extends Model
{
    use SoftDeletes; 
    protected $table        = 'cms_page_gallery';
    protected $primaryKey   = 'id';
    public $timestamps   = true;

    protected $fillable     = [
                                'cms_page_id', 'name','image_path',
                                'short_description','status',
                                'sort_order',
                            ];


    public function cms_page(){
        return $this->belongsTo('App\Models\CmsPage');
    }

}    
