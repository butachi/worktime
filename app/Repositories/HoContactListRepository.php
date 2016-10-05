<?php

namespace App\Repositories;

use App\Models\Ho_ContactList;
use DB;

class HoContactListRepository
{
    /**
     * insert contact list at first
     *
     * @param  User  $user
     * @return Collection
     */
    public function insertAtFirst($type, $send_to, $contents, $email, $name)
    {
        DB::transaction(function() use ($type, $send_to, $contents, $email, $name)
        {
            
            $contact = new Ho_ContactList;

            $contact->type = $type;
            $contact->send_to = $send_to;
            $contact->contents = $contents;
            $contact->email = $email;
            $contact->name = $name;

            $contact->save();
            
            // update start_id
            $result = Ho_ContactList::find($contact->id)->update(['start_id' => $contact->id]);
            
            if( !$result )
            {
                throw new \Exception('Contact not created.');
            }
        });
    }
    
    /**
     * insert contact list at first
     *
     * @param  User  $user
     * @return Collection
     */
    public function insertWithStartId($type, $send_to, $contents, $start_id)
    {
        DB::transaction(function() use ($type, $send_to, $contents, $start_id)
        {
            $contact = new Ho_ContactList;

            $contact->type = $type;
            $contact->send_to = $send_to;
            $contact->contents = $contents;
            $contact->start_id = $start_id;

            $result = $contact->save();
            
            if( !$result )
            {
                throw new \Exception('Contact not created.');
            }
        });
    }
}
