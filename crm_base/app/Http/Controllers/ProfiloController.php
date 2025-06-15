<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class ProfiloController extends Controller
{
	public function edit()
	{
		$user = auth()->user();
		
		return response()->json([
			'name' => $user->name,
			'email' => $user->email,
			'role' => $user->roles->pluck('name')->first()
		]);
	}

    public function update(Request $request)
    {
		$validatedData = $request->validate([
			'name' => "required|string|max:255",
			'password' => 'sometimes|nullable|string|min:8|confirmed'
		], [
			'name.required' => 'Il campo Nome è obbligatorio',
			'name.max' => 'Il campo Nome deve essere di massimo :max caratteri',
			'password.min' => 'La password deve essere di almeno :min caratteri',
			'password.confirmed' => 'La password e la conferma devono coincidere'
		]);
		
		$user = auth()->user();

        $user->name = $validatedData['name'];

        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }
		
		$user->save();
		
		$data = [
			'id' => $user->id,
			'name' => $user->name
		];

		return response()->json([
			'record' => $data
		]);
    }
}