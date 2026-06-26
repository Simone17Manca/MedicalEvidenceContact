<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BusinessPointOfContactController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->role === 'business', 403);

        $businessProfile = $request->user()
            ->businessProfile()
            ->with('pointsOfContact')
            ->firstOrFail();

        return view('business-points-of-contact.index', [
            'businessProfile' => $businessProfile,
            'pointsOfContact' => $businessProfile->pointsOfContact,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->role === 'business', 403);

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
        ]);

        $businessProfile = $request->user()->businessProfile()->firstOrFail();
        $businessProfile->addPointOfContact($data);

        return redirect()
            ->route('business-points-of-contact.index')
            ->with('status', 'Point of Contact aggiunto.');
    }
}
