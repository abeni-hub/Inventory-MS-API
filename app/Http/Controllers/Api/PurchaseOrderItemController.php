<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PurchaseOrderItemController extends Controller
{
    public function index()
    {
        $purchaseOrderItems = PurchaseOrderItem::with(['purchaseOrder', 'item'])->get();
        return response()->json($purchaseOrderItems);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $purchaseOrderItem = PurchaseOrderItem::create($request->only(['purchase_order_id', 'item_id', 'quantity', 'unit_price']));

        // Update PO total
        $po = PurchaseOrder::find($request->purchase_order_id);
        $po->updateTotal(); // Assuming you add this method to PurchaseOrder model
        $po->save();

        return response()->json($purchaseOrderItem, 201);
    }

    public function show($id)
    {
        $purchaseOrderItem = PurchaseOrderItem::with(['purchaseOrder', 'item'])->find($id);
        if (!$purchaseOrderItem) {
            return response()->json(['message' => 'Purchase Order Item not found'], 404);
        }
        return response()->json($purchaseOrderItem);
    }

    public function update(Request $request, $id)
    {
        $purchaseOrderItem = PurchaseOrderItem::find($id);
        if (!$purchaseOrderItem) {
            return response()->json(['message' => 'Purchase Order Item not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'quantity' => 'sometimes|required|integer|min:1',
            'unit_price' => 'sometimes|required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $purchaseOrderItem->update($request->only(['quantity', 'unit_price']));

        // Update PO total
        $po = $purchaseOrderItem->purchaseOrder;
        $po->updateTotal();
        $po->save();

        return response()->json($purchaseOrderItem);
    }

    public function destroy($id)
    {
        $purchaseOrderItem = PurchaseOrderItem::find($id);
        if (!$purchaseOrderItem) {
            return response()->json(['message' => 'Purchase Order Item not found'], 404);
        }

        $po = $purchaseOrderItem->purchaseOrder;
        $purchaseOrderItem->delete();

        // Update PO total
        $po->updateTotal();
        $po->save();

        return response()->json(['message' => 'Purchase Order Item deleted']);
    }
}