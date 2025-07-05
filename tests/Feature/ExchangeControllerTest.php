<?php

namespace Tests\Unit;

use Tests\TestCase;

class ExchangeControllerTest extends TestCase
{
    /**
     * Test the Exchange Controller that returns the full list of exchange rates
     */
    public function test_index_returns_successful_response_with_rates(): void
    {
        $response = $this->get('/api/rates');

        $response->assertStatus(200);

        $this->assertNotEmpty($response->json());
    }

    /**
     * Test the store method saves exchange rates
     */
    public function test_store_returns_success_message(): void
    {
        $response = $this->post('/api/store-rates');

        $response->assertStatus(200);

        $response->assertJson(['message' => 'Ecxhange rates stored.']);
    }

    /**
     * Test the storedRates method returns paginated results
     */
    public function test_stored_rates_returns_paginated_results(): void
    {
        $response = $this->get('/api/stored-rates?page=1');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'current_page',
            'per_page',
            'total'
        ]);
    }

    /**
     * Test the storedRates method with filters
     */
    public function test_stored_rates_with_filters(): void
    {
        $response = $this->get('/api/stored-rates?currency_to=EUR&min_rate=0.5&max_rate=1.5&retrieved_at=2022-01-01');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data'
        ]);
    }

    /**
     * Test the specificRate method returns a rate when found
     */
    public function test_specific_rate_returns_rate_when_found(): void
    {
        $response = $this->get('/api/stored-rates/1');

        $response->assertStatus(200);

        $response->assertJson([]);
    }
}
