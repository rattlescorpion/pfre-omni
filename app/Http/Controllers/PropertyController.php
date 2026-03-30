<?php

namespace App\Http\Controllers;

use App\Services\Crm\PropertyService;
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

    public function store(Request $request)
    {
        $this->service->create($request->all(), auth()->id() ?? 1);
        return redirect('/properties')->with('success', 'Property added to inventory.');
    }
}