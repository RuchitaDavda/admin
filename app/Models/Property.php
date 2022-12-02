<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $table = 'propertys';

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'address',
        'client_address',
        'propery_type',
        'price',
        'unit_type',
        'carpet_area',
        'build_up_area',
        'plot_area',
        'hecta_area',
        'acre',
        'house_type',
        'furnished',
        'house_no',
        'survey_no',
        'plot_no',
        'title_image',
        'state',
        'taluka',
        'village',
        'status',
        'total_click',
        'latitude',
        'longitude'

    ];
    protected $hidden = [
        'updated_at',
        'deleted_at'
    ];

    protected $appends = [
        'gallery'
    ];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id')->select('id', 'category', 'parameter_types');
    }


    public function unitType()
    {
        return $this->hasOne(Unit::class, 'id', 'unit_type')->select('id', 'measurement');
    }


    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'added_by')->select('id', 'name');
    }


    public function houseType()
    {
        return $this->hasOne(Housetype::class, 'id', 'house_type')->select('id', 'type');
    }
    public function favourite()
    {
        return $this->hasMany(Favourite::class);
    }
    public function assignparameter()
    {
        return $this->hasMany(AssignParameters::class);
    }
    public function getGalleryAttribute()
    {
        $data = PropertyImages::select('id', 'image')->where('propertys_id', $this->id)->get();


        foreach ($data as $item) {
            if ($item['image'] != '') {
                $item['image'] = $item['image'];
                $item['image_url'] = ($item['image'] != '') ? url('') . config('global.IMG_PATH') . config('global.PROPERTY_GALLERY_IMG_PATH') . $this->id . "/" . $item['image'] : '';
            }
        }
        return $data;
    }

    protected $casts = [
        'category_id' => 'integer',
    ];
}
