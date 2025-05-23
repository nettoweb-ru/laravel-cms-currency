<?php
namespace Netto\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Currency $source
 * @property Currency $target
 */

class ExchangeRate extends BaseModel
{
    public $timestamps = false;
    public $table = 'cms_currency__exchange_rates';

    protected $attributes = [
        'value' => '0.0000',
    ];

    public $fillable = [
        'source_id',
        'target_id',
        'base',
        'value',
        'date',
        'provider',
    ];

    /**
     * @return BelongsTo
     */
    public function source(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'source_id');
    }

    /**
     * @return BelongsTo
     */
    public function target(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'target_id');
    }
}
