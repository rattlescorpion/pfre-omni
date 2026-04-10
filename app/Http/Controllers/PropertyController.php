<?php

namespace App\Http\Controllers;

use App\Services\Crm\PropertyService;
use App\Http\Requests\Crm\StorePropertyRequest;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function __construct(protected PropertyService $service)
    {
    }

    public function index()
    {
        return view('properties.index', ['properties' => $this->service->getAll()]);
    }

    public function create()
    {
        return view('properties.create');
    }

    public function store(StorePropertyRequest $request)
    {
        $this->service->create($request->validated(), auth()->id() ?? 1);
        return redirect('/properties')->with('success', 'Property added to inventory.');
    }
}