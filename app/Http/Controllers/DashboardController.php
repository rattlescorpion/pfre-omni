<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $greeting = match (true) {
            Carbon::now()->hour < 12 => 'Morning',
            Carbon::now()->hour < 17 => 'Afternoon',
            default                  => 'Evening',
        };

        $kpis = [
            'active_leads'    => Schema::hasTable('leads') 
                ? DB::table('leads')->whereNotIn('stage', ['closed_won', 'closed_lost'])->count() 
                : 0,
            'pending_rent'    => Schema::hasTable('leases') 
                ? DB::table('leases')->where('status', 'active')->count() 
                : 0,
            'open_tickets'    => Schema::hasTable('tickets') 
                ? DB::table('tickets')->where('status', 'open')->count() 
                : 0,
            'monthly_revenue' => 0,
        ];

        $recentLeads = Schema::hasTable('leads')
            ? DB::table('leads')->latest()->take(5)->get()
            : collect();

        return view('dashboard', compact('greeting', 'kpis', 'recentLeads'));
    }
}