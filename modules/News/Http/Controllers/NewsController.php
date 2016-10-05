<?php namespace Modules\News\Http\Controllers;

use Pingpong\Modules\Routing\Controller;

class NewsController extends Controller {
	
	public function index()
	{
		return view('news::index');
	}
	
}