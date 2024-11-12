<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function getData()
    {
        $users = User::select('id', 'nama', 'email', 'jenisPerusahaan', 'noWa', 'alamat', 'role', 'status')->get();
        return response()->json(['data' => $users]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'jenisPerusahaan' => 'required|string',
            'alamat' => 'required|string',
            'noWa' => 'required|string',
            'role' => 'required|in:pelanggan,owner,admin,baker',
        ]);

        $user = User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'jenisPerusahaan' => $validated['jenisPerusahaan'],
            'alamat' => $validated['alamat'],
            'noWa' => $validated['noWa'],
            'role' => $validated['role'],
            'status' => 1, // Default pending
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
        ]);
    }

    public function update(Request $request, $id)
    {

        $user = User::find($id);
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'jenisPerusahaan' => 'required|string',
            'alamat' => 'required|string',
            'noWa' => 'required|string',
            'role' => 'required|in:pelanggan,owner,admin,baker',
            'status' => 'required|in:0,1,2',
        ]);
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $cek = $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
        ]);
    }

    public function destroy($id)
    {
        User::find($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ]);
    }
}
