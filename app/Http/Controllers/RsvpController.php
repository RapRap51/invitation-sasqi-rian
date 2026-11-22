<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Rsvp;
use Illuminate\Http\Request;

class RsvpController extends Controller
{
    public function store(Request $request, string $code)
    {
        $guest = Guest::where(
            'unique_code',
            Guest::fullCode($code)
        )->firstOrFail();

        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'kehadiran' => [
                'required',
                'in:Hadir,Tidak Hadir',
            ],

            'jumlah' => [
                'required',
                'integer',
                'min:1',
                'max:' . $guest->invitation_count,
            ],
        ]);

        $quantity = $validated['kehadiran'] === 'Tidak Hadir'
            ? 0
            : $validated['jumlah'];

        Rsvp::updateOrCreate(
            [
                'guest_id' => $guest->id,
            ],
            [
                'attendance' => $validated['kehadiran'],
                'quantity' => $quantity,
            ]
        );

        $guest->update([
            'rsvp_status' =>
                $validated['kehadiran'] === 'Hadir'
                    ? 'confirmed'
                    : 'declined',
        ]);

        return redirect(
            route('invitation.show', [
                'code' => $code,
                'opened' => 1,
            ]) . '#guest-qr'
        )->with(
            'success',
            'Konfirmasi kehadiran berhasil dikirim.'
        );
    }
}