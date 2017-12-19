<?php
namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model{
    protected $table = 'productos';
    protected $fillable = ['name', 'quantity', 'price', 'description', 'brand', 'type' ];

    public function comments(){
        return $this->hasMany('\App\Models\Comment')->orderBy('created_at', 'asc');
    }
}
