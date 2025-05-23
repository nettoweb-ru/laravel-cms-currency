<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const TABLE_PERMISSIONS = 'cms__permissions';

    private const CODES = [
        'admin-currencies' => 'main.list_currency',
    ];

    /**
     * @return void
     */
    public function up(): void
    {
        foreach (self::CODES as $key => $value) {
            DB::table(self::TABLE_PERMISSIONS)->insert([
                'name' => $value,
                'slug' => $key,
                'is_system' => '1',
            ]);
        }

        $permissions = [];
        foreach (DB::table(self::TABLE_PERMISSIONS)->get() as $item) {
            $permissions[$item->slug] = $item->id;
        }

        $roles = [];
        foreach (DB::table('cms__roles')->get() as $item) {
            $roles[$item->slug] = $item->id;
        }

        DB::table('cms__roles__permissions')->insert([
            ['object_id' => $roles['developer'], 'related_id' => $permissions['admin-currencies']],
        ]);
    }

    /**
     * @return void
     */
    public function down(): void
    {
        DB::table(self::TABLE_PERMISSIONS)->whereIn('slug', array_keys(self::CODES))->delete();
    }
};
