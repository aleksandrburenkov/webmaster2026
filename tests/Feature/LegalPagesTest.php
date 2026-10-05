<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegalPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_privacy_policy_page_returns_a_successful_response(): void
    {
        $response = $this->get('/privacy-policy');

        $response->assertStatus(200);
    }

    public function test_offer_page_returns_a_successful_response(): void
    {
        $response = $this->get('/offer');

        $response->assertStatus(200);
    }
}
