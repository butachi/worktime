<?php namespace Modules\Core\Http\Controller;

use Pingpong\Modules\Routing\Controller;

class CoreController extends Controller {
	
	public function index()
	{
		return view('core::index');
	}
	
}