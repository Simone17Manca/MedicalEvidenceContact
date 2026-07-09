<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class InterviewController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        abort_unless(in_array($user->role, ['business', 'professional'], true), 403);

        $businessJobPostings = $user->role === 'business'
            ? $user->jobPostings()
                ->with([
                    'applications' => fn ($query) => $query
                        ->with('professional:id,name,first_name,last_name,role,residence')
                        ->latest(),
                ])
                ->withCount('applications')
                ->latest()
                ->get()
            : collect();

        $professionalApplications = $user->role === 'professional'
            ? $user->jobApplications()
                ->with('jobPosting.owner.businessProfile')
                ->latest()
                ->get()
            : collect();

        return view('interviews.index', [
            'businessJobPostings' => $businessJobPostings,
            'professionalApplications' => $professionalApplications,
            'role' => $user->role,
        ]);
    }
}
