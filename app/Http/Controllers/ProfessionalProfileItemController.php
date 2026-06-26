<?php

namespace App\Http\Controllers;

use App\Models\ProfessionalProfileItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfessionalProfileItemController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->role === 'professional', 403);

        $request->user()->professionalProfileItems()->create($this->validatedData($request));

        return redirect()
            ->route('dashboard')
            ->with('status', 'Profilo professionale aggiornato.');
    }

    public function update(Request $request, ProfessionalProfileItem $professionalProfileItem): RedirectResponse
    {
        $this->authorizeOwner($request, $professionalProfileItem);

        $professionalProfileItem->update($this->validatedData($request));

        return redirect()
            ->route('dashboard')
            ->with('status', 'Elemento del profilo aggiornato.');
    }

    public function destroy(Request $request, ProfessionalProfileItem $professionalProfileItem): RedirectResponse
    {
        $this->authorizeOwner($request, $professionalProfileItem);

        $professionalProfileItem->delete();

        return redirect()
            ->route('dashboard')
            ->with('status', 'Elemento del profilo eliminato.');
    }

    /**
     * @return array<string, string|null>
     */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'type' => ['required', Rule::in([
                ProfessionalProfileItem::TYPE_WORK_EXPERIENCE,
                ProfessionalProfileItem::TYPE_EDUCATION,
            ])],
            'title' => ['required', 'string', 'max:180'],
            'duration' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:3000'],
        ]);
    }

    private function authorizeOwner(Request $request, ProfessionalProfileItem $professionalProfileItem): void
    {
        abort_unless($request->user()->role === 'professional', 403);
        abort_unless($professionalProfileItem->user_id === $request->user()->id, 403);
    }
}