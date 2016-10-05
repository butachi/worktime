@extends('layouts.default')
@section('title')
{{ trans('auth.login') }} | @parent
@stop

@section('content')
<div id="cart_items">
    <div class="table-responsive cart_info">        
        <table class="table table-condensed">
            <thead>
                <tr class="cart_menu">
                    <td class="image">Name</td>
                    <td class="price">Price</td>
                    <td class="quantity">Quantity</td>
                    <td class="total">Total</td>                    
                </tr>
            </thead>
            @if ($items->count())
            <tbody>
                <?php
                foreach ($items as $item) {
                    //print_r($item);die;
                    ?>
                    <tr>
                        <td class="cart_description">
                            <h4><a href="">{{ $item->name }}</a></h4>
                        </td>
                        <td class="cart_price">
                            <h4>{{ $item->price }}</h4>
                        </td>
                        <td class="cart_quantity">
                            <div class="input-group btn-block" style="max-width: 200px;">
                                <input type="text" name="quantity[18]" value="1" size="1" class="form-control">
                                <span class="input-group-btn">
                                    <button type="submit" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Update"><i class="fa fa-refresh"></i></button>
                                    <button type="button" data-toggle="tooltip" title="" class="btn btn-danger" onclick="cart.remove('18');" data-original-title="Remove"><i class="fa fa-times-circle"></i></button>
                                </span>
                            </div>                            
                        </td>
                        <td class="cart_total">
                            <p class="cart_total_price">${{ $item->subtotal * $item->quantity }}</p>
                        </td>                        
                    </tr>
                    <?php
                }
                ?>
            </tbody>
            @else
            <tbody>
                <tr><td colspan="4">empty cart</td></tr>
            </tbody>            
            @endif            
        </table>        
    </div>
</div>
<div class="row">
    @if ($items->count())
    <div class="col-sm-6">        
        <form method="POST" action="http://demo.cartalyst.com/cart/coupon" accept-charset="UTF-8">
            <input name="_token" type="hidden" value="{{ csrf_Token() }}">                
            <div class="form-group">
                <label for="coupon" class="control-label">Apply Coupon<i class="fa fa-info-circle"></i></label>
                <input type="text" class="form-control" name="coupon" id="coupon" placeholder="Coupon Code" value="" required="">
                <span class="help-block">Valid Codes: PROMO14, DISC2014</span>
            </div>
            <div class="form-group">
                <button class="btn">Apply Coupon</button>
            </div>
        </form>        
    </div>
    <div class="col-sm-6">
        <div class="total_area">
            <ul>
                <li>Cart Sub Total <span>$59</span></li>
                <li>Eco Tax <span>$2</span></li>
                <li>Shipping Cost <span>Free</span></li>
                <li>Total <span>$61</span></li>
            </ul>
            <a href="{{ URL::route('home') }}" class="btn btn-default update">Continue Shopping</a>
            <a href="" class="btn btn-default check_out pull-right">Check Out</a>
        </div>
    </div>
    @endif
</div>
@stop