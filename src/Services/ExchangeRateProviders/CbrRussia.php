<?php

namespace Netto\Services\ExchangeRateProviders;

use Illuminate\Support\Facades\Http;
use Netto\Exceptions\NettoException;
use Netto\Models\Currency;

class CbrRussia extends Abstract\ExchangeRatesProvider
{
    private const DEFAULT = 'RUB';
    private const URL = 'https://www.cbr.ru/scripts/XML_daily.asp';

    /**
     * @param array $codes
     * @return array
     * @throws NettoException
     */
    protected function get(array $codes): array
    {
        if (!array_key_exists(self::DEFAULT, $codes)) {
            $model = new Currency();
            $model->setAttribute('name', self::DEFAULT);
            $model->setAttribute('slug', self::DEFAULT);
            $model->setAttribute('is_default', '1');
            $model->save();

            $codes[self::DEFAULT] = $model->getAttribute('id');
        }

        try {
            $response = Http::get(self::URL);
            $response->throwUnlessStatus(200);
        } catch (\Throwable $throwable) {
            throw new NettoException($throwable->getMessage());
        }

        $result = parse_xml($response->body());
        $date = date('Y-m-d', strtotime($result['@attributes']['Date']));

        $return = [];
        foreach ($result['Valute'] as $item) {
            if (array_key_exists($item['CharCode'], $codes)) {
                $return[] = [
                    'source_id' => $codes[$item['CharCode']],
                    'target_id' => $codes[self::DEFAULT],
                    'base' => (int) $item['Nominal'],
                    'value' => (float) str_replace(',', '.', $item['Value']),
                    'date' => $date,
                ];
            }
        }

        return $return;
    }
}
