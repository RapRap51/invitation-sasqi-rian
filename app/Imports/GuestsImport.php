<?php

namespace App\Imports;

use App\Models\Guest;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GuestsImport implements ToModel, WithHeadingRow
{
    /**
     * Baris pertama Excel digunakan sebagai nama kolom.
     *
     * @param array $row
     */
    public function model(array $row)
    {
        // Lewati baris kosong
        if (empty($row['nama'])) {
            return null;
        }

        $name = trim($row['nama']);

        $phone = !empty($row['no_hp'])
            ? trim((string) $row['no_hp'])
            : null;

        $invitationCount = !empty($row['jumlah_undangan'])
            ? (int) $row['jumlah_undangan']
            : 1;

        /*
         * Cegah data tamu yang sama ter-import berulang.
         *
         * Untuk sementara kita gunakan kombinasi nama + nomor HP.
         */
        $existingGuest = Guest::query()
            ->where('name', $name)
            ->when(
                $phone,
                fn ($query) => $query->where('phone', $phone)
            )
            ->first();

        // Kalau sudah ada, jangan buat tamu baru.
        if ($existingGuest) {
            return null;
        }

        // Generate kode unik undangan
        do {
            $uniqueCode = Guest::fullCode(Str::random(6));
        } while (
            Guest::where('unique_code', $uniqueCode)->exists()
        );

        // Generate token QR
        do {
            $qrToken = Str::random(64);
        } while (
            Guest::where('qr_token', $qrToken)->exists()
        );

        return new Guest([
            'name' => $name,
            'phone' => $phone,
            'invitation_count' => max(1, $invitationCount),

            'unique_code' => $uniqueCode,
            'qr_token' => $qrToken,

            'rsvp_status' => 'pending',
            'checkin_status' => 'not_arrived',
        ]);
    }
}