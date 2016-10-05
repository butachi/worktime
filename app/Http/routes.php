<?php

use Illuminate\Routing\Router;

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */
$router->get('/', [
    'as' => 'home', 'uses' => 'HomeController@index',
]);

/*
 |--------------------------------------------------------------------------
 | Route of member with action login, register, activate, reset, logout
 |--------------------------------------------------------------------------
 */
$router->group(['prefix' => 'auth'], function (Router $router) {
    # Login
    $router->get('login', ['middleware' => 'auth.guest', 'as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);
    $router->post('login', array('as' => 'login.post', 'uses' => 'Auth\AuthController@postLogin'));
    # Register
    $router->get('register', ['middleware' => 'auth.guest', 'as' => 'register', 'uses' => 'Auth\AuthController@getRegister']);
    $router->post('register', array('as' => 'register.post', 'uses' => 'Auth\AuthController@postRegister'));
    $router->get('register/{email}/{activationCode}', ['as' => 'register.complete', 'uses' => 'Auth\AuthController@getRegisterComplete']);
    $router->post('register/{email}/{activationCode}', ['as' => 'register.complete.post', 'uses' => 'Auth\AuthController@postRegisterComplete']);
    # Reset password
    $router->get('reset', ['as' => 'reset', 'uses' => 'Auth\AuthController@getReset']);
    $router->post('reset', ['as' => 'reset.post', 'uses' => 'Auth\AuthController@postReset']);
    $router->get('reset/{id}/{code}', ['middleware' => 'auth.guest', 'as' => 'reset.complete', 'uses' => 'Auth\AuthController@getResetComplete']);
    $router->post('reset/{id}/{code}', ['as' => 'reset.complete.post', 'uses' => 'Auth\AuthController@postResetComplete']);
    # Logout
    $router->get('logout', array('as' => 'logout', 'uses' => 'Auth\AuthController@getLogout'));
});

/*
 |--------------------------------------------------------------------------
 | Route of shoping cart
 |--------------------------------------------------------------------------
 */
$router->group(['prefix' => 'cart'], function (Router $router) {
    $router->get('/', ['as' => 'cart.index', 'uses' => 'CartController@index']);
    $router->post('/', ['as' => 'cart.update', 'uses' => 'CartController@update']);
    $router->get('{id}/add', ['as' => 'cart.add', 'uses' => 'CartController@add']);
    $router->get('{id}/move', ['as' => 'cart.move', 'uses' => 'CartController@move']);
    $router->get('{id}/addAjax', ['as' => 'cart.add.ajax', 'uses' => 'CartController@addAjax']);
    $router->get('{id}/remove', ['as' => 'cart.delete', 'uses' => 'CartController@delete']);
    $router->get('{id}/removeAjax', ['as' => 'cart.delete.ajax', 'uses' => 'CartController@deleteAjax']);
    $router->get('destroy', ['as' => 'cart.destroy', 'uses' => 'CartController@destroy']);
    $router->get('count', ['as' => 'cart.count.ajax', 'uses' => 'CartController@countAjax']);
});
/*
 |--------------------------------------------------------------------------
 | Route of the wishlist
 |--------------------------------------------------------------------------
 */
$router->group(['prefix' => 'wishlist'], function ($router) {
    $router->get('/', ['as' => 'wishlist.index', 'uses' => 'WishlistController@index']);
    $router->post('/', ['as' => 'wishlist.update', 'uses' => 'WishlistController@update']);
    $router->get('{id}/add', ['as' => 'wishlist.add', 'uses' => 'WishlistController@add']);
    $router->get('{id}/move', ['as' => 'wishlist.remove', 'uses' => 'WishlistController@move']);
    $router->get('{id}/addAjax', ['as' => 'wishlist.add.ajax', 'uses' => 'WishlistController@addAjax']);
    $router->get('{id}/remove', ['as' => 'wishlist.delete', 'uses' => 'WishlistController@delete']);
    $router->get('{id}/removeAjax', ['as' => 'wishlist.delete.ajax', 'uses' => 'WishlistController@deleteAjax']);
    $router->get('destroy', ['as' => 'wishlist.destroy', 'uses' => 'WishlistController@destroy']);
    $router->get('count', ['as' => 'wishlist.count.ajax', 'uses' => 'WishlistController@countAjax']);
});
/*
 |--------------------------------------------------------------------------
 | Route of shoping cart
 |--------------------------------------------------------------------------
 */
$router->group(['prefix' => 'coupon'], function ($router) {
    $router->post('/', ['as' => 'applyCoupon', 'uses' => 'CartController@applyCoupon']);
    $router->get('remove/{name}', ['as' => 'applyCoupon', 'uses' => 'CartController@removeCoupon']);
});

$router->get('contact', ['as' => 'contact.index', 'uses' => 'ContactController@getContact']);
$router->post('contact', ['as' => 'contact.store', 'uses' => 'ContactController@postContact']);
