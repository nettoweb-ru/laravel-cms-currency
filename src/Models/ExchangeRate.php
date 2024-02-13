<?php
namespace Netto\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Currency $source
 * @property Currency $target
 */

class ExchangeRate extends Model
{
    public $timestamps = false;
    public $table = 'cms__exchange_rates';

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
