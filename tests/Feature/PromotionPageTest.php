<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromotionPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_promotion_page_returns_a_successful_response(): void
    {
        $this->get('/promotion')->assertStatus(200);
    }

    public function test_promotion_page_exposes_service_faq_and_breadcrumb_structured_data(): void
    {
        $response = $this->get('/promotion');

        $response->assertSee('FAQPage', false);
        $response->assertSee('"@type":"Service"', false);
        $response->assertSee('"@type":"BreadcrumbList"', false);
        $response->assertSee('rel="canonical"', false);
        $response->assertSee('Комплексное продвижение сайтов', false);
    }
}
