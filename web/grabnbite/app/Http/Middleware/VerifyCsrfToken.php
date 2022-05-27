<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
        'http://192.168.137.1:8000/mobile/register',
        'http://192.168.137.1:8000/mobile/register2',
        'http://192.168.137.1:8000/mobile/login',
        'http://192.168.137.1:8000/mobile/getprofile',
        'http://192.168.137.1:8000/mobile/saveprofile',
        'http://192.168.137.1:8000/mobile/products',
        'http://192.168.137.1:8000/mobile/smallorders',
        'http://192.168.137.1:8000/mobile/largeorders',
        'http://192.168.137.1:8000/mobile/getproductdetails',
        'http://192.168.137.1:8000/mobile/addcart',
        'http://192.168.137.1:8000/mobile/cart',
        'http://192.168.137.1:8000/mobile/placeorder',
        'http://192.168.137.1:8000/mobile/deletecart',
        'http://192.168.137.1:8000/mobile/minuscart',
        'http://192.168.137.1:8000/mobile/pluscart',
        'http://192.168.137.1:8000/mobile/changepassword',
        'http://192.168.137.1:8000/mobile/ordersummary',
        'http://192.168.137.1:8000/mobile/savedriver',
        'http://192.168.137.1:8000/mobile/getdriver',
        'http://192.168.137.1:8000/mobile/getorderhistory',
        'http://192.168.137.1:8000/mobile/getorderhistory2',
        'http://192.168.137.1:8000/mobile/orderdetails',
        'http://192.168.137.1:8000/mobile/orderdetails2',
        'http://192.168.137.1:8000/mobile/cancelorder',
        'http://192.168.137.1:8000/mobile/confirmorder',
        'http://192.168.137.1:8000/mobile/transitorder',
        'http://192.168.137.1:8000/mobile/receiveorder',
        'http://192.168.137.1:8000/mobile/rateexperience',
        'http://192.168.137.1:8000/mobile/getratings',
        'http://192.168.137.1:8000/mobile/getreviews',
        'http://192.168.137.1:8000/mobile/getcompany',
        'http://192.168.137.1:8000/mobile/getuser',
        'http://192.168.137.1:8000/mobile/coupons',
        'http://192.168.137.1:8000/mobile/usecoupon',
        'http://192.168.137.1:8000/mobile/addfavorite',
        'http://192.168.137.1:8000/mobile/favorites',
        'http://192.168.137.1:8000/mobile/removefavorite',
        'http://192.168.137.1:8000/mobile/gettopfood',
        'http://192.168.137.1:8000/mobile/getridernotifications',
        'http://192.168.137.1:8000/mobile/updateridernotifications',
        'http://192.168.137.1:8000/mobile/getcustomernotifications',
        'http://192.168.137.1:8000/mobile/updatecustomernotifications',
        'http://192.168.137.1:8000/mobile/countcartnotifications',
        'http://192.168.137.1:8000/mobile/getuserdetails',
        'http://192.168.137.1:8000/mobile/getriderdetails'

    ];
}
