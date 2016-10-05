<?php

namespace app\Services;

use Validator;
use Illuminate\Contracts\Events\Dispatcher;
use App\Services\Collection\CartCollection;
use App\Services\Collection\CartRowCollection;
use App\Services\Collection\CartRowOptionsCollection;
use App\Exceptions\CartInstanceException;
use App\Exceptions\CartInvalidItemException;
use App\Exceptions\CartInvalidRowIdException;

class Cart
{
    /**
     * Session class instance.
     *
     * @var Illuminate\Session\SessionManager
     */
    protected $session;

    /**
     * Event class instance.
     *
     * @var Illuminate\Events\Dispatcher
     */
    protected $event;

    /**
     * Current cart identifier.
     *
     * @var string
     */
    protected $identify;

    /**
     * Constructor.
     *
     * @param Illuminate\Session\SessionManager       $session Session class instance
     * @param \Illuminate\Contracts\Events\Dispatcher $event   Event class instance
     */
    public function __construct($session, Dispatcher $event)
    {
        $this->session = $session;
        $this->event = $event;
        
        $this->identify = $this->session->getId();
    }

    /**
     * Set the current cart identifier.
     *
     * @param string $identify Cart instance name
     *
     * @return app\Services\Cart
     */
    public function setIdentity($identify = null)
    {
        if (empty($identify)) {
            throw new CartInstanceException();
        }

        $this->identify = $identify;

        // Return self so the method is chainable
        return $this;
    }

    /**
     * Get the current cart instance.
     *
     * @return string
     */
    public function getIdentify()
    {
        return $this->identify;
    }

    /**
     * Add item to the shopping cart.
     *
     * @param array $item - A single or multidimensional array that respects the list of indexes
     *
     * @return \App\Services\Cart\Cart
     */
    public function add($item)
    {
        // And if it's not only an array, but a multidimensional array, we need to
        // recursively call the add function
        if ($this->is_multi($item)) {
            // Add multi items
            foreach ($item as $record) {
                $this->add($record);
            }

            return $this;
        }

        // validate item
        $this->validate($item);

        $attributes = array_get($item, 'attributes', []);

        // Fire the cart.add event
        $this->event->fire('cart.add', array_merge($item, ['attributes' => $attributes]));

        $result = $this->addItem($item['id'], $item['name'], $item['quantity'], $item['price'], $attributes);

        // Fire the cart.added event
        $this->event->fire('cart.added', array_merge($item, ['attributes' => $attributes]));

        return $result;
    }

    /**
     * Updating items is as simple as adding them.
     *
     * @param string    $rowId The rowid of the item you want to update
     * @param int|array $data  This can be either an array or an integer, if an integer, it'll update the item quantity.
     *
     * @return bool
     */
    public function update($rowId, $data = array())
    {
        if (is_array($rowId) && $this->is_multi($rowId)) {
            // update multi items
            foreach ($rowId as $id => $data) {
                $this->update($id, $data);
            }

            return $this;
        }

        if (!$this->hasRowId($rowId)) {
            throw new CartInvalidRowIdException();
        }

        if (is_array($data)) {
            // Fire the cart.update event
            $this->event->fire('cart.update', $rowId);

            $result = $this->updateData($rowId, $data);

            // Fire the cart.updated event
            $this->event->fire('cart.updated', $rowId);

            return $result;
        }

        // Fire the cart.update event
        $this->event->fire('cart.update', $rowId);

        $result = $this->updateQuantity($rowId, $data);

        // Fire the cart.updated event
        $this->event->fire('cart.updated', $rowId);

        return $result;
    }

    /**
     * Remove a row from the cart.
     *
     * @param string $rowId The rowid of the item
     *
     * @return bool
     */
    public function remove($rowId)
    {
        if (is_array($rowId)) {
            foreach ($rowId as $id) {
                $this->remove($id);
            }

            return $this;
        }

        if (!$this->hasRowId($rowId)) {
            throw new CartInvalidRowIdException();
        }

        $cart = $this->getItems();

        // Fire the cart.remove event
        $this->event->fire('cart.remove', $rowId);

        $cart->forget($rowId);

        // Fire the cart.removed event
        $this->event->fire('cart.removed', $rowId);

        return $this->updateCart($cart);
    }

    /**
     * Get the cart content.
     *
     * @return \App\Services\Cart\CartCollection
     */
    public function items()
    {
        $cart = $this->getItems();

        return (empty($cart)) ? null : $cart;
    }

    /**
     * Get a single item.
     *
     * @param string $rowId The id of item
     *
     * @return \App\Services\Cart\CartRowCollection
     */
    public function item($rowId)
    {
        $cart = $this->getItems();

        return ($cart->has($rowId)) ? $cart->get($rowId) : null;
    }

    /**
     * Check if an item exists.
     *
     * @param string $rowId
     *
     * @return bool
     */
    public function exists($rowId)
    {
        return $this->hasRowId($rowId);
    }

    /**
     * Empty the cart.
     *
     * @return bool
     */
    public function clear()
    {
        // Fire the cart.destroy event
        $this->event->fire('cart.clear');

        $result = $this->updateCart();

        // Fire the cart.destroyed event
        $this->event->fire('cart.cleared');

        return $result;
    }

    /**
     * Get the cart total.
     *
     * @return int
     */
    public function total()
    {
        $total = 0;
        $cart = $this->getItems();
        
        if (empty($cart)) {
            return $total;
        }

        foreach ($cart as $row) {
            $total += $row->subtotal;
        }

        return $total;
    }

    /**
     * Get the number of items in the cart.
     *
     * @param bool $totalItems Get all the items (when false, will return the number of rows)
     *
     * @return int
     */
    public function count()
    {
        $cart = $this->getItems();

        return $cart->count();
    }
    
    /**
     * Get the total number of items that are in the cart.
     *
     *
     * @return int
     */
    public function quantity()
    {
        $cart = $this->getItems();

        $count = 0;

        foreach ($cart as $row) {
            $count += $row->quantity;
        }

        return $count;
    }

    /**
     * Search if the cart has a item.
     *
     * @param array $data An array with the item ID and attributes options
     *
     * @return array|bool
     */
    public function find(array $data)
    {
        if (empty($data)) {
            return false;
        }

        foreach ($this->getItems() as $item) {
            $found = $item->search($data);

            if ($found) {
                $rows[] = $item->rowid;
            }
        }

        return (empty($rows)) ? false : $rows;
    }

    /**
     * validate Item data.
     *
     * @param $item
     *
     * @return array $item;
     *
     * @throws InvalidItemException
     */
    protected function validate($item)
    {
        $validator = Validator::make($item, [
            'id' => 'required',
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            throw new CartInvalidItemException($validator->messages()->first());
        }

        return $item;
    }

    /**
     * Add item to the shoping cart.
     *
     * @param string $idItem     Unique ID of the item
     * @param string $name       Name of the item
     * @param int    $quantity   Item quantity to add to the cart
     * @param float  $price      Price of one item
     * @param array  $attributes Array of additional attributes, such as 'size' or 'color'
     */
    protected function addItem($idItem, $name, $quantity, $price, array $attributes = [])
    {
        $cartCollection = $this->getItems();

        $rowId = $this->generateRowId($idItem, $attributes);

        if ($cartCollection->has($rowId)) {
            $item = $cartCollection->get($rowId);
            $cartCollection = $this->updateRow($rowId, ['quantity' => $item->quantity + $quantity]);
        } else {
            $cartCollection = $this->createRow($rowId, $idItem, $name, $quantity, $price, $attributes);
        }

        return $this->updateCart($cartCollection);
    }

    /**
     * Get the carts content, if there is no cart content set yet, return a new empty Collection.
     *
     * @return App\Services\Cart\CartCollection
     */
    protected function getItems()
    {
        $content = ($this->session->has($this->getIdentify())) ?
                $this->session->get($this->getIdentify()) : new CartCollection();

        return $content;
    }

    /**
     * Generate a unique id for the new row.
     *
     * @param string $id         Unique ID of the item
     * @param array  $attributes Array of additional attributes, such as 'size' or 'color'
     *
     * @return bool
     */
    protected function generateRowId($id, $attributes)
    {
        ksort($attributes);

        return md5($id.serialize($attributes));
    }

    /**
     * Update the cart.
     *
     * @param \App\Services\Cart\CartCollection $cart The new cart content
     */
    protected function updateCart($cart = null)
    {
        return $this->session->put($this->getIdentify(), $cart);
    }

    /**
     * Check if a rowid exists in the current cart instance.
     *
     * @param string $rowId Unique ID of the item
     *
     * @return bool
     */
    protected function hasRowId($rowId)
    {
        return $this->getItems()->has($rowId);
    }

    /**
     * Create a new row Object.
     *
     * @param string $rowId      The ID of the new record
     * @param string $id         Unique ID of the item
     * @param string $name       Name of the item
     * @param int    $quantity   Item quantity to add to the cart
     * @param float  $price      Price of one item
     * @param array  $attributes Array of additional attributes, such as 'size' or 'color'
     *
     * @return App\Services\Cart\CartCollection
     */
    protected function createRow($rowId, $id, $name, $quantity, $price, $attributes)
    {
        $cart = $this->getItems();

        $newRecord = new CartRowCollection([
            'rowid' => $rowId,
            'id' => $id,
            'name' => $name,
            'quantity' => $quantity,
            'price' => $price,
            'subtotal' => $quantity * $price,
            'attributes' => new CartRowOptionsCollection($attributes),
        ]);

        $cart->put($rowId, $newRecord);

        return $cart;
    }

    /**
     * Update a row if the rowId already exists.
     *
     * @param string $rowId The ID of the row to update
     * @param array  $data  This can be either an array or an integer, if an integer, it'll update the item quantity
     *
     * @return App\Services\Cart\CartCollection
     */
    protected function updateRow($rowId, $data)
    {
        $cart = $this->getItems();

        $row = $cart->get($rowId);

        foreach ($data as $key => $value) {
            if ($key == 'attributes') {
                $attributes = $row->attributes->merge($value);
                $row->put($key, $attributes);
            } else {
                $row->put($key, $value);
            }
        }

        if (!is_null(array_keys($data, ['quantity', 'price']))) {
            $row->put('subtotal', $row->quantity * $row->price);
        }

        $cart->put($rowId, $row);

        return $cart;
    }

    /**
     * Update the quantity of a row.
     *
     * @param string $rowId    The ID of the row
     * @param int    $quantity The qty to add
     *
     * @return App\Services\Cart\CartCollection
     */
    protected function updateQuantity($rowId, $quantity)
    {
        if ($quantity <= 0) {
            return $this->remove($rowId);
        }

        return $this->updateRow($rowId, ['quantity' => $quantity]);
    }

    /**
     * Update an attribute of the row.
     *
     * @param string $rowId The ID of the row
     * @param array  $data  An array of attributes to update
     *
     * @return App\Services\Cart\CartCollection
     */
    protected function updateData($rowId, $data)
    {
        return $this->updateRow($rowId, $data);
    }

    /**
     * Check if the array is a multidimensional array.
     *
     * @param array $array The array to check
     *
     * @return bool
     */
    protected function is_multi(array $array)
    {
        return is_array(head($array));
    }
    
    /**
     * Generate a identity for session key
     *
     * @return string
     */
    protected function generateIdentity()
    {
        return str_random(32);
    }
}
