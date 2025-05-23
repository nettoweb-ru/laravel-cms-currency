<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema};

return new class extends Migration
{
    private const TABLE = 'cms_currency__exchange_rates';
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
            $table->smallInteger('base')->unsigned()->default(1);
            $table->decimal('value', 10, 4)->nullable()->default(0);
            $table->dateTime('date');
            $table->string('provider');
            $table->foreign('source_id')->references('id')->on('cms_currency__currencies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('target_id')->references('id')->on('cms_currency__currencies')->onDelete('cascade')->onUpdate('cascade');
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
        if (Schema::hasTable(self::TABLE)) {
            Schema::table(self::TABLE, function(Blueprint $table) {
                $table->dropForeign(self::TABLE.'_target_id_foreign');
                $table->dropForeign(self::TABLE.'_source_id_foreign');
            });

            Schema::drop(self::TABLE);
        }
    }
};
