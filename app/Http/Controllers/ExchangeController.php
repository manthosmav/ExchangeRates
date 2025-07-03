<?php 

namespace App\Http\Controllers;

use App\Services\ExchangeService;

class ExchangeController extends Controller
{
    public function index(ExchangeService $exchangeService)
    {
        $rates = $exchangeService->fetchExchangeRates();

        return response()->json($rates);
    }

    public function store(ExchangeService $exchangeService)
    {
        $exchangeService->storeExchangeRates();

        return response()->json(['message' => 'Ecxhange rates stored.']);
    }
}