<?php

namespace App\Http\Controllers;

use App\Repositories\Crm\PropertyRepository;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function __construct(protected PropertyRepository $propertyRepo) {}

    public function index()
    {
        $properties = $this->propertyRepo->getAll();
        return view('properties.index', compact('properties'));
    }

    public function create()
    {
        return view('properties.create');
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255']);
        $this->propertyRepo->create($request->all());
        return redirect('/properties');
    }
}