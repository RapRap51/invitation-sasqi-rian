<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Wish;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function show(Request $request, string $code)
    {
        $guest = Guest::where(
            'unique_code',
            'RAFIINA-' . strtoupper($code)
        )->firstOrFail();

        $wishes = Wish::latest()
            ->paginate(10);

        return view(
            'invitation',
            compact('guest', 'wishes')
        );
    }

    public function wishes(string $code)
    {
        Guest::where(
            'unique_code',
            'RAFIINA-' . strtoupper($code)
        )->firstOrFail();

        $wishes = Wish::latest()
            ->paginate(10);

        return response()->json([
            'data' => $wishes->map(function ($wish) {
                return [
                    'name' => $wish->name,
                    'message' => $wish->message,
                    'initial' => mb_strtoupper(
                        mb_substr($wish->name, 0, 1)
                    ),
                    'time' => $wish->created_at
                        ->timezone('Asia/Jakarta')
                        ->diffForHumans(),
                ];
            }),

            'next_page' => $wishes->currentPage()
                < $wishes->lastPage()
                ? $wishes->currentPage() + 1
                : null,
        ]);
    }
}