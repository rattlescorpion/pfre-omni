<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Services\Crm\TaskService;
use App\Http\Requests\Crm\StoreTaskRequest;
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

    public function store(StoreTaskRequest $request)
    {
        $this->service->create($request->validated(), auth()->id() ?? 1);
        return back()->with('success', 'Task scheduled.');
    }

    public function update(Request $request, $id)
    {
        $this->service->update($id, $request->except(['_token', '_method']));
        return back();
    }
}