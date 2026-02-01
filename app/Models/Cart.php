<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable=['user_id','product_id','order_id','quantity','amount','price','status', 'product_title', 'product_photo', 'product_summary', 'product_title','product_photo','product_summary'];
    // ADDED: Product snapshot fields to preserve order history
    // Add product title, photo, and summary to fillable attributes
    
    // public function product(){
    //     return $this->hasOne('App\Models\Product','id','product_id');
    // }
    // public static function getAllProductFromCart(){
    //     return Cart::with('product')->where('user_id',auth()->user()->id)->get();
    // }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }
    public function getProductTitleAttribute($value)
    {
        // Prioritas 1: Gunakan snapshot jika ada
        if (!empty($this->attributes['product_title'])) {
            return $this->attributes['product_title'];
        }
        
        // Prioritas 2: Fallback ke relasi product
        return $this->product ? $this->product->title : 'Product Not Available';
    }
}
