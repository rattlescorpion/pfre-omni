<?php namespace App\Repositories\Crm;

use App\Repositories\BaseRepository;

class TaskRepository extends BaseRepository 
{
    protected string $table = 'tasks'; // That's it! It now has find, create, update, etc.

    /**
     * Define mass-assignable fields for tasks.
     */
    protected array $fillable = [
        'title',
        'description',
        'due_date',
        'priority',
        'status',
        'assigned_to',
        'assigned_by',
        'lead_id',
        'property_id',
        'reminder_sent',
        'completed_at',
    ];

    // You only write code here if the Task needs something SPECIAL
    public function getOverdueTasks() {
        return \DB::table($this->table)->where('due_date', '<', now())->get();
    }
}