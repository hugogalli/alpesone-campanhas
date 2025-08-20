<?php namespace Alpes\Campaigns\Models;

use Model;
use Winter\Storm\Database\Traits\Validation;

class Campaign extends Model
{
    use Validation;

    public $table = 'alpes_campaigns';

    protected $fillable = [
        'name','slug','is_active','meta_title','meta_description',
        'section1','section2','section3','footer', 'brand'
    ];

    protected $jsonable = ['section1','section2','section3','footer','brand'];

    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:alpes_campaigns',
        'section3' => 'max:3'
    ];
}
