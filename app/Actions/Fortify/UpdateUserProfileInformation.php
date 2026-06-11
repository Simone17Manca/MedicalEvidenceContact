<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            'nationality' => [Rule::requiredIf($user->role === 'professional'), 'nullable', Rule::in(config('nationalities.values'))],
            'address_city' => [Rule::requiredIf($user->role === 'professional'), 'nullable', 'string', 'max:150'],
            'address_country' => [Rule::requiredIf($user->role === 'professional'), 'nullable', 'string', 'max:150'],
            'address_province' => [Rule::requiredIf($user->role === 'professional'), 'nullable', 'string', 'max:100'],
            'postal_code' => [Rule::requiredIf($user->role === 'professional'), 'nullable', 'string', 'max:20'],
            'street_address' => [Rule::requiredIf($user->role === 'professional'), 'nullable', 'string', 'max:255'],
            'residence_permit_document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'ata_certificate_document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        $profileFields = $this->professionalProfileFields($user, $input);

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input, $profileFields);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
                ...$profileFields,
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, mixed>  $input
     * @param  array<string, mixed>  $profileFields
     */
    protected function updateVerifiedUser(User $user, array $input, array $profileFields): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
            ...$profileFields,
        ])->save();

        $user->sendEmailVerificationNotification();
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function professionalProfileFields(User $user, array $input): array
    {
        if ($user->role !== 'professional') {
            return [];
        }

        $fields = [
            'nationality' => $input['nationality'] ?? null,
            'address_city' => $input['address_city'] ?? null,
            'address_country' => $input['address_country'] ?? null,
            'address_province' => $input['address_province'] ?? null,
            'postal_code' => $input['postal_code'] ?? null,
            'street_address' => $input['street_address'] ?? null,
            'residence' => $input['address_city'] ?? $user->residence,
        ];

        if (($input['residence_permit_document'] ?? null) instanceof UploadedFile) {
            $fields['residence_permit_path'] = $input['residence_permit_document']->store('professional-documents/residence-permits', 'public');
        }

        if (($input['ata_certificate_document'] ?? null) instanceof UploadedFile) {
            $fields['ata_certificate_path'] = $input['ata_certificate_document']->store('professional-documents/ata-certificates', 'public');
        }

        return $fields;
    }
}
