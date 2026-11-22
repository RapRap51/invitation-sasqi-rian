<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ganti awalan kode undangan lama RAFIINA- menjadi NT- (NodeTone).
     * Kode pendek di URL/QR tidak berubah, jadi link yang sudah dibagikan tetap berlaku.
     */
    public function up(): void
    {
        DB::table('guests')
            ->where('unique_code', 'like', 'RAFIINA-%')
            ->update([
                'unique_code' => DB::raw("REPLACE(unique_code, 'RAFIINA-', 'NT-')"),
            ]);
    }

    public function down(): void
    {
        DB::table('guests')
            ->where('unique_code', 'like', 'NT-%')
            ->update([
                'unique_code' => DB::raw("REPLACE(unique_code, 'NT-', 'RAFIINA-')"),
            ]);
    }
};
