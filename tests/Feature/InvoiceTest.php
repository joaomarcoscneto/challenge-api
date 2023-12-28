<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_invoice()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            "number" => "123456774",
            "value" => 30.45,
            "issuance_date" => "2023-06-01",
            "sender_cnpj" => "57413601000143",
            "sender_name" => "Fulano",
            "transporter_cnpj" => "32118531000170",
            "transporter_name" => "Ciclano"
        ];

        $response = $this->postJson('/api/invoices', $data);
        $response->assertStatus(201);
    }

    public function test_index_returns_invoices_associated_with_authenticated_user()
    {
        $user = User::factory()->create();
        Invoice::factory()->count(3)->create(['user_id' => $user->id]);
        Invoice::factory()->count(2)->create();

        $this->actingAs($user);

        $response = $this->get('/api/invoices');

        $response->assertStatus(200);

        $response->assertJsonCount(3);
    }
    
    public function test_show_returns_specific_invoice_for_authenticated_user()
    {
        $user = User::factory()->create();
        $invoice = Invoice::factory()->create(['user_id' => $user->id]);
        Invoice::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/api/invoices/' . $invoice->id);

        $response->assertStatus(200);

        $response->assertJson([
            'invoice' => [
                'id' => $invoice->id,
            ]
        ]);
    }

    public function test_update_updates_specific_invoice_for_authenticated_user()
    {
        $user = User::factory()->create();
        $invoice = Invoice::factory()->create(['user_id' => $user->id]);
        Invoice::factory()->create();

        $this->actingAs($user);

        $newInvoiceData = [
            'number' => '987654321',
        ];

        $response = $this->put('/api/invoices/' . $invoice->id, $newInvoiceData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'number' => $newInvoiceData['number'],
        ]);
    }

    public function test_delete_invoice()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $invoice = Invoice::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson('/api/invoices/' . $invoice->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('invoices', ['id' => $invoice->id]);
    }

    public function test_user_cannot_access_other_user_invoice()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $this->actingAs($user1);

        $invoice = Invoice::factory()->create(['user_id' => $user2->id]);

        $responseShow = $this->getJson('/api/invoices/' . $invoice->id);
        $responseShow->assertStatus(403);

        $responseUpdate = $this->putJson('/api/invoices/' . $invoice->id, []);
        $responseUpdate->assertStatus(403);

        $responseDelete = $this->deleteJson('/api/invoices/' . $invoice->id);
        $responseDelete->assertStatus(403);
    }

    public function test_store_invoice_request_validation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'number' => '12345678',
            'value' => -10,
            'issuance_date' => now()->addDays(1)->format('Y-m-d'),
            'sender_cnpj' => '1234567890',
            'sender_name' => str_repeat('A', 101),
            'transporter_cnpj' => '9876543210',
            'transporter_name' => str_repeat('B', 101)
        ];

        $response = $this->postJson('/api/invoices', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'number', 'value', 'issuance_date', 'sender_cnpj', 'sender_name', 'transporter_cnpj', 'transporter_name'
            ]);
    }
}