<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetCurrency extends Currency
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $data = [];
        foreach ($this->getCurrencies() as $currency) {
            $data[] = $currency['char_code'];
        }
        return JsonResponse::create($data);
    }
}
