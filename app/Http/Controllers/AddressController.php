<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function index(Request $request): View
    {
        return view('addresses.index', ['addresses' => $request->user()->addresses]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['recipient' => ['required', 'string', 'max:255'], 'phone' => ['required', 'string', 'max:30'], 'full_address' => ['required', 'string']]);
        $request->user()->addresses()->create($data);

        return back();
    }

    public function destroy(Request $request, Address $address): RedirectResponse
    {
        abort_unless($address->user_id === $request->user()->id, 403);
        $address->delete();

        return back();
    }
}
