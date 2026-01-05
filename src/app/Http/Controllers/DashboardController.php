<?php

namespace App\Http\Controllers;

use App\Models\World;
use App\Models\Entity;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $data = [
            'worlds' => $user->worlds()->withCount('entities')->get(),
            'totalEntities' => Entity::whereHas('world', function($q) use ($user) {
                $q->where('owner_id', $user->id);
            })->count(),
        ];

        if ($user->isAdmin()) {
            $data['totalUsers'] = User::count();
            $data['totalWorlds'] = World::count();
        }

        return view('dashboard', $data);
    }
}
