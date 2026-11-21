<?php

namespace App\Http\Controllers;

use App\Exports\GuestsExport;
use App\Imports\GuestsImport;
use App\Exports\GuestTemplateExport;
use App\Models\Guest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class GuestController extends Controller
{
    public function index()
    {
        $guests = Guest::latest()->paginate(20);

        return view('admin.guests.index', compact('guests'));
    }

    public function importForm()
    {
        return view('admin.import-guests');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:5120',
            ],
        ]);

        Excel::import(
            new GuestsImport,
            $request->file('file')
        );

        return redirect()
            ->route('admin.guests.index')
            ->with('success', 'Data tamu berhasil diimport.');
    }

    public function export()
    {
        return Excel::download(
            new GuestsExport,
            'daftar-tamu-rafi-ina.xlsx'
        );
    }

    public function edit(Guest $guest)
    {
        return view(
            'admin.guests.edit',
            compact('guest')
        );
    }

    public function update(
        Request $request,
        Guest $guest
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'invitation_count' => [
                'required',
                'integer',
                'min:1',
                'max:20',
            ],
        ]);

        $guest->update($validated);

        return redirect()
            ->route('admin.guests.index')
            ->with(
                'success',
                'Data tamu berhasil diperbarui.'
            );
    }

    public function destroy(Guest $guest)
    {
        $guest->delete();

        return redirect()
            ->route('admin.guests.index')
            ->with(
                'success',
                'Data tamu berhasil dihapus.'
            );
    }

    public function template()
    {
    return Excel::download(
        new GuestTemplateExport,
        'template-import-tamu-rafi-ina.xlsx'
    );
    }
}