<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MobileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'prevent-back-history'],function(){

	Route::get('/', function(){

		return view('index');
	});

	Auth::routes();

	// guest routes
	Route::get('/', [GuestController::class, 'index'])->name('guest-index');

	Route::get('/signup', [GuestController::class, 'registerForm'])->name('guest-register');

	Route::post('/save', [GuestController::class, 'save'])->name('guest-register-save');

	Route::post('/login', [GuestController::class, 'login'])->name('guest-login');

	Route::get('/verify/{vkey}', [GuestController::class, 'verify']);

	Route::get('/notverified', function(){

		return view('notverified');
	});

	// superadmin routes
	Route::get('/admin/users', [AdminController::class, 'getUsers'])->name('admin-get-users');

	Route::post('/admin/user/delete',  [AdminController::class, 'deleteUser'])->name('admin-delete-user');

	Route::post('/admin/user/restore', [AdminController::class, 'restoreUser'])->name('admin-restore-user');

	Route::get('/admin/ratings', [AdminController::class, 'allRatings'])->name('admin-all-ratings');

	Route::get('/admin/getmerchantratings', [AdminController::class, 'getMerchantRatings'])->name('admin-get-merchant-ratings');

	Route::get('/admin/getriderratings', [AdminController::class, 'getRiderRatings'])->name('admin-get-rider-ratings');

	Route::get('/admin/merchant/ratings/{id}', [AdminController::class, 'getMerchantRatingsByID']);

	Route::get('/admin/rider/ratings/{id}', [AdminController::class, 'getRiderRatingsByID']);

	// admin routes
	Route::get('/admin', [AdminController::class, 'index'])->name('admin-index');

	Route::get('/admin/account', [AdminController::class, 'account'])->name('admin-account');

	Route::get('/admin/getprofile', [AdminController::class, 'getProfile'])->name('admin-get-profile');

	Route::post('/admin/upload', [AdminController::class, 'upload'])->name('admin-image-upload');

	Route::post('/admin/saveprofile', [AdminController::class, 'saveProfile'])->name('admin-save-profile');

	Route::get('/admin/business', [AdminController::class, 'companyProfile'])->name('admin-company-profile');

	Route::post('/admin/savecompany', [AdminController::class, 'saveCompany'])->name('admin-save-company');

	Route::get('/admin/getcompany', [AdminController::class, 'getCompany'])->name('admin-get-company');

	Route::post('/admin/uploadlogo', [AdminController::class, 'uploadLogo'])->name('admin-company-image-upload');

	Route::post('/changepassword', [AdminController::class, 'changePassword'])->name('admin-change-password');

	Route::get('/admin/product', [AdminController::class, 'product'])->name('admin-product');

	Route::get('/admin/getproducts', [AdminController::class, 'getProducts'])->name('admin-get-products');

	Route::post('/admin/addproduct', [AdminController::class, 'addProduct'])->name('admin-add-product');

	Route::get('/admin/product/{id}', [AdminController::class, 'getProductByID']);

	Route::post('/admin/editproduct', [AdminController::class, 'editProduct'])->name('admin-edit-product');

	Route::post('/admin/deleteproduct', [AdminController::class, 'deleteProduct'])->name('admin-delete-product');

	Route::post('/admin/addons', [AdminController::class, 'addOns'])->name('admin-add-addons');

	Route::get('/admin/getaddons', [AdminController::class, 'getAddOns'])->name('admin-get-addons');

	Route::get('/admin/addon/{id}', [AdminController::class, 'getAddOnByID']);

	Route::post('/admin/editaddon', [AdminController::class, 'editAddOn'])->name('admin-edit-addon');

	Route::post('/admin/deleteaddon', [AdminController::class, 'deleteAddOn'])->name('admin-delete-addon');

	Route::get('/admin/loadaddons', [AdminController::class, 'loadAddOns'])->name('admin-load-addons');

	Route::get('/admin/categories', [AdminController::class, 'loadCategories'])->name('admin-load-categories');

	Route::get('/admin/user/{id}', [AdminController::class, 'userDetails']);

	Route::get('/admin/orders', [AdminController::class, 'orderView'])->name('admin-order-view');

	Route::get('/admin/getorders', [AdminController::class, 'getOrders'])->name('admin-get-orders');

	Route::get('/admin/order/detail/{id}', [AdminController::class, 'getOrderByID']);

	Route::get('/admin/reviews', [AdminController::class, 'reviewPage'])->name('admin-reviews');

	Route::get('/admin/getreviews', [AdminController::class, 'getReviews'])->name('admin-get-reviews');

	Route::post('/admin/deletereview', [AdminController::class, 'deleteReview'])->name('admin-delete-review');

	Route::get('/admin/coupons', [AdminController::class, 'couponPage'])->name('admin-coupons');

	Route::post('/admin/addcoupon', [AdminController::class, 'addCoupon'])->name('admin-add-coupon');

	Route::get('/admin/getcoupons', [AdminController::class, 'getCoupons'])->name('admin-get-coupons');

	Route::get('/admin/coupon/{id}', [AdminController::class, 'getCouponByID']);

	Route::post('/admin/editcoupon', [AdminController::class, 'editCoupon'])->name('admin-edit-coupon');

	Route::post('/admin/deletecoupon', [AdminController::class, 'deleteCoupon'])->name('admin-delete-coupon');

	Route::post('/admin/coupon/disable',  [AdminController::class, 'disableCoupon'])->name('admin-disable-coupon');

	Route::post('/admin/coupon/enable', [AdminController::class, 'enableCoupon'])->name('admin-enable-coupon');

	Route::get('/admin/reports', [AdminController::class, 'getReports'])->name('admin-get-reports');

	Route::post('/admin/submitdate', [AdminController::class, 'getReportByDate'])->name('admin-submit-date');

	Route::get('/admin/convert/pdf/{start}/{end}', [AdminController::class, 'convertPDF']);

	Route::get('/admin/notifications', [AdminController::class, 'getNotifications'])->name('admin-get-notifications');

	Route::post('/admin/updatenotification', [AdminController::class, 'updateNotification'])->name('admin-update-notification');

	Route::get('/admin/loadchart', [AdminController::class, 'loadChart'])->name('admin-load-chart');

	Route::get('/admin/loadsummary', [AdminController::class, 'loadSummary'])->name('admin-load-summary');

	Route::get('/admin/loadsummary2', [AdminController::class, 'loadSummary2'])->name('admin-load-summary2');



	// mobile routes
	Route::post('/mobile/register', [MobileController::class, 'register']);

	Route::post('/mobile/register2', [MobileController::class, 'register2']);

	Route::post('/mobile/login', [MobileController::class, 'login']);

	Route::post('/mobile/getprofile', [MobileController::class, 'getProfile']);

	Route::post('/mobile/saveprofile', [MobileController::class, 'saveProfile']);

	Route::get('/mobile/getrestaurants', [MobileController::class, 'getRestaurants']);

	Route::post('/mobile/products', [MobileController::class, 'getProducts']);

	Route::post('/mobile/smallorders', [MobileController::class, 'getSmallOrders']);

	Route::post('/mobile/largeorders', [MobileController::class, 'getLargeOrders']);

	Route::post('/mobile/getproductdetails', [MobileController::class, 'getProductDetails']);

	Route::post('/mobile/addcart', [MobileController::class, 'addCart']);

	Route::post('/mobile/cart', [MobileController::class, 'getCart']);

	Route::post('/mobile/placeorder', [MobileController::class, 'placeorder']);

	Route::post('/mobile/deletecart', [MobileController::class, 'deleteCart']);

	Route::post('/mobile/minuscart', [MobileController::class, 'minusCart']);

	Route::post('/mobile/pluscart', [MobileController::class, 'plusCart']);

	Route::post('/mobile/changepassword', [MobileController::class, 'changePassword']);

	Route::post('/mobile/ordersummary', [MobileController::class, 'orderSummary']);

	Route::post('/mobile/savedriver', [MobileController::class, 'saveDriver']);

	Route::post('/mobile/getdriver', [MobileController::class, 'getDriver']);

	Route::post('/mobile/getorderhistory', [MobileController::class, 'getOrderHistory']);

	Route::get('/mobile/getorderhistory2', [MobileController::class, 'getOrderHistory2']);

	Route::post('/mobile/orderdetails', [MobileController::class, 'orderDetails']);

	Route::post('/mobile/orderdetails2', [MobileController::class, 'orderDetails2']);

	Route::post('/mobile/cancelorder', [MobileController::class, 'cancelOrder']);

	Route::post('/mobile/confirmorder', [MobileController::class, 'confirmOrder']);

	Route::post('/mobile/transitorder', [MobileController::class, 'transitOrder']);

	Route::post('/mobile/receiveorder', [MobileController::class, 'receiveOrder']);

	Route::post('/mobile/rateexperience', [MobileController::class, 'rateExperience']);

	Route::post('/mobile/getratings', [MobileController::class, 'getRatings']);

	Route::post('/mobile/getreviews', [MobileController::class, 'getReviews']);

	Route::post('/mobile/getcompany', [MobileController::class, 'getCompanyByID']);

	Route::post('/mobile/getuser', [MobileController::class, 'getUserByID']);

	Route::post('/mobile/coupons', [MobileController::class, 'getCoupons']);

	Route::post('/mobile/usecoupon', [MobileController::class, 'useCoupon']);

	Route::post('/mobile/addfavorite', [MobileController::class, 'addFavorite']);

	Route::post('/mobile/favorites', [MobileController::class, 'getFavorites']);

	Route::post('/mobile/removefavorite', [MobileController::class, 'removeFavorite']);

	Route::get('/mobile/gettopfood', [MobileController::class, 'getTopFood']);

	Route::get('/mobile/gettoprestaurant', [MobileController::class, 'getTopRestaurant']);

	Route::post('/mobile/getridernotifications', [MobileController::class, 'getRiderNotifications']);

	Route::post('/mobile/updateridernotifications', [MobileController::class, 'updateRiderNotifications']);

	Route::post('/mobile/getcustomernotifications', [MobileController::class, 'getCustomerNotifications']);

	Route::post('/mobile/updatecustomernotifications', [MobileController::class, 'updateCustomerNotifications']);

	Route::post('/mobile/countcartnotifications', [MobileController::class, 'countCartNotifications']);

	Route::post('/mobile/getuserdetails', [MobileController::class, 'getUserDetails']);

	Route::post('/mobile/getriderdetails', [MobileController::class, 'getRiderDetails']);


	Route::get('/test/email', function () {
    	return view('thankyou');
	});

});
