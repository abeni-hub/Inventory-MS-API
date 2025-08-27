<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function lowStock(Request $request)
    {
        $threshold = $request->input('threshold', 10); // Default threshold
        $items = Item::where('quantity', '<', $threshold)->get();
        return response()->json($items);
    }

    public function totalValue()
{
    $total = Item::sum(DB::raw('quantity * price'));
    return response()->json(['total_value' => $total]);
}

    public function categorySummary()
{
    $summary = Item::with('category')
        ->select('category_id', DB::raw('COUNT(*) as item_count'), DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(quantity * price) as total_value'))
        ->groupBy('category_id')
        ->get()
        ->map(function ($row) {
            return [
                'category_id' => $row->category_id,
                'category_name' => $row->category ? $row->category->name : 'Uncategorized',
                'item_count' => $row->item_count,
                'total_quantity' => $row->total_quantity,
                'total_value' => $row->total_value,
            ];
        });
    return response()->json($summary);
}
}