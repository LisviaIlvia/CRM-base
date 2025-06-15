<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
	public function index()
	{	
		$dashboard = 'Dashboard';
		
		return Inertia::render($dashboard, [
			'title' => 'Dashboard',
			'icon' => 'fa-solid fa-sliders'
		]);
	}
}