<?php

namespace Tests\Feature;

use App\Models\ProfessionalDocument;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Services\ProfessionalDocumentStorage;
use Tests\TestCase;

class ProfessionalDocumentStorageTest extends TestCase
{
    use RefreshDatabase;

    public function test_professional_document_uploads_are_saved_locally_and_indexed_as_json(): void
    {
        Storage::fake('professional_documents');

        $professional = User::factory()->create([
            'role' => 'professional',
            'nationality' => 'Argentina',
        ]);

        $this->actingAs($professional)
            ->post(route('professional-documents.store'), [
                'ata_certificate_document' => UploadedFile::fake()->create('ata.pdf', 120, 'application/pdf'),
                'residence_permit_document' => UploadedFile::fake()->create('permesso.pdf', 120, 'application/pdf'),
            ])
            ->assertRedirect(route('dashboard', absolute: false));

        $documentRecord = ProfessionalDocument::query()->whereBelongsTo($professional)->firstOrFail();
        $documents = $documentRecord->documents;

        $this->assertArrayHasKey('ata_certificate', $documents);
        $this->assertArrayHasKey('residence_permit', $documents);
        $this->assertSame('professional_documents', $documents['ata_certificate']['disk']);
        $this->assertSame('ata.pdf', $documents['ata_certificate']['original_name']);
        $this->assertStringContainsString('professional-documents/ata-certificates/'.$professional->id.'/', $documents['ata_certificate']['path']);
        $this->assertNull($documents['ata_certificate']['url']);

        Storage::disk('professional_documents')->assertExists($documents['ata_certificate']['path']);
        Storage::disk('professional_documents')->assertExists($documents['residence_permit']['path']);

        $professional->refresh();
        $this->assertSame($documents['ata_certificate']['path'], $professional->ata_certificate_path);
        $this->assertSame($documents['residence_permit']['path'], $professional->residence_permit_path);
    }


    public function test_professional_document_storage_falls_back_to_uploaded_file_pathname(): void
    {
        Storage::fake('professional_documents');

        $professional = User::factory()->create([
            'role' => 'professional',
            'nationality' => 'Italiana',
        ]);

        $source = tempnam(sys_get_temp_dir(), 'ata_');
        file_put_contents($source, 'ata certificate');

        $file = new class($source, 'ata.pdf', 'application/pdf', null, true) extends UploadedFile
        {
            public function getRealPath(): string|false
            {
                return false;
            }
        };

        app(ProfessionalDocumentStorage::class)->store($professional, [
            'ata_certificate_document' => $file,
        ]);

        $documents = ProfessionalDocument::query()->whereBelongsTo($professional)->firstOrFail()->documents;

        $this->assertSame('professional_documents', $documents['ata_certificate']['disk']);
        Storage::disk('professional_documents')->assertExists($documents['ata_certificate']['path']);
    }

    public function test_professional_document_json_is_updated_without_losing_existing_documents(): void
    {
        Storage::fake('professional_documents');

        $professional = User::factory()->create([
            'role' => 'professional',
            'nationality' => 'Argentina',
        ]);

        $this->actingAs($professional)->post(route('professional-documents.store'), [
            'ata_certificate_document' => UploadedFile::fake()->create('ata.pdf', 120, 'application/pdf'),
        ]);

        $this->actingAs($professional)->post(route('professional-documents.store'), [
            'residence_permit_document' => UploadedFile::fake()->create('permesso.pdf', 120, 'application/pdf'),
        ]);

        $documents = ProfessionalDocument::query()->whereBelongsTo($professional)->firstOrFail()->documents;

        $this->assertArrayHasKey('ata_certificate', $documents);
        $this->assertArrayHasKey('residence_permit', $documents);
        $this->assertDatabaseCount('professional_documents', 1);
    }
}