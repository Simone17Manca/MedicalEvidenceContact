<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        abort_unless($request->user()->role === 'admin', 403);

        return view('admin.dashboard', [
            'usersCount' => User::count(),
            'jobPostingsCount' => JobPosting::count(),
            'recentUsers' => User::latest()->limit(5)->get(),
            'recentJobPostings' => JobPosting::with('owner')->latest()->limit(5)->get(),
        ]);
    }
}
