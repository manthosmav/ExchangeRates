<?php


namespace App\Services;

use App\Models\ExchangeRate;
use Exception;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Support\Facades\Http;
use SimpleXMLElement;

use GuzzleHttp\Client;

class ExchangeService
{
    public string $url = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';


    public function fetchExchangeRates(): array
    {
        try {

            // Adding Guzzle HTTP Client to solve the SSL error (cURL error 60: SSL certificate problem: self signed certificate in certificate chain laravel)
            $http = new Client([
                'base_uri' => $this->url,
                'verify' => false,
            ]);

            // then instead of using Http::get($this->url), i use Http::setClient($http)->get($this->url)
            $xml = new SimpleXMLElement(Http::setClient($http)->get($this->url));
            $attributes = json_decode(json_encode($xml), true);

            $attributes = $attributes['Cube']['Cube']['Cube'];

            $rates = [];
            foreach ($attributes as $attribute) {
                foreach ($attribute as $key => $value) {
                    if ($key === '@attributes') {
                        $rates[$value['currency']] = $value['rate'];
                    }
                }
            }

            return $rates;
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
    public function storeExchangeRates(): void
    {
        $exchangeRates = $this->fetchExchangeRates();
        if (isset($exchangeRates['error'])) {
            return;
        }
        foreach ($exchangeRates as $currencyTo => $rate) {
            ExchangeRate::create(
                [
                    'currency_from' => 'EUR',
                    'currency_to' => $currencyTo,
                    'rate' => $rate,
                    'retrieved_at' => now(),
                ]
            );
        }
    }
}
