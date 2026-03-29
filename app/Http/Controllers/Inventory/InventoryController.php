<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Services\Inventory\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function __construct(protected InventoryService $service) {}

    public function index()
    {
        return view('inventory.index', [
            'items' => $this->service->getAll()
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        // Toggle between Available, Booked, or Sold
        $this->service->update($id, ['status' => $request->status]);
        return back()->with('status', 'Inventory status updated.');
    }
}