<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Services\Crm\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(protected TaskService $service) {}

    public function index()
    {
        return view('crm.tasks.index', [
            'tasks' => $this->service->getAll()
        ]);
    }

    public function store(Request $request)
    {
        $this->service->create($request->all(), auth()->id() ?? 1);
        return back()->with('success', 'Task scheduled.');
    }

    public function update(Request $request, $id)
    {
        $this->service->update($id, $request->all());
        return back();
    }
}