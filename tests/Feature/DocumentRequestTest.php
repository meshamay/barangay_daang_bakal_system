<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\DocumentRequest;
use Illuminate\Support\Str;

class DocumentRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_see_their_document_requests_with_tracking_number()
    {
        // 1. Create and authenticate a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // 2. Create a document request for the user
        $documentRequest = DocumentRequest::factory()->create([
            'resident_id' => $user->id,
            'tracking_number' => 'DOC-' . strtoupper(Str::random(8)),
        ]);

        // 3. Visit the document requests page
        $response = $this->get(route('user.document-requests.index'));

        // 4. Assert that the page displays the tracking number
        $response->assertStatus(200);
        $response->assertSee($documentRequest->tracking_number);
    }

    public function test_user_sees_tracking_number_after_submitting_request()
    {
        // 1. Create and authenticate a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // 2. Simulate a document request submission
        $documentType = 'Barangay Certificate';
        $purpose = 'For school application';
        $response = $this->post(route('user.document.store'), [
            'document_type' => $documentType,
            'purpose' => $purpose,
        ]);

        // 3. Follow the redirect
        $response->assertStatus(302);
        $response->assertRedirect(route('user.document-requests.index'));
        $response = $this->get(route('user.document-requests.index'));

        // 4. Get the latest document request from the database
        $latestRequest = DocumentRequest::where('resident_id', $user->id)->latest()->first();

        // 5. Assert that the tracking number is visible in the response
        $response->assertStatus(200);
        $response->assertSee($latestRequest->tracking_number);
    }
}