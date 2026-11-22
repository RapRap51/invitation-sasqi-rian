<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Checkin;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function scanner()
    {
        return view('admin.checkin.scanner');
    }

    public function find(Request $request)
    {
        $request->validate([
            'token' => [
                'required',
                'string',
            ],
        ]);

        // QR undangan berisi URL dengan kode pendek (mis. /undangan/AB12CD);
        // tetap dukung qr_token untuk QR lama yang berisi token langsung.
        $guest = Guest::where('unique_code', Guest::fullCode($request->token))
            ->orWhere('qr_token', $request->token)
            ->first();

        if (!$guest) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'guest' => [
                'id' => $guest->id,
                'name' => $guest->name,
                'invitation_count' => $guest->invitation_count,
                'rsvp_status' => $guest->rsvp_status,
                'checkin_status' => $guest->checkin_status,
                'unique_code' => $guest->unique_code,
            ],
        ]);
    }

    public function confirm(Request $request, Guest $guest)
    {
        if ($guest->checkin_status === 'arrived') {
            return back()->with(
                'error',
                'Tamu ini sudah melakukan check-in.'
            );
        }

        Checkin::create([
            'guest_id' => $guest->id,
            'checked_in_by' => auth()->id(),
            'checked_in_at' => now(),
        ]);

        $guest->update([
            'checkin_status' => 'arrived',
        ]);

        return back()->with(
            'success',
            'Check-in berhasil untuk ' . $guest->name
        );
    }

        public function manual()
    {
        return view('admin.checkin.manual');
    }

    public function searchManual(Request $request)
    {
        $request->validate([
            'search' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $search = trim($request->search);

        $guests = Guest::query()
            ->where('name', 'like', '%' . $search . '%')
            ->orWhere('phone', 'like', '%' . $search . '%')
            ->orderBy('name')
            ->limit(20)
            ->get();

        return view(
            'admin.checkin.manual',
            compact('guests', 'search')
        );
    }

}