<?php

namespace Netto\Models;

use Netto\Models\Abstract\Model as BaseModel;
use Netto\Traits\{HasDefaultAttribute, IsMultiLingual};

class Currency extends BaseModel
{
    use HasDefaultAttribute, IsMultiLingual;

    public $timestamps = false;
    public $table = 'cms_currency__currencies';

    public array $multiLingual = [
        'name',
    ];

    public string $multiLingualClass = CurrencyLang::class;

    protected $casts = [
        'is_default' => 'boolean',
    ];

    protected $attributes = [
        'is_default' => '0',
        'sort' => 0,
    ];
}
