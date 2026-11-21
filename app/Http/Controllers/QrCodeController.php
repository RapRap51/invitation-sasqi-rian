<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    public function show(Guest $guest)
    {
        return view(
            'admin.guests.qr',
            compact('guest')
        );
    }

    public function download(Guest $guest)
    {
        $url = route(
            'invitation.show',
            [
                'code' => str_replace(
                'RAFIINA-',
                '',
                $guest->unique_code
        ),
            ]
        );

        $qr = QrCode::format('svg')
        ->size(800)
        ->margin(2)
        ->generate($url);

    return response($qr)
        ->header('Content-Type', 'image/svg+xml')
        ->header(
            'Content-Disposition',
            'attachment; filename="QR-' .
            $guest->unique_code .
            '.svg"'
        );
    }
}