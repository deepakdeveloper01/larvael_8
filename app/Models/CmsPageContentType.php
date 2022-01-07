<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CmsPageContentType extends Model
{
    
    use SoftDeletes;

    protected $table        = 'cms_page_contnet_types';
    protected $primaryKey   = 'id';
    public $timestamps   = true;

    protected $fillable     = [
                                'id', 'name', 'slug'
                            ];  
}
