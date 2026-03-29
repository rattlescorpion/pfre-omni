<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // This tells Laravel we want to talk to the database!

final class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Fetch real data directly from the pfre_omni database!
        $kpis = [
            // Count all leads that are not marked as closed
            'active_leads' => DB::table('leads')
                ->whereNotIn('stage', ['closed_won', 'closed_lost'])
                ->count(),
            
            // Count all currently active leases
            'pending_rent' => DB::table('leases')
                ->where('status', 'active')
                ->count(),
            
            // Count all support tickets that are currently open
            'open_tickets' => DB::table('tickets')
                ->where('status', 'open')
                ->count()
        ];

        // Send the real data to the visual dashboard
        return view('dashboard', compact('kpis'));
    }
}