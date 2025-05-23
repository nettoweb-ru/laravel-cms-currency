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
        $groups = [];
        foreach (DB::table('cms__navigation_groups')->get() as $item) {
            $groups[$item->name] = $item->id;
        }

        DB::table(self::TABLE)->insert([
            ['group_id' => $groups['main.navigation_group_settings'], 'is_system' => '1', 'sort' => 20, 'name' => 'main.list_currency', 'url' => 'admin.currency.index', 'highlight' => '["admin.currency.edit", "admin.currency.create"]'],
        ]);

        $permissions = [];
        foreach (DB::table('cms__permissions')->get() as $item) {
            $permissions[$item->slug] = $item->id;
        }

        $items = [];
        foreach (DB::table('cms__navigation')->get() as $item) {
            $items[$item->url] = $item->id;
        }

        DB::table('cms__navigation__permissions')->insert([
            ['object_id' => $items['admin.currency.index'], 'related_id' => $permissions['admin-currencies']],
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
