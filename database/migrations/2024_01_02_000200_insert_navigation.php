<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const TABLE = 'cms__navigation';

    /**
     * @return void
     */
    public function up(): void
    {
        DB::table(self::TABLE)->insert([
            ['group_id' => 20, 'sort' => 30, 'name' => 'cms-currency::main.list', 'url' => 'admin.currency.index', 'highlight' => '["admin.currency.index", "admin.currency.edit", "admin.currency.create"]'],
        ]);
    }

    /**
     * @return void
     */
    public function down(): void
    {
        DB::table(self::TABLE)->where('url', 'admin.currency.index')->delete();
    }
};
