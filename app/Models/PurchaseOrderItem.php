<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderItem extends Model
{
    use HasFactory;
    protected $fillable = ['purchase_order_id', 'item_id', 'quantity', 'unit_price'];
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
