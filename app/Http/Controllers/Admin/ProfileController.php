<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('admin.profile.edit', ['user' => $request->user()]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'string', 'min:8', 'max:100', 'confirmed'],
        ], [
            'current_password.required_with' => 'Isi password saat ini untuk mengganti password.',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (filled($data['password'] ?? null)) {
            $user->password = $data['password']; // di-hash otomatis oleh cast "hashed"
        }

        $user->save();

        return redirect()->route('admin.profile.edit')->with('success', 'Profil disimpan.');
    }
}
