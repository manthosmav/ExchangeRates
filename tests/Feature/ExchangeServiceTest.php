<?php

namespace Tests\Unit;

use App\Services\ExchangeService;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class ExchangeServiceTest extends TestCase
{
    private $url = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';

    /**
     * Test fetchExchangeRates returns array of rates
     */
    public function test_fetch_exchange_rates_returns_array(): void
    {
        Http::fake([
            $this->url => Http::response($this->getMockXmlResponse(), 200)
        ]);

        $service = new ExchangeService();
        $rates = $service->fetchExchangeRates();

        $this->assertIsArray($rates);
        $this->assertArrayHasKey('USD', $rates);
        $this->assertArrayHasKey('GBP', $rates);
    }

    /**
     * Test fetchExchangeRates handles errors gracefully
     */
    public function test_fetch_exchange_rates_handles_errors(): void
    {
        Http::fake([
            $this->url => Http::response('invalid xml', 200)
        ]);

        $service = new ExchangeService();
        $rates = $service->fetchExchangeRates();

        $this->assertIsArray($rates);
        $this->assertNotEmpty($rates);
    }

    /**
     * Test storeExchangeRates saves data to database
     */
    public function test_store_exchange_rates_saves_to_database(): void
    {
        Http::fake([
            $this->url => Http::response($this->getMockXmlResponse(), 200)
        ]);

        $service = new ExchangeService();
        $service->storeExchangeRates();

        $this->assertDatabaseHas('exchange_rates', [
            'currency_from' => 'EUR',
            'currency_to' => 'USD'
        ]);
    }

    /**
     * Mock XML response for the tests
     */
    private function getMockXmlResponse(): string
    {
        return '<gesmes:Envelope xmlns:gesmes="http://www.gesmes.org/xml/2002-08-01" xmlns="http://www.ecb.int/vocabulary/2002-08-01/eurofxref">
                    <gesmes:subject>Reference rates</gesmes:subject>
                    <gesmes:Sender>
                    <gesmes:name>European Central Bank</gesmes:name>
                    </gesmes:Sender>
                    <Cube>
                    <Cube time="2025-07-03">
                    <Cube currency="USD" rate="1.1782"/>
                    <Cube currency="SGD" rate="1.5003"/>
                    <Cube currency="THB" rate="38.162"/>
                    <Cube currency="ZAR" rate="20.6505"/>
                    </Cube>
                    </Cube>
                </gesmes:Envelope>';
    }
}
