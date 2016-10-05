<?php

namespace App\Services\Collection;

use Illuminate\Support\Collection;

class CartRowCollection extends Collection
{
    /**
     * Constructor for the CartRowCollection.
     *
     * @param array  $rows
     */
    public function __construct($rows)
    {
        parent::__construct($rows);        
    }

    public function __get($arg)
    {
        if ($this->has($arg)) {
            return $this->get($arg);
        }
        return;
    }

    public function search($search, $strict = false)
    {
        foreach ($search as $key => $value) {
            if ($key === 'attributes') {
                $found = $this->{$key}->search($value);
            } else {
                $found = ($this->{$key} === $value) ? true : false;
            }
            if (!$found) {
                return false;
            }
        }

        return $found;
    }
}
