<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\HoContactListFormRequest;
use App\Http\Controllers\Controller;
use App\Repositories\HoContactListRepository;

class ContactController extends Controller
{
    /**
     * The contact repository instance.
     *
     * @var HoContactRepository
     */
    protected $contact;

    /**
     * Create a new controller instance.
     *
     * @param  HoContactListRepository  $contact
     * @return void
     */
    public function __construct(HoContactListRepository $contact)
    {
        $this->contact = $contact;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getContact()
    {
        return view('frontend.contact');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function postContact(HoContactListFormRequest $request)
    {
        $this->contact->insertAtFirst("all", "jhi", $request->contents, $request->email, $request->name);
        return redirect()->route('contact.index')->with('status', 'Send contact success!');
    }
}
