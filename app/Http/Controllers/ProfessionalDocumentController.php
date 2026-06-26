<?php

namespace App\Http\Controllers;

use App\Services\ProfessionalDocumentStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfessionalDocumentController extends Controller
{
    public function store(Request $request, ProfessionalDocumentStorage $documents): RedirectResponse
    {
        abort_unless($request->user()->role === 'professional', 403);

        $data = $request->validate([
            'ata_certificate_document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'residence_permit_document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $documents->store($request->user(), $data);

        return redirect()
            ->route('dashboard')
            ->with('status', 'Documenti aggiornati.');
    }
}