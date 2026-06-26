@php
    $selectedBusiness = old('user_id', $jobPosting->user_id);
@endphp

<div>
    <x-label for="user_id" value="Business proprietario" />
    <select id="user_id" name="user_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">Seleziona business</option>
        @foreach ($businessUsers as $businessUser)
            <option value="{{ $businessUser->id }}" @selected((int) $selectedBusiness === $businessUser->id)>
                {{ $businessUser->name }} - {{ $businessUser->email }}
            </option>
        @endforeach
    </select>
</div>

@include('job-postings._form', ['jobPosting' => $jobPosting])
