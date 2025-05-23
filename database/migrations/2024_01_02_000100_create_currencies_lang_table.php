<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema};

return new class extends Migration
{
    private const TABLE = 'cms_currency__currencies__lang';
    private const COLUMNS = [
        'object_id' => 'cms_currency__currencies',
        'lang_id' => 'cms__lang',
    ];

    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            foreach (self::COLUMNS as $columnName => $tableName) {
                $table->unsignedBigInteger($columnName);
                $table->foreign($columnName)->references('id')->on($tableName)->onDelete('cascade')->onUpdate('cascade');
            }
            $table->unique(array_keys(self::COLUMNS));
            $table->string('name')->nullable()->default(null);
        });

        $currency = DB::table('cms_currency__currencies')->where('slug', 'RUB')->select('id')->first();
        $lang = DB::table('cms__lang')->where('slug', 'ru')->select('id')->first();

        DB::table(self::TABLE)->insert([
            ['object_id' => $currency->id, 'lang_id' => $lang->id, 'name' => 'Рубль'],
        ]);
    }

    /**
     * @return void
     */
    public function down(): void
    {
        if (Schema::hasTable(self::TABLE)) {
            Schema::table(self::TABLE, function(Blueprint $table) {
                foreach (array_reverse(self::COLUMNS) as $columnName => $tableName) {
                    $table->dropForeign(self::TABLE.'_'.$columnName.'_foreign');
                }
            });

            Schema::drop(self::TABLE);
        }
    }
};
