<?php

namespace App\Http\Controllers;

use App\Models\Guest;

class DashboardController extends Controller
{
    public function index()
    {
        $totalGuests = Guest::count();

        $confirmed = Guest::where(
            'rsvp_status',
            'confirmed'
        )->count();

        $declined = Guest::where(
            'rsvp_status',
            'declined'
        )->count();

        $arrived = Guest::where(
            'checkin_status',
            'arrived'
        )->count();

        $totalPeople = Guest::sum(
            'invitation_count'
        );

        $arrivedPeople = Guest::where(
            'checkin_status',
            'arrived'
        )->sum('invitation_count');

        return view(
            'admin.dashboard',
            compact(
                'totalGuests',
                'confirmed',
                'declined',
                'arrived',
                'totalPeople',
                'arrivedPeople'
            )
        );
    }
}