<?php namespace App\Repositories\Crm;

use App\Repositories\BaseRepository;

class TaskRepository extends BaseRepository 
{
    protected string $table = 'tasks'; // That's it! It now has find, create, update, etc.

    // You only write code here if the Task needs something SPECIAL
    public function getOverdueTasks() {
        return \DB::table($this->table)->where('due_date', '<', now())->get();
    }
}