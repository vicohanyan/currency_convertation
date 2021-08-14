<?php

namespace App\Http\Controllers;

use SimpleXMLElement;

class Currency extends Controller
{
    private const CURRENCY_SERVER = "http://www.cbr.ru/scripts/XML_daily.asp";

    protected function getCurrencies(): array
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', self::CURRENCY_SERVER);
        $xml = new SimpleXMLElement($response->getBody());
        $data = [];
        /**
         * @var SimpleXMLElement $element
         * @var SimpleXMLElement $child
         */
        foreach ($xml as $element) {
            $data[] = [
                'char_code' => $element->CharCode->__tostring(),
                'value'     => floatval(str_replace(',', '.', $element->Value->__tostring())),
                'nominal'   => intval($element->Nominal->__tostring()),
            ];
        }
        return $data;
    }
}
