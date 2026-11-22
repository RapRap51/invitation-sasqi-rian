<?php

namespace App\Exports;

use App\Models\Guest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GuestsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Guest::with([
            'checkin.officer',
        ])
            ->latest()
            ->get()
            ->map(function (Guest $guest) {
                return [
                    'Nama' => $guest->name,

                    'No HP' => $guest->phone ?? '',

                    'Jumlah Undangan' =>
                        $guest->invitation_count,

                    'Kode Unik' =>
                        $guest->unique_code,

                    'Link Undangan' =>
                        route(
                            'invitation.show',
                            [
                                'code' => $guest->short_code,
                            ]
                        ),

                    'Status RSVP' =>
                        match ($guest->rsvp_status) {
                            'confirmed' => 'Hadir',
                            'declined' => 'Tidak Hadir',
                            default => 'Belum Mengisi',
                        },

                    'Status Check-in' =>
                        $guest->checkin_status === 'arrived'
                            ? 'Sudah Hadir'
                            : 'Belum Hadir',

                    'Waktu Check-in' =>
                        $guest->checkin?->checked_in_at
                            ? $guest->checkin->checked_in_at
                                ->timezone('Asia/Jakarta')
                                ->format('d-m-Y H:i:s')
                            : '',

                    'Petugas Check-in' =>
                        $guest->checkin?->officer?->name ?? '',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'No HP',
            'Jumlah Undangan',
            'Kode Unik',
            'Link Undangan',
            'Status RSVP',
            'Status Check-in',
            'Waktu Check-in',
            'Petugas Check-in',
        ];
    }
}