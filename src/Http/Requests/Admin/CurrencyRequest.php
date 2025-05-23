<?php
namespace Netto\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Netto\Models\Currency;
use Netto\Rules\UniqueDefaultEntity;

class CurrencyRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return array_merge(
            get_rules_multilingual([
                'name' => ['required', 'string', 'max:255'],
            ]),
            [
                'sort' => ['integer', 'min:0', 'max:255'],
                'slug' => ['required', 'string', 'size:3', 'uppercase', 'alpha:ascii', Rule::unique(Currency::class, 'slug')->ignore($this->get('id'))],
                'is_default' => ['in:1,0', new UniqueDefaultEntity(Currency::class, $this->get('id'), $this->get('is_default'))],
            ]
        );
    }
}
