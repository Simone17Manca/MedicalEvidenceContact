<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" id="photo" class="hidden"
                            wire:model.live="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-label for="photo" value="{{ __('Photo') }}" />

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full size-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full size-20 bg-cover bg-no-repeat bg-center"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
                </x-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </x-secondary-button>
                @endif

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Name') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" required autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required autocomplete="username" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="text-sm mt-2">
                    {{ __('Your email address is unverified.') }}

                    <button type="button" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 font-medium text-sm text-green-600">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>

        @if ($this->user->role === 'professional')
            <div class="col-span-6 border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900">Dati professionista</h3>
                <p class="mt-1 text-sm text-gray-600">Completa nazionalita, indirizzo e documenti professionali.</p>
            </div>

            <div class="col-span-6 sm:col-span-3">
                <x-label for="nationality" value="Nazionalita" />
                <select id="nationality" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" wire:model.live="state.nationality" required autocomplete="country-name">
                    @foreach (config('nationalities.values') as $nationality)
                        <option value="{{ $nationality }}">{{ $nationality }}</option>
                    @endforeach
                </select>
                <x-input-error for="nationality" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <x-label for="address_country" value="Paese" />
                <x-input id="address_country" type="text" class="mt-1 block w-full" wire:model="state.address_country" required autocomplete="country-name" />
                <x-input-error for="address_country" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <x-label for="address_city" value="Citta" />
                <x-input id="address_city" type="text" class="mt-1 block w-full" wire:model="state.address_city" required autocomplete="address-level2" />
                <x-input-error for="address_city" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <x-label for="address_province" value="Provincia" />
                <x-input id="address_province" type="text" class="mt-1 block w-full" wire:model="state.address_province" required autocomplete="address-level1" />
                <x-input-error for="address_province" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-2">
                <x-label for="postal_code" value="CAP" />
                <x-input id="postal_code" type="text" class="mt-1 block w-full" wire:model="state.postal_code" required autocomplete="postal-code" />
                <x-input-error for="postal_code" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <x-label for="street_address" value="Indirizzo" />
                <x-input id="street_address" type="text" class="mt-1 block w-full" wire:model="state.street_address" required autocomplete="street-address" />
                <x-input-error for="street_address" class="mt-2" />
            </div>

            <div class="col-span-6 border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900">Documenti</h3>
            </div>

            @php
                $nationality = strtolower(trim($this->user->nationality ?? ''));
                $isItalian = in_array($nationality, ['italiana', 'italiano', 'italia', 'italian'], true);
            @endphp

            <div class="col-span-6 sm:col-span-4" x-data="{ nationality: @entangle('state.nationality').live }" x-show="! ['italiana', 'italiano', 'italia', 'italian'].includes((nationality || '').toLowerCase().trim())">
                <x-label for="residence_permit_document" value="Permesso di soggiorno" />
                <input id="residence_permit_document" type="file" class="mt-1 block w-full text-sm text-gray-700" wire:model="state.residence_permit_document" accept=".pdf,.jpg,.jpeg,.png" />
                <p class="mt-1 text-sm text-gray-500">Visibile solo per nazionalita non italiana. Formati: PDF, JPG, PNG.</p>
                @if ($this->user->residence_permit_path && ! $isItalian)
                    <p class="mt-2 text-sm font-medium text-green-700">Documento gia caricato.</p>
                @endif
                <x-input-error for="residence_permit_document" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <x-label for="ata_certificate_document" value="Attestato ATA" />
                <input id="ata_certificate_document" type="file" class="mt-1 block w-full text-sm text-gray-700" wire:model="state.ata_certificate_document" accept=".pdf,.jpg,.jpeg,.png" />
                <p class="mt-1 text-sm text-gray-500">Formati: PDF, JPG, PNG.</p>
                @if ($this->user->ata_certificate_path)
                    <p class="mt-2 text-sm font-medium text-green-700">Attestato gia caricato.</p>
                @endif
                <x-input-error for="ata_certificate_document" class="mt-2" />
            </div>
        @endif
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>
