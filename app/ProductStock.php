<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    use HasFactory;
    protected $table = 'stocks';
    
    protected $fillable = [
        'label_id',
        'category_id',
        'stock',
        'user_id',
    ];
    

    public function user(){
        return $this->belongsTo(User::class);
    }
 
    protected $appends = ['title','category'];
    protected $hidden = ['label'];

    public function label()
    {
        return $this->belongsTo(SparePartLabel::class,'label_id');
    }
   
    public function getTitleAttribute()
    {
        return $this->label ? $this->label->title : null;
    }
    public function getCategoryAttribute()
    {
        switch ($this->category_id) {
            case '1':
                $category = 'Accessory';
                break;
            case '2':
                $category = 'Parts';
                break;
            case '3':
                $category = 'Tools';
                break;
            case '4':
                $category = 'Lubricant';
                break;
            default:
                $category = null;
                break;
        }
        return $category;
    }
}

