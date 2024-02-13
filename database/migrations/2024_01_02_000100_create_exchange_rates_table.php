<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLE = 'cms__exchange_rates';
    private const UNSIGNED_DECIMAL = [
        'value',
    ];

    /**
     * @return void
     */
    public function up(): void
    {
        $tableName = self::TABLE;

        Schema::create($tableName, function (Blueprint $table) {
            $table->unsignedBigInteger('source_id');
            $table->unsignedBigInteger('target_id');
            $table->decimal('value', 10, 4)->nullable()->default(0);
            $table->foreign('source_id')->references('id')->on('cms__currencies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('target_id')->references('id')->on('cms__currencies')->onDelete('cascade')->onUpdate('cascade');
            $table->primary(['source_id', 'target_id']);
        });

        foreach (self::UNSIGNED_DECIMAL as $item) {
            DB::statement("ALTER TABLE `{$tableName}` ADD CONSTRAINT `{$tableName}_{$item}_check` CHECK (`{$item}` >= 0)");
        }
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(self::TABLE);
    }
};
