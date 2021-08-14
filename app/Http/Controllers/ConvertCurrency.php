<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConvertCurrency extends Currency
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $from_cur = $request->get('from');
        $to_cur = $request->get('to');
        $user_amount = $request->get('amount');
        if(empty($from_cur) || empty($to_cur) || empty($user_amount)){
            return JsonResponse::create([], 500);
        }
        $from_cur_value = NULL;
        $from_cur_nominal = NULL;
        $to_cur_value = NULL;
        $to_cur_nominal = NULL;
        foreach ($this->getCurrencies() as $currency) {
            if ($currency['char_code'] == $from_cur) {
                $from_cur_value = $currency['value'];
                $from_cur_nominal = $currency['nominal'];
            }
            if ($currency['char_code'] == $to_cur) {
                $to_cur_value = $currency['value'];
                $to_cur_nominal = $currency['nominal'];
            }
        }
        if ($from_cur == 'RUB' && !empty($to_cur_value) && !empty($to_cur_nominal)) {
            $amount = round($this->fromRub($user_amount, $to_cur_value, $to_cur_nominal));
            return JsonResponse::create(["amount" => $amount]);
        }
        if ($to_cur == 'RUB' && !empty($from_cur_value) && !empty($from_cur_nominal)) {
            $amount = round($this->toRub($user_amount, $from_cur_value, $from_cur_nominal));
            return JsonResponse::create(["amount" => $amount]);
        }
        if (empty($from_cur_value) || empty($from_cur_nominal)
            || empty($to_cur_value) || empty($to_cur_nominal)
        ) {
            return JsonResponse::create([], 500);
        }
        $amount_in_rub = $this->toRub($user_amount, $from_cur_value, $from_cur_nominal);
        $amount = round($this->fromRub($amount_in_rub, $to_cur_value, $to_cur_nominal));
        return JsonResponse::create(["amount" => $amount]);
    }

    /**
     * convert currency from RUB
     * @param int $amount
     * @param float $cur_value
     * @param int $cur_nominal
     * @return float
     */
    protected function fromRub(int $amount, float $cur_value, int $cur_nominal): float
    {
        return ($amount * $cur_nominal) / $cur_value;
    }

    /**
     * convert currency to RUB
     * @param int $amount
     * @param float $cur_value
     * @param int $cur_nominal
     * @return float
     */
    protected function toRub(int $amount, float $cur_value, int $cur_nominal): float
    {
        return $amount * ($cur_value / $cur_nominal);
    }
}
