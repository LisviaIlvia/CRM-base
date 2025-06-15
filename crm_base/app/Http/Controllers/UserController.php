<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractCrudController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends AbstractCrudController
{
	protected string $pattern = 'utenti';
	protected string $model = User::class;
	
	protected array $indexSetup = [
		'plural' => 'Utenti',
		'single' => 'Utente',
		'type' => 'a',
		'icon' => 'fa-solid fa-users',
		'order' => [ 'key' => 'name', 'order' => 'asc' ],
		'nameDialog' => 'name',
		'headers' => [
			['title' => 'Nome', 'key' => 'name', 'sortable' => true],
			['title' => 'Email', 'key' => 'email', 'sortable' => true],
			['title' => 'Ruolo', 'key' => 'role', 'sortable' => true],
			['title' => 'Azioni', 'key' => 'actions', 'sortable' => false, 'align' => 'end']
		]
	];
	
	protected array $dialogSetup = [
		'create' => [
			'width' => '32%'
		],
		'show' => [
			'width' => '32%'
		],
		'edit' => [
			'width' => '32%'
		]
	];

	protected function beforeStore(&$validatedData)
	{
		$validatedData['password'] = !empty($validatedData['password'])
			? Hash::make($validatedData['password'])
			: Hash::make(Str::random(10));
	}
	
	protected function afterStore(&$object, $validatedData)
	{
		$object->assignRole(Role::findOrFail($validatedData['role_id']));
	}
	
	protected function beforeUpdate(&$validatedData)
	{
		if (!empty($validatedData['password'])) {
			$validatedData['password'] = Hash::make($validatedData['password']);
		}
	}
	
	protected function afterUpdate(&$object, $validatedData)
	{
		$object->roles()->sync(Role::findOrFail($validatedData['role_id']));
	}
	
	protected function setJsonData(string $type, Model|Collection $object) 
	{
		$main = [
			'id' => $object->id,
			'name' => $object->name,
			'email' => $object->email
		];

		$data = match($type) {
			'index', 'store', 'update' => [
				'role' => $object->roles?->pluck('name')->first()
			],
			'show', 'edit' => [
				'role_id' => $object->roles?->pluck('id')->first()
			],
			default => []
		};

		return array_merge(['main' => $main], [$type => $data]);
	}
	
	protected function setOtherData(string $type, Model $object) 
	{
		$roles = Role::all();
		
		return ['roles' => $roles];
	}
	
	protected function setValidation(Model $object) 
	{	
		return [
			'rules' => [
				'name' => 'required|string|max:255',
				'password' => 'sometimes|nullable|string|min:8|confirmed',
				'role_id' => 'required|exists:roles,id'
			],
			'store' => [
				'email' => [
					'required',
					Rule::unique('users', 'email')
						->whereNull('deleted_at')
				]
			],
			'update' => [
				'email' => [
					'required',
					Rule::unique('users', 'email')
						->ignore($object->id)
						->whereNull('deleted_at')
				]
			],
			'messages' => [
				'name.required' => 'Il campo Nome è obbligatorio',
				'name.max' => 'Il campo Nome deve essere di massimo :max caratteri',
				'email.required' => 'Il campo Email è obbligatorio',
				'email.email' => 'Il campo Email deve essere un indirizzo email valido',
				'email.max' => 'Il campo Email deve essere di massimo :max caratteri',
				'email.unique' => 'L\'indirizzo email inserito è già in uso',
				'password.min' => 'La password deve essere di almeno :min caratteri',
				'password.confirmed' => 'La password e la conferma devono coincidere',
				'role_id.required' => 'Il campo Ruolo è obbligatorio',
				'role_id.exists' => 'Il Ruolo selezionato non esiste'
			]
		];
	}
}