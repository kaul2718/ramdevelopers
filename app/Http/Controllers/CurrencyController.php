<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Http\Requests\CurrencyRequest;
use Inertia\Inertia;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::orderBy('curr_name')->paginate(10);

        return Inertia::render('Currency/Index', [
            'currencies' => $currencies
        ]);
    }

    public function create()
    {
        return Inertia::render('Currency/Create');
    }

    public function store(CurrencyRequest $request)
    {
        $currency = Currency::create($request->validated());

        return redirect()->route('currency.index')
            ->with('success', 'Moneda creada correctamente.');
    }

    public function edit(Currency $currency)
    {
        return Inertia::render('Currency/Edit', [
            'currency' => $currency
        ]);
    }

    public function update(CurrencyRequest $request, Currency $currency)
    {
        $currency->update($request->validated());

        return redirect()->route('currency.index')
            ->with('success', 'Moneda actualizada correctamente.');
    }

    public function destroy(Currency $currency)
    {
        $currency->delete();

        return redirect()->route('currency.index')
            ->with('success', 'Moneda eliminada correctamente.');
    }
}
