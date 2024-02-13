<?php

namespace Netto\Services\RateProviders;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Netto\Models\ExchangeRate;
use Netto\Services\CurrencyService;

class CbrRussia
{
    private const PRECISION = 4;
    private const URL = 'https://www.cbr.ru/scripts/XML_daily.asp';

    /**
     * @param array $list
     * @return void
     * @throws RequestException
     */
    public function load(array $list): void
    {
        if (count($list) < 2) {
            return;
        }

        $response = Http::get(self::URL);
        $response->throwUnlessStatus(200);

        $result = parse_xml($response->body());

        $data = [];
        foreach ($result['Valute'] as $item) {
            if (!array_key_exists($item['CharCode'], $list)) {
                continue;
            }

            $data[$item['CharCode']] = [
                'nominal' => (int) $item['Nominal'],
                'value' => (float) str_replace(',', '.', $item['Value']),
            ];
        }

        $defaultId = CurrencyService::getDefaultId();

        $rates = [];
        foreach ($data as $code => $item) {
            $targetId = $list[$code];

            $rates[$defaultId][$targetId] = round($item['value'] / $item['nominal'], self::PRECISION);
            $rates[$targetId][$defaultId] = round((1 / $item['value']) * $item['nominal'], self::PRECISION);
        }

        $codes = array_keys($data);

        foreach ($codes as $from) {
            $sourceId = $list[$from];
            foreach ($codes as $to) {
                if ($from == $to) {
                    continue;
                }

                $targetId = $list[$to];

                $rates[$sourceId][$targetId] = round($rates[$sourceId][$defaultId] * $rates[$defaultId][$targetId], self::PRECISION);
                $rates[$targetId][$sourceId] = round($rates[$targetId][$defaultId] * $rates[$defaultId][$sourceId], self::PRECISION);
            }
        }

        DB::table((new ExchangeRate())->getTable())->delete();

        foreach ($rates as $sourceId => $rate) {
            foreach ($rate as $targetId => $value) {
                $model = new ExchangeRate();

                $model->setAttribute('source_id', $sourceId);
                $model->setAttribute('target_id', $targetId);
                $model->setAttribute('value', $value);

                $model->save();
            }
        }
    }
}
