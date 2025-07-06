<?php 

namespace App\Http\Controllers;

use App\Models\ExchangeRate;
use App\Services\ExchangeService;
use Illuminate\Http\Request;

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

        return response()->json(['message' => 'Exchange rates stored.']);
    }

    public function storedRates(Request $request) { 
        $query = ExchangeRate::query();

        if ($request->has('currency_to')) {
            $query->where('currency_to', $request->input('currency_to'));
        }

        if ($request->has('retrieved_at')) {
            $query->where('retrieved_at', 'like', $request->input('retrieved_at') . '%');
        }

        if ($request->has('min_rate')) {
            $query->where('rate', '>=', $request->input('min_rate'));
        }
        
        if ($request->has('max_rate')) {
            $query->where('rate', '<=', $request->input('max_rate'));
        }


        $exchangeRates = $query->paginate($request->input('per_page', 10));

        return response()->json($exchangeRates);
    }

    public function specificRate($id) {
        $exchangeRate = ExchangeRate::find($id);
        
        if (!$exchangeRate) {
            return response()->json(['message' => 'Sorry, there is no corresponding record to this id'], 404);
        }

        return response()->json($exchangeRate);
    }
}