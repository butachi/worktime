<?php

namespace App\Http\Controllers;

use App\Facades\Cart;
use Laracasts\Flash\Flash;

class CartController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        //Cart::clear();
        //Cart::update('027c91341fd5cf4d2579b49c4b6a90da', 10);
        /*Cart::update('027c91341fd5cf4d2579b49c4b6a90da', [
            'quantity' => 2,
            'price'    => 12.50,
            'attributes'  => array('size' => 'large')
        ]);*/
        
        /*Cart::update([
            '027c91341fd5cf4d2579b49c4b6a90da' => [                
                'name'     => 'T-Shirt',
                'quantity' => 1,
                'price'    => 12.50,
            ],
            '370d08585360f5c568b18d1f2e4ca1df' => [                
                'name'     => 'Sweatshirt',
                'quantity' => 2,
                'price'    => 98.32,
            ],
        ]);*/
        //Cart::remove('027c91341fd5cf4d2579b49c4b6a90da');
        /*Cart::remove([
            '370d08585360f5c568b18d1f2e4ca1df',
            '027c91341fd5cf4d2579b49c4b6a90da',
        ]);
        
        if (Cart::exists('370d08585360f5c568b18d1f2e4ca1df'))
        {
            Cart::remove('370d08585360f5c568b18d1f2e4ca1df');
        }*/        
        $items = Cart::items();
        
        return view('frontend.cart.index', compact('items'));
        
    }
    
    /**
     * Adds a new product to the shopping cart.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add($id)
    {
        // Get the product from the database
        $product = [
            [
            'id' => '1',
            'name' => 'Product 1',
            'quantity' => 1,
            'price' => 9.99,
            'options' => array('size' => 'large'),
            ],
            [
            'id' => '2',
            'name' => 'Product 2',
            'quantity' => 1,
            'price' => 9.99,
            'options' => array('size' => 'large'),
            ],
            
        ];
        
        $this->addToCart($product);
        
        Flash::error(trans('Product was successfully added to the shopping cart.'));

        return redirect()->back()->withInput();
    }

    /**
     * Add product to cart.
     *
     * @param App\Models\Product $product
     *
     * @return Cartalyst\Cart\Collections\ItemCollection
     */
    protected function addToCart($product)
    {
        // Add the item to the cart
        $item = Cart::add($product);

        return $item;
    }
}
