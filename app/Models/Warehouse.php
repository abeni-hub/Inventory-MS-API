<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Stock;
use App\Models\StockMovement;

class Warehouse extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'address'];
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
