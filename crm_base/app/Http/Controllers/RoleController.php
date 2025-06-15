<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractCrudController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends AbstractCrudController
{
	protected string $pattern = 'ruoli';
	protected string $model = Role::class;
	protected array $verifyDestroy = ['users'];
	
	protected array $indexSetup = [
		'plural' => 'Ruoli',
		'single' => 'Ruolo',
		'type' => 'm',
		'icon' => 'fa-solid fa-user-shield',
		'order' => [ 'key' => 'name', 'order' => 'asc' ],
		'nameDialog' => 'name',
		'headers' => [
			['title' => 'Nome', 'key' => 'name', 'sortable' => true],
			['title' => 'Azioni', 'key' => 'actions', 'sortable' => false, 'align' => 'end']
		]
	];
	
	protected function afterStore(&$object, $validatedData)
	{
		$permissions = Permission::whereIn('id', $validatedData['permissions'])->get();
		$object->syncPermissions($permissions);
	}
	
	protected function afterUpdate(&$object, $validatedData)
	{
		if ($validatedData['permissions']) {
			$permissions = Permission::whereIn('id', $validatedData['permissions'])->get();
		} else {
			$permissions = null;
		}
		$object->syncPermissions($permissions ?? []);
	}
	
	protected function setJsonData(string $type, Model|Collection $object) 
	{
		return [
			'main' => [
				'id' => $object->id,
				'name' => $object->name
			]
		];
	}
	
	protected function setOtherData(string $type, Model $object) 
	{
		$permissions = Permission::all()->groupBy(function ($permission) {
			return explode('.', $permission->name)[0];
		})->map(function ($permissions, $model) use ($object) {
			$rules = $permissions->mapWithKeys(function ($permission) use ($object) {
				$actionName = explode('.', $permission->name)[1];
				
				$rule = [
					'id' => $permission->id,
				];
				
				if ($object && method_exists($object, 'hasPermissionTo')) {
					$rule['value'] = $object->hasPermissionTo($permission);
				}
				
				return [
					$actionName => $rule
				];
			});

			return [
				'model' => $model,
				'rules' => $rules,
			];
		})->values();
		
		return [
			'permissions' => $permissions
		];
	}
	
	protected function setValidation(Model $object) 
	{	
		return [
			'rules' => [
				'permissions' => 'nullable|array'
			],
			'store' => [
				'name' => 'required|unique:roles,name'
			],
			'update' => [
				'name' => 'required|unique:roles,name,' . $object->id
			],
			'messages' => [
				'name.required' => 'Il campo nome è obbligatorio.',
				'name.unique' => 'Il nome del ruolo deve essere unico.',
				'permissions.array' => 'Il campo permessi deve essere un array.'
			]
		];
	}
}