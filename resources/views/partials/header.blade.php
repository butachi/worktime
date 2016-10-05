<header id="header"><!--header-->
            <div class="header_top"><!--header_top-->
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="contactinfo">
                                <ul class="nav nav-pills">
                                    <li><a href="#"><i class="fa fa-phone"></i> +2 95 01 88 821</a></li>
                                    <li><a href="#"><i class="fa fa-envelope"></i> info@domain.com</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="social-icons pull-right">                                
                                <ul class="nav navbar-nav">                                    
                                    <li>
                                        <div id="wxWrap">
                                            <span id="wxIntro">
                                                ハワイ時間: {{ Carbon\Carbon::now('Pacific/Honolulu')->format('Y/m/d Ag:i') }}
                                            </span>
                                            <span id="wxIcon2"></span>
                                            <span id="wxTemp"></span>
                                        </div>
                                    </li>
                                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                    <li>                                            
                                        <i class="fa">
                                            <a href="{{LaravelLocalization::getLocalizedURL($localeCode) }}">
                                                <img src='<?= url('themes/front/images/countries/'.$localeCode.'.png') ?>' />
                                            </a>
                                        </i>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--/header_top-->

            <div class="header-middle"><!--header-middle-->
                <div class="container">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="logo pull-left">
                                <a href="index.html"><img src="{{ asset('themes/eshopper/images/t_title.png') }}" alt="" /></a>
                            </div>                            
                        </div>
                        <div class="col-sm-8">
                            <div class="shop-menu pull-right">
                                <ul class="nav navbar-nav">
                                    <li><a href="#"><i class="fa fa-user"></i> Account</a></li>
                                    <li><a href="{{ URL::route('wishlist.index') }}"><i class="fa fa-star"></i> Wishlist</a></li>
                                    <li><a href=""><i class="fa fa-crosshairs"></i> Checkout</a></li>
                                    <li><a href="{{ URL::route('cart.index') }}"><i class="fa fa-shopping-cart"></i> Cart {{ app('cart')->quantity() }}</a></li>
                                    <li>
                                        <?php
                                        if ($user) 
                                        {                                        
                                            $fullname = $user->name_family . ' ' . $user->name_fore; 
                                            if ($fullname == ' ')
                                            {
                                                $fullname = trans('core.general.complete your profile');
                                            }
                                            ?>
                                        <a href="{{ URL::route('logout') }}"><i class="fa fa-user"></i>{!! trans('auth.logout') !!}</a>
                                        <?php
                                        }                                        
                                        else {
                                        ?>      
                                        <a href="{{ URL::route('login') }}"><i class="fa fa-lock"></i> Login</a>
                                        <?php 
                                        }
                                        ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--/header-middle-->

            <div class="header-bottom"><!--header-bottom-->
                <div class="container">
                    <div class="row">
                        <div class="col-sm-9">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>
                            <div class="mainmenu pull-left">
                                <ul class="nav navbar-nav collapse navbar-collapse">
                                    <li><a href="index.html" class="active">Home</a></li>
                                    <li class="dropdown"><a href="#">Shop<i class="fa fa-angle-down"></i></a>
                                        <ul role="menu" class="sub-menu">
                                            <li><a href="shop.html">Products</a></li>
                                            <li><a href="product-details.html">Product Details</a></li> 
                                            <li><a href="checkout.html">Checkout</a></li> 
                                            <li><a href="cart.html">Cart</a></li> 
                                            <li><a href="login.html">Login</a></li> 
                                        </ul>
                                    </li> 
                                    <li class="dropdown"><a href="#">Blog<i class="fa fa-angle-down"></i></a>
                                        <ul role="menu" class="sub-menu">
                                            <li><a href="blog.html">Blog List</a></li>
                                            <li><a href="blog-single.html">Blog Single</a></li>
                                        </ul>
                                    </li> 
                                    <li><a href="404.html">404</a></li>
                                    <li><a href="{{ URL::route('contact.index') }}">Contact</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="search_box pull-right">
                                <input type="text" placeholder="Search"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--/header-bottom-->
        </header><!--/header-->
