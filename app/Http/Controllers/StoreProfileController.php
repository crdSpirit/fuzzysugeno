<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StoreProfileController extends Controller
{
    public function edit()
    {
        $store = auth()->user()->store;

        return view('store.profile', compact('store'));
    }

    public function update(Request $request)
    {
        $store = auth()->user()->store;

        $request->validate([
            'store_name' => 'required|string|max:100',
            'owner_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string',

            // lokasi dari MAP
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $store->update([
            'store_name' => $request->store_name,
            'owner_name' => $request->owner_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()
            ->route('store.profile.edit')
            ->with('success', 'Data toko berhasil diperbarui');
    }
}
