<?php

namespace App\Http\Controllers\Hrms;

use App\Http\Controllers\Controller;
use App\Services\Hrms\EmployeeService;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct(protected EmployeeService $service) {}

    public function index()
    {
        return view('hrms.employees.index', [
            'employees' => $this->service->getAll()
        ]);
    }

    public function create()
    {
        return view('hrms.employees.create');
    }

    public function store(Request $request)
    {
        $this->service->create($request->all(), auth()->id() ?? 1);
        return redirect('/employees')->with('success', 'Employee onboarded successfully.');
    }
}