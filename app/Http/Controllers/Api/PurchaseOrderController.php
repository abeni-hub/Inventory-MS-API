<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with(['supplier', 'items.item'])->get();
        return response()->json($purchaseOrders);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'status' => 'sometimes|in:pending,received,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $purchaseOrder = PurchaseOrder::create($request->only(['supplier_id', 'order_date', 'status']));
        return response()->json($purchaseOrder, 201);
    }

    public function show($id)
    {
        $purchaseOrder = PurchaseOrder::with(['supplier', 'items.item'])->find($id);
        if (!$purchaseOrder) {
            return response()->json(['message' => 'Purchase Order not found'], 404);
        }
        return response()->json($purchaseOrder);
    }

    public function update(Request $request, $id)
    {
        $purchaseOrder = PurchaseOrder::find($id);
        if (!$purchaseOrder) {
            return response()->json(['message' => 'Purchase Order not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'supplier_id' => 'sometimes|required|exists:suppliers,id',
            'order_date' => 'sometimes|required|date',
            'status' => 'sometimes|in:pending,received,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $purchaseOrder->update($request->only(['supplier_id', 'order_date', 'status']));
        $purchaseOrder->updateTotal(); // Assuming you add this method to the model
        return response()->json($purchaseOrder);
    }

    public function destroy($id)
    {
        $purchaseOrder = PurchaseOrder::find($id);
        if (!$purchaseOrder) {
            return response()->json(['message' => 'Purchase Order not found'], 404);
        }
        $purchaseOrder->delete();
        return response()->json(['message' => 'Purchase Order deleted']);
    }

    public function receive($id)
    {
        $purchaseOrder = PurchaseOrder::with('items.item')->findOrFail($id);
        if ($purchaseOrder->status !== 'pending') {
            return response()->json(['message' => 'Order already processed'], 400);
        }

        foreach ($purchaseOrder->items as $poItem) {
            $item = $poItem->item;
            $item->quantity += $poItem->quantity;
            $item->save();
        }

        $purchaseOrder->status = 'received';
        $purchaseOrder->save();

        return response()->json($purchaseOrder);
    }
}