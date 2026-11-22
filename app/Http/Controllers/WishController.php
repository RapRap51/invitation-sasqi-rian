<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Wish;
use Illuminate\Http\Request;

class WishController extends Controller
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

            'ucapan' => [
                'required',
                'string',
                'max:2000',
            ],
        ]);

        Wish::create([
            'guest_id' => $guest->id,
            'name' => $validated['nama'],
            'message' => $validated['ucapan'],
        ]);

        return redirect()
        ->route('invitation.show', [
            'code' => $code,
            'opened' => 1,
        ])
        ->with(
            'wish_success',
            'Terima kasih atas ucapan dan doanya ❤️'
        );
        }
}