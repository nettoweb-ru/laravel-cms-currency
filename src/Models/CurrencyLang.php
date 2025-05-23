<?php

namespace Netto\Models;

use Netto\Models\Abstract\Pivot as BaseModel;

class CurrencyLang extends BaseModel
{
    public $timestamps = false;
    public $table = 'cms_currency__currencies__lang';

    protected string $parentClass = Currency::class;
}
