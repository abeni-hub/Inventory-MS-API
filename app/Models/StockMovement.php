<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockMovement extends Model
{
    use HasFactory;
    protected $fillable = ['warehouse_id', 'item_id', 'quantity', 'type', 'notes'];
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function source()
    {
        return $this->morphTo();
    }
}