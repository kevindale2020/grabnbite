<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Mail\NotifyMail;
use Carbon\Carbon;
use Auth;
use Mail;
use App\Models\Company;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\AddOn;
use App\Models\Driver;
use App\Models\Rating;
use App\Models\Coupon;
use App\Models\Favorite;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;


class MobileController extends Controller
{

    public function register(Request $request) {

        $vkey = bin2hex(random_bytes(32));
        $email = $request->get('email');
        $password = $request->get('password');

        $user = new User([
            'email' => $email,
            'password' => Hash::make($password),
            'vkey' => $vkey
        ]);

        // check if email exists
        $validator = User::where('email', $email)->first();

        if($validator===null) {

            // saves user to the db
            $user->save();

            $userRole = User::find($user->id);

            $role = new Role(['name' => 'User']);

            $userRole->roles()->save($role);

            $data = [
                'vkey' => $vkey
            ];

            $notification = new Notification([

                'user_id' => 2, //must be the user id of superadmin
                'type' => 'Registration',
                'subject' => 'Registration',
                'content' => 'User ID # '.$user->id.' registered to the system',
                'date' => Carbon::now()

            ]);

            $notification->save();

            Mail::to($email)->send(new NotifyMail($data));

            return response()->json(['success'=>'1', 'message'=>'Thanks for signing up! A verification link has been sent to your email. Please verify your account.']);
        } else {
            return response()->json(['success'=>'0', 'message'=>'Email already exists']);
        }
    }

    public function register2(Request $request) {

        $vkey = bin2hex(random_bytes(32));
        $email = $request->get('email');
        $password = $request->get('password');

        $user = new User([
            'email' => $email,
            'password' => Hash::make($password),
            'vkey' => $vkey
        ]);

        // check if email exists
        $validator = User::where('email', $email)->first();

        if($validator===null) {

            // saves user to the db
            $user->save();

            $userRole = User::find($user->id);

            $role = new Role(['name' => 'Driver']);

            $userRole->roles()->save($role);

            $data = [
                'vkey' => $vkey
            ];

            $notification = new Notification([

                'user_id' => 2, //must be the user id of superadmin
                'type' => 'Registration',
                'subject' => 'Registration',
                'content' => 'User ID # '.$user->id.' registered to the system',
                'date' => Carbon::now()

            ]);

            $notification->save();

            Mail::to($email)->send(new NotifyMail($data));

            return response()->json(['success'=>'1', 'message'=>'Thanks for signing up! A verification link has been sent to your email. Please verify your account.']);
        } else {
            return response()->json(['success'=>'0', 'message'=>'Email already exists']);
        }
    }

    public function login(Request $request) {

        // $remember = $request->has('remember') ? true : false; 

        $data = array(
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        );

        if(auth()->attempt($data)) {
            
            foreach(Auth::user()->roles as $role) {
                $roleName = $role->name;
            }

            if($roleName=='User') {
                return response()->json(['success'=>'1', 'message'=>'Successfully logged in', 'id'=>Auth::user()->id, 'fname'=>Auth::user()->fname, 'lname'=>Auth::user()->lname, 'address'=>Auth::user()->address, 'phone'=>Auth::user()->phone, 'verified'=>Auth::user()->is_verified]);
            } else {
                return response()->json(['success'=>'2', 'message'=>'Successfully logged in as driver', 'id'=>Auth::user()->id, 'image'=>Auth::user()->image, 'fname'=>Auth::user()->fname, 'lname'=>Auth::user()->lname, 'verified'=>Auth::user()->is_verified]);
            }
        } else {
            return response()->json(['success'=>'0', 'message'=>'Invalid credentials']);
        }
               
    }

    public function getProfile(Request $request) {

        $id = $request->get('id');

        $user = User::find($id);

        if($user) {

             return response()->json([
                'success' => '1',
                'image' => $user->image,
                'fname' => $user->fname,
                'lname' => $user->lname,
                'address' => $user->address,
                'email' => $user->email,
                'phone' => $user->phone
            ]);
        }
    }

    public function saveProfile(Request $request) {

        $id = $request->get('id');

        $user = User::find($id);

        $uniqueid = uniqid();

        $image = $request->get('image');

        if($image!="empty") {
            $filename = Carbon::now()->format('Ymd').'_'.$uniqueid.'.png';
            $path = "images/users/".$filename;
            file_put_contents($path, base64_decode($image));
        } else {
            $filename = $user->image;
        }

         $user->image = $filename;
         $user->fname =  $request->get('fname');
         $user->lname = $request->get('lname');
         $user->address = $request->get('address');
         $user->email = $request->get('email');
         $user->phone = $request->get('phone');
         $user->updated_at = Carbon::now();

         if($user->save()) {

            return response()->json(['success'=>'1', 'message'=>'Successfully saved']);
         }
    }

    public function getRestaurants() {

        $companies = Company::all();

        foreach ($companies as $company) {

            $row['id'] = $company->id;
            $row['name'] = $company->name;
            $row['image'] = $company->image;
            $row['latitude'] = $company->latitude;
            $row['longitude'] = $company->longitude;

            $data[] = $row;
        }

        return response()->json(['success'=>'1', 'data'=>$data]);
    }

    public function getProducts(Request $request) {

        $id = $request->get('company_id');
        $category_id = $request->get('category_id');

        $products = Product::where('company_id', $id)->where('category_id', $category_id)->get();

        if(count($products)>0) {

             foreach ($products as $product) {
            
                $row['id'] = $product->id;
                $row['image'] = $product->image;
                $row['name'] = $product->name;
                $row['desc'] = $product->description;
                $row['price'] = $product->price;

                $data[] = $row;
            }

            return response()->json(['success'=>1, 'data'=>$data]);
        } else {
            return response()->json(['success'=>0]);
        }

    }

     public function getSmallOrders(Request $request) {

        $id = $request->get('company_id');

        $products = Product::where('company_id', $id)->where('category_id', 1)->take(5)->get();

        $overall_products = Product::where('company_id', $id)->where('category_id', 1)->get();

        $status = '';

        if(count($products)>0) {

             foreach ($products as $product) {

                $favorite = Favorite::where('product_id', $product->id)->first();
            
                $row['id'] = $product->id;
                $row['image'] = $product->image;
                $row['name'] = $product->name;
                $row['desc'] = $product->description;
                $row['price'] = $product->price;
                $row['qty'] = $product->qty;
                $row['status'] = ($favorite!==null) ? 1 : 0;

                $data[] = $row;
            }

            return response()->json(['success'=>'1', 'size'=>count($overall_products), 'data'=>$data]);
        } else {
            return response()->json(['success'=>'0', 'size'=>count($overall_products)]);
        }

    }

     public function getLargeOrders(Request $request) {

        $id = $request->get('company_id');

        $products = Product::where('company_id', $id)->where('category_id', 2)->take(5)->get();

        $overall_products = Product::where('company_id', $id)->where('category_id', 1)->get();

        if(count($products)>0) {

             foreach ($products as $product) {

                $favorite = Favorite::where('product_id', $product->id)->first();
            
                $row['id'] = $product->id;
                $row['image'] = $product->image;
                $row['name'] = $product->name;
                $row['desc'] = $product->description;
                $row['price'] = $product->price;
                $row['qty'] = $product->qty;
                $row['status'] = ($favorite!==null) ? 1 : 0;

                $data[] = $row;
            }

            return response()->json(['success'=>'1', 'size'=>count($overall_products), 'data'=>$data]);
        } else {
            return response()->json(['success'=>'0', 'size'=>count($overall_products)]);
        }

    }

    public function getProductDetails(Request $request) {

        $id = $request->get('product_id');

        $product = Product::find($id);

        $addon_id = explode(',', $product->addon_id);

        $addons = AddOn::whereIn('id', $addon_id)->get();

        foreach($addons as $addon) {

            $row['id'] = $addon->id;
            $row['name'] = $addon->name;
            $row['price'] = $addon->price;
            $data[] = $row;
        }


        if($product) {

            if(!empty($data)) 
                return response()->json(['success' => '2', 'data' => $data, 'image' => $product->image, 'name' => $product->name, 'desc' => $product->description, 'price' => $product->price]);
            
            return response()->json(['success' => '1', 'image' => $product->image, 'name' => $product->name, 'desc' => $product->description, 'price' => $product->price]);
            
        }
    }

    public function addCart(Request $request) {

        $user_id = $request->get('user_id');
        $product_id = $request->get('product_id');
        $addon_id = str_replace('empty', '', $request->get('addon_id'));
        $qty = $request->get('qty');

        $product = Product::find($product_id);

        $company_id = $product->company_id;

        $carts = Cart::all();

        foreach($carts as $cart) {

            if($cart->user_id == $user_id) {

                if($cart->product->company_id != $company_id) {

                    return response()->json(['success'=>'0', 'message'=>'You have already selected different restaurant. You can only order from one restaurant per transaction.']);
                    exit;
                }
            }
        }

        // update if product added to cart already existed
        $productExist = Cart::where('product_id', $product_id)->where('user_id', $user_id)->where('has_addons', '=', 0)->orderBy('id', 'DESC')->first();

        if($productExist!==null) {

            if($addon_id!='') {

                $addonExist = Cart::where('user_id', $user_id)->where('addon_id', $addon_id)->where('has_addons', '=', 1)->first();

                if($addonExist!==null) {

                    $addonExist->qty = $addonExist->qty + $qty;
                    $addonExist->save();
                } else {

                    $cart = new Cart(['user_id'=>$user_id, 'product_id'=>$product_id, 'qty'=>$qty, 'addon_id'=>$addon_id, 'has_addons'=>1]);
                    $cart->save();
                }

            } else {
                $productExist->qty = $productExist->qty + $qty;
                $productExist->save();
            }
            return response()->json(['success'=>'1']);
            exit;
        }

        $has_addons = ($addon_id!='') ? 1 : 0; 


        $cart = new Cart(['user_id'=>$user_id, 'product_id'=>$product_id, 'qty'=>$qty, 'addon_id'=>$addon_id, 'has_addons'=>$has_addons]);
       
        if($cart->save()) {

             return response()->json(['success'=>'1', 'addons'=>$addon_id]);
        }
    }

    public function getCart(Request $request) {

        $user_id = $request->get('user_id');

        $carts = Cart::where('user_id', $user_id)->get();

        if(count($carts)>0) {

            foreach($carts as $cart) {

                $addon_id = explode(',', $cart->addon_id);

                $addons = AddOn::whereIn('id', $addon_id)->get();

                // $result = array();

                $str_addons = "";
                $total_addons = 0;

                $qty = $cart->qty;

                foreach($addons as $addon) {

                    // $res['id'] = $addon->id;
                    // $res['name'] = $addon->name;
                    // $res['price'] = $addon->price;
                    // array_push($result, $res);
                    if($qty > 1) {
                        $total_addons += $addon->price * $qty;
                    } else {
                        $total_addons += $addon->price;
                    }
                    $str_addons .= ", $addon->name";
                }

                $row['id'] = $cart->id;
                $row['image'] = $cart->product->image;
                $row['name'] = $cart->product->name;
                $row['desc'] = $cart->product->description;
                $row['addons'] = ($str_addons!="") ? substr($str_addons, 2) : '';
                $row['price'] = $cart->product->price;
                $row['total'] = $total_addons;
                $row['qty'] = $cart->qty;
                $data[] = $row;
            }

            return response()->json(['success'=>'1', 'data'=>$data]);
        } else {
            return response()->json(['success'=>'0']);
        }

    }

    public function placeorder(Request $request) {

        $user_id = $request->get('user_id');
        $orderDate = Carbon::now();
        $currentLat = $request->get('current_latitude');
        $currentLng = $request->get('current_longitude');
        $location = $request->get('location');
        $delivery_fee = $request->get('delivery_fee');
        $coupon_id = ($request->get('coupon_id')!=0) ? $request->get('coupon_id') : 0;

        $order = new Order([
            'user_id' => $user_id,
            'date' => $orderDate,
            'status' => 'Pending',
            'latitude' => $currentLat,
            'longitude' => $currentLng,
            'location_str' => $location,
            'delivery_fee' => $delivery_fee,
            'coupon_id' => $coupon_id
        ]);

        $order->save();

        $carts = Cart::where('user_id', $user_id)->get();

        if(count($carts)>0) {

            foreach($carts as $cart) {

                $cart_id = $cart->id;
                $product_id = $cart->product->id;
                $addon_id = $cart->addon_id;
                $owner_id = $cart->product->company->user_id;
                $qty = $cart->qty;

                $product = Product::find($product_id);
                $product->qty = $product->qty - $qty;
                $product->save();

                $addon = AddOn::find($addon_id);

                if($addon!==null) {
                     $addon->qty = $addon->qty - 1;
                    $addon->save();
                }

                $orderDetail = new OrderDetail(['order_id'=>$order->id, 'product_id'=>$product_id, 'qty'=>$qty, 'addon_id'=>$addon_id]);
                $orderDetail->save();

                $deleteCart = Cart::find($cart_id);
                $deleteCart->delete();

            }
        }

        $user = User::find($user_id);

        // notify admin
        $notification = new Notification([

            'user_id' => $owner_id,
            'type' => 'Order',
            'subject' => 'Order',
            'content' => $user->fname.' '.$user->lname.' placed an order',
            'date' => Carbon::now(),

        ]);

        $notification->save();

        // notify all nearby drives
        $drivers = User::with('roles')->withTrashed()->whereHas('roles', function($q){
            $q->where('name', '=', 'Driver');
        })->get();

        foreach($drivers as $driver) {

            $notification = new Notification([

                'user_id' => $driver->id,
                'type' => 'Order',
                'subject' => 'Order',
                'content' => $user->fname.' '.$user->lname.' placed an order',
                'date' => Carbon::now(),
                'latitude' => $currentLat,
                'longitude' => $currentLng

            ]);

            $notification->save();

        }

       return response()->json(['success'=>'1', 'message'=>'You have placed your order. You may track your order in your order history']);
    }

    public function plusCart(Request $request) {

        $cart_id = $request->get('cart_id');

        $cart = Cart::find($cart_id);
        $cart->qty = $cart->qty + 1;

        if($cart->save()) {

            return response()->json(['success'=>'1']);
        }
    }

    public function deleteCart(Request $request) {

        $cart_id = $request->get('cart_id');

        $cart = Cart::find($cart_id);

        if($cart->delete()) {

            return response()->json(['success'=>'1']);
        }
    }

    public function minusCart(Request $request) {

        $cart_id = $request->get('cart_id');

        $cart = Cart::find($cart_id);
        $cart->qty = $cart->qty - 1;

        if($cart->save()) {

            return response()->json(['success'=>'1']);
        }
    }

    public function changePassword(Request $request) {

        $id = $request->get('user_id');
        $current = $request->get('current_password');
        $newpassword = Hash::make($request->get('new_password'));

        $user = User::find($id);

        if(Hash::check($current, $user->password)) {

            $user->password = $newpassword;
            $user->updated_at = Carbon::now();
            $user->save();

            return response()->json(['success'=>'1', 'message'=>'Password successfully changed']);
        } else {
                
            return response()->json(['success'=>'0', 'message'=>'This is not your current password']);
        }
    }

     public function orderSummary(Request $request) {

        $user_id = $request->get('user_id');

        $carts = Cart::where('user_id', $user_id)->get();

        $company_id = 0;

        if(count($carts)>0) {

            foreach($carts as $cart) {

                $addon_id = explode(',', $cart->addon_id);

                $addons = AddOn::whereIn('id', $addon_id)->get();

                // $result = array();

                $str_addons = "";
                $total_addons = 0;

                $qty = $cart->qty;

                foreach($addons as $addon) {

                    if($qty > 1) {
                        $total_addons += $addon->price * $qty;
                    } else {
                        $total_addons += $addon->price;
                    }
                    $str_addons .= ", $addon->name";
                }

                $company_lat = $cart->product->company->latitude;
                $company_lng = $cart->product->company->longitude;
                $company_id = $cart->product->company->id;

                $row['id'] = $cart->id;
                $row['name'] = $cart->product->name;
                $row['price'] = $cart->product->price;
                $row['qty'] = $cart->qty;
                $row['addons'] = ($str_addons!="") ? substr($str_addons, 2) : '';
                $row['total'] = $total_addons;
                $data[] = $row;
            }

            return response()->json(['success'=>'1', 'data'=>$data, 'company_id'=>$company_id, 'company_lat'=>$company_lat, 'company_lng'=>$company_lng]);
        } else {
            return response()->json(['success'=>'0']);
        }

    }

    public function saveDriver(Request $request) {

        $id = $request->get('id');
        $file = $request->get('file');
        $file2 = $request->get('file2');
        $file3 = $request->get('file3');
        $file4 = $request->get('file4');

        $validator = Driver::where('user_id', $id)->first();

        if($validator===null) {

            if($file!="empty") {

                $path = "documents/".$request->get('filename');
                file_put_contents($path, base64_decode($file));
            }

            if($file2!="empty") {

                $path = "documents/".$request->get('filename2');
                file_put_contents($path, base64_decode($file2));
            }

            if($file3!="empty") {

                $path = "documents/".$request->get('filename3');
                file_put_contents($path, base64_decode($file3));
            }

            if($file4!="empty") {

                $path = "documents/".$request->get('filename4');
                file_put_contents($path, base64_decode($file4));
            }

            $driver = new Driver([
                'user_id' => $id,
                'vehicle_type' => $request->get('type'),
                'vehicle_color' => $request->get('color'),
                'vehicle_plate_no' => $request->get('number'),
                'tin' => ($request->get('filename')!="empty") ? $request->get('filename') : '',
                'bir_form' => ($request->get('filename2')!="empty") ? $request->get('filename2') : '',
                'gov_issued_id' => ($request->get('filename3')!="empty") ? $request->get('filename3') : '',
                'driver_license' => ($request->get('filename4')!="empty") ? $request->get('filename4') : '',
            ]);

            $driver->save();

            return response()->json(['success'=>'1', 'message'=>'Successfully saved']);

        } else {

            if($file!="empty") {

                $path = "documents/".$request->get('filename');
                file_put_contents($path, base64_decode($file));
            }

            if($file2!="empty") {

                $path = "documents/".$request->get('filename2');
                file_put_contents($path, base64_decode($file2));
            }

            if($file3!="empty") {

                $path = "documents/".$request->get('filename3');
                file_put_contents($path, base64_decode($file3));
            }

            if($file4!="empty") {

                $path = "documents/".$request->get('filename4');
                file_put_contents($path, base64_decode($file4));
            }

            $driver = Driver::where('user_id', $id)->first();
            $driver->vehicle_type = $request->get('type');
            $driver->vehicle_color = $request->get('color');
            $driver->vehicle_plate_no = ($request->get('type')=='Motorcycle') ? $request->get('number') : '';
            $driver->tin = ($request->get('filename')!="empty") ? $request->get('filename') : $driver->tin;
            $driver->bir_form = ($request->get('filename2')!="empty") ? $request->get('filename2') : $driver->bir_form;
            $driver->gov_issued_id = ($request->get('filename3')!="empty") ? $request->get('filename3') : $driver->gov_issued_id;
            $driver->driver_license = ($request->get('type')=='Motorcycle') ? ($request->get('filename4')!="empty") ? $request->get('filename4') : $driver->driver_license : '';
            $driver->updated_at = Carbon::now();

            $driver->save();
            return response()->json(['success'=>'1', 'message'=>'Successfully saved']);
        }
    }

    public function getDriver(Request $request) {

        $id = $request->get('id');

        $driver = Driver::where('user_id', $id)->first();

        if($driver!==null) {
            return response()->json(['success'=>'1', 'type'=>$driver->vehicle_type, 'color'=>$driver->vehicle_color, 'number'=>$driver->vehicle_plate_no, 'file'=>$driver->tin, 'file2'=>$driver->bir_form, 'file3'=>$driver->gov_issued_id, 'file4'=>$driver->driver_license]);
        } else {
            return response()->json(['success'=>'0']);
        }
    }

    public function getOrderHistory(Request $request) {

        $id = $request->get('user_id');

        $total = 0;

        $new_total = 0;

        $percent_off = 0;

        $orders = Order::where('user_id', $id)->orderBy('date', 'DESC')->get();

        if(count($orders)>0) {

            foreach($orders as $order) {

                $coupon_id = $order->coupon_id;

                $coupon = Coupon::find($coupon_id);

                foreach($order->orderDetails as $detail) {

                    $name = $detail->product->company->name;
                    $price = $detail->product->price;
                    $qty = $detail->qty;

                    $total += $price * $qty;


                    $addon_id = explode(',', $detail->addon_id);

                    $addons = AddOn::whereIn('id', $addon_id)->get();


                    foreach($addons as $addon) {

                        if($qty > 1) {

                            $total+= $addon->price * $qty;

                        } else {

                            $total+= $addon->price;
                        }
                    }
                }


                if($coupon!==null) {

                    if($coupon->type=='Fixed') {

                        $new_total = $total - $coupon->value;

                    } else if($coupon->type=='Percent off') {

                        $percent_off = ($coupon->value / 100) * $total;
                        $new_total = $total - $percent_off;

                    } else if($coupon->type=='Minimum') {

                        if($total >= $coupon->constraint) {

                            $new_total = $total - $coupon->value;
                        }

                    } else {

                        // nothing
                    }
                } else {

                    $new_total = $total;
                }

                $row['id'] = $order->id;
                $row['name'] = $name;
                $row['user_id'] = $order->user_id;
                $row['date'] = date("F j, Y, g:i a", strtotime($order->date));
                $row['status'] = $order->status;
                $row['latitude'] = $order->latitude;
                $row['longitude'] = $order->longitude;
                $row['delivery_fee'] = $order->delivery_fee;
                $row['new_total'] = $new_total;
                $data[] = $row;

                $total = 0;
            }
            return response()->json(['success'=>'1', 'data'=>$data]);
        } else {
            return response()->json(['success'=>'0']);
        }
    }

     public function orderDetails(Request $request) {

        $orderno = $request->get('orderno');

        $order = Order::find($orderno);

        if($order!==null) {

            foreach($order->orderDetails as $res) {

                $addon_id = explode(',', $res->addon_id);

                $addons = AddOn::whereIn('id', $addon_id)->get();

                // $result = array();

                $str_addons = "";
                $total_addons = 0;

                $qty = $res->qty;

                foreach($addons as $addon) {

                    if($qty > 1) {
                        $total_addons += $addon->price * $qty;
                    } else {
                        $total_addons += $addon->price;
                    }
                    $str_addons .= ", $addon->name";
                }


                $row['id'] = $res->id;
                $row['name'] = $res->product->name;
                $row['price'] = $res->product->price;
                $row['qty'] = $res->qty;
                $row['addons'] = ($str_addons!="") ? substr($str_addons, 2) : '';
                $row['total'] = $total_addons;
                $data[] = $row;
            }

            $driver = User::find($order->driver_id);
            $driver_name = "";
            $driver_vehicle_type = "";
            $driver_vehicle_color = "";
            $driver_vehicle_plate_no = "";
            $driver_phone = "";

            if($driver!==null) {

                $driver_name = $driver->fname.' '.$driver->lname;
                $driver_vehicle_type = $driver->driver->vehicle_type;
                $driver_vehicle_color = $driver->driver->vehicle_color;
                $driver_vehicle_plate_no = $driver->driver->vehicle_plate_no;
                $driver_phone = $driver->phone;
            }

            $confirmed_date = $order->confirmed_date;
            $cancelled_date = $order->cancelled_date;
            $delivered_date = $order->delivered_date;

            $coupon = Coupon::find($order->coupon_id);


             $coupon_id = 0;
             $coupon_code = '';
             $coupon_desc = '';
             $coupon_value = 0;
             $coupon_type = '';
             $coupon_constraint = 0;

            if($coupon!==null) {

                $coupon_id = $coupon->id;
                $coupon_code = $coupon->code;
                $coupon_desc = $coupon->description;
                $coupon_value = $coupon->value;
                $coupon_type = $coupon->type;
                $coupon_constraint = $coupon->constraint;
            }

            return response()->json(['success'=>'1', 'orderno'=>$order->id, 'delivery_fee'=>$order->delivery_fee, 'date'=>date("F j, Y, g:i a", strtotime($order->date)), 'latitude'=>$order->latitude, 'longitude'=>$order->longitude, 'status'=>$order->status, 'cancel_reason'=>$order->cancel_reason, 'driver_id'=>$order->driver_id, 'driver_name'=>($driver_name!="") ? $driver_name : '', 'driver_vehicle_type'=>($driver_vehicle_type!="") ? $driver_vehicle_type : '', 'driver_vehicle_color'=>($driver_vehicle_color!="") ? $driver_vehicle_color : '', 'driver_vehicle_plate_no'=>($driver_vehicle_plate_no!="") ? $driver_vehicle_plate_no : '', 'driver_contact'=>($driver_phone!="") ? $driver_phone : '', 'confirmed_date'=>($confirmed_date!="") ? date("F j, Y, g:i a", strtotime($confirmed_date)) : '', 'cancelled_date'=>($cancelled_date!="") ? date("F j, Y, g:i a", strtotime($cancelled_date)) : '', 'delivered_date'=>($delivered_date!="") ? date("F j, Y, g:i a", strtotime($delivered_date)) : '', 'coupon_id'=>$coupon_id, 'coupon_code'=>$coupon_code, 'coupon_desc'=>$coupon_desc, 'coupon_value'=>$coupon_value, 'coupon_type'=>$coupon_type, 'coupon_constraint'=>$coupon_constraint, 'data'=>$data]);
        } else {
            return response()->json(['success'=>'0', 'test'=>$orderno]);
        }

    }

    public function getOrderHistory2() {

        $total = 0;

        $new_total = 0;

        $percent_off = 0;

        $orders = Order::where('status', 'Pending')->orWhere('status', 'Confirmed')->orWhere('status', 'In Transit')->orWhere('status', 'Delivered')->orderBy('date', 'DESC')->get();

        if(count($orders)>0) {

            foreach($orders as $order) {

                $coupon_id = $order->coupon_id;

                $coupon = Coupon::find($coupon_id);

                foreach($order->orderDetails as $detail) {

                    $name = $detail->product->company->name;
                    $price = $detail->product->price;
                    $qty = $detail->qty;

                    $total += $price * $qty;


                    $addon_id = explode(',', $detail->addon_id);

                    $addons = AddOn::whereIn('id', $addon_id)->get();


                    foreach($addons as $addon) {

                        if($qty > 1) {
                            $total+= $addon->price * $qty;
                        } else {
                            $total+= $addon->price;
                        }
                    }
                }

                if($coupon!==null) {

                    if($coupon->type=='Fixed') {

                        $new_total = $total - $coupon->value;

                    } else if($coupon->type=='Percent off') {

                        $percent_off = ($coupon->value / 100) * $total;
                        $new_total = $total - $percent_off;

                    } else if($coupon->type=='Minimum') {

                        if($total >= $coupon->constraint) {

                            $new_total = $total - $coupon->value;
                        }

                    } else {

                        // nothing
                    }
                } else {

                    $new_total = $total;
                }

                $row['id'] = $order->id;
                $row['name'] = $name;
                $row['user_id'] = $order->user_id;
                $row['date'] = date("F j, Y, g:i a", strtotime($order->date));
                $row['status'] = $order->status;
                $row['latitude'] = $order->latitude;
                $row['longitude'] = $order->longitude;
                $row['delivery_fee'] = $order->delivery_fee;
                $row['new_total'] = $new_total;
                $data[] = $row;

                $total = 0;
            }
            return response()->json(['success'=>'1', 'data'=>$data]);
        } else {
            return response()->json(['success'=>'0']);
        }
    }

    public function orderDetails2(Request $request) {

        $orderno = $request->get('orderno');

        $order = Order::find($orderno);

        $recipient_name = $order->user->fname.' '.$order->user->lname;
        $recipient_contact = $order->user->phone;

        if($order!==null) {

            foreach($order->orderDetails as $res) {

                $business_name = $res->product->company->name;
                $business_lat = $res->product->company->latitude;
                $business_lng = $res->product->company->longitude;

                $addon_id = explode(',', $res->addon_id);

                $addons = AddOn::whereIn('id', $addon_id)->get();

                // $result = array();

                $str_addons = "";
                $total_addons = 0;

                $qty = $res->qty;

                foreach($addons as $addon) {

                    if($qty > 1) {
                        $total_addons += $addon->price * $qty;
                    } else {
                        $total_addons += $addon->price;
                    }
                    $str_addons .= ", $addon->name";
                }


                $row['id'] = $res->id;
                $row['name'] = $res->product->name;
                $row['price'] = $res->product->price;
                $row['qty'] = $res->qty;
                $row['addons'] = ($str_addons!="") ? substr($str_addons, 2) : '';
                $row['total'] = $total_addons;
                $data[] = $row;
            }

            $confirmed_date = $order->confirmed_date;
            $cancelled_date = $order->cancelled_date;
            $delivered_date = $order->delivered_date;


            $coupon = Coupon::find($order->coupon_id);


             $coupon_id = 0;
             $coupon_code = '';
             $coupon_desc = '';
             $coupon_value = 0;
             $coupon_type = '';
             $coupon_constraint = 0;

            if($coupon!==null) {

                $coupon_id = $coupon->id;
                $coupon_code = $coupon->code;
                $coupon_desc = $coupon->description;
                $coupon_value = $coupon->value;
                $coupon_type = $coupon->type;
                $coupon_constraint = $coupon->constraint;
            }

            return response()->json(['success'=>'1', 'orderno'=>$order->id, 'delivery_fee'=>$order->delivery_fee, 'recipient_name'=>$recipient_name, 'recipient_contact'=>$recipient_contact, 'date'=>date("F j, Y, g:i a", strtotime($order->date)), 'latitude'=>$order->latitude, 'longitude'=>$order->longitude, 'business_name'=>$business_name, 'business_lat'=>$business_lat, 'business_lng'=>$business_lng, 'status'=>$order->status, 'confirmed_date'=>($confirmed_date!="") ? date("F j, Y, g:i a", strtotime($confirmed_date)) : '', 'cancelled_date'=>($cancelled_date!="") ? date("F j, Y, g:i a", strtotime($cancelled_date)) : '', 'delivered_date'=>($delivered_date!="") ? date("F j, Y, g:i a", strtotime($delivered_date)) : '', 'coupon_id'=>$coupon_id, 'coupon_code'=>$coupon_code, 'coupon_desc'=>$coupon_desc, 'coupon_value'=>$coupon_value, 'coupon_type'=>$coupon_type, 'coupon_constraint'=>$coupon_constraint, 'data'=>$data]);
        } else {
            return response()->json(['success'=>'0', 'test'=>$orderno]);
        }

    }

    public function cancelOrder(Request $request) {

        $orderno = $request->get('orderno');
        $cancel_reason = $request->get('cancel_reason');

        $order = Order::find($orderno);

        $order->status = 'Cancelled';
        $order->cancel_reason = $cancel_reason;
        $order->cancelled_date = Carbon::now();

        if($order->save()) {

            foreach($order->orderDetails as $detail) {

                $owner_id = $detail->product->company->user_id;
                break;
            }

            $notification = new Notification([

                'user_id' => $owner_id,
                'type' => 'Order',
                'subject' => 'Order',
                'content' => 'Order#'.$orderno.' was cancelled',
                'date' => Carbon::now()

            ]);

            $notification->save();

            return response()->json(['success'=>'1', 'message'=>'Order cancelled']);
        } else {

            return response()->json(['success'=>'0']);
        }
    }

    public function confirmOrder(Request $request) {

        $user_id = $request->get('user_id');
        $orderno = $request->get('orderno');

        $order = Order::find($orderno);

        $order->status = 'Confirmed';
        $order->driver_id = $user_id;
        $order->confirmed_date = Carbon::now();

        $user = User::find($user_id);

        if($order->save()) {

            foreach ($order->orderDetails as $detail) {

                $owner_id = $detail->product->company->user_id;
                break;
            }
            // notify admin
             $notification = new Notification([

                'user_id' => $owner_id, //must be the user id of superadmin
                'type' => 'Order',
                'subject' => 'Order',
                'content' => 'Order#'.$orderno.' is confirmed by rider '.$user->fname.' '.$user->lname,
                'date' => Carbon::now()

            ]);

             $notification->save();

            // notify customer
             $notification = new Notification([

                'user_id' => $order->user_id, //must be the user id of superadmin
                'type' => 'Order',
                'subject' => 'Order',
                'content' => 'Order#'.$orderno.' is now confirmed and will be delivered by '.$user->fname.' '.$user->lname,
                'date' => Carbon::now()

            ]);

             $notification->save();

            return response()->json(['success'=>'1', 'message'=>'Order confirmed']);
        } else {

            return response()->json(['success'=>'0']);
        }
    }

    public function transitOrder(Request $request) {

        $orderno = $request->get('orderno');

        $order = Order::find($orderno);

        $order->status = 'In Transit';

        if($order->save()) {

            foreach ($order->orderDetails as $detail) {

                $owner_id = $detail->product->company->user_id;
                break;
            }

             $notification = new Notification([

                'user_id' => $owner_id, //must be the user id of superadmin
                'type' => 'Order',
                'subject' => 'Order',
                'content' => 'Order#'.$orderno.' is now in transit',
                'date' => Carbon::now()

            ]);

             $notification->save();

             // notify customer
             $notification = new Notification([

                'user_id' => $order->user_id, //must be the user id of superadmin
                'type' => 'Order',
                'subject' => 'Order',
                'content' => 'Order#'.$orderno.' is now in transit',
                'date' => Carbon::now()

            ]);

             $notification->save();

            return response()->json(['success'=>'1', 'message'=>'Order in transit']);
        } else {

            return response()->json(['success'=>'0']);
        }
    }

    public function receiveOrder(Request $request) {

        $orderno = $request->get('orderno');

        $order = Order::find($orderno);

        $order->status = 'Delivered';
        $order->delivered_date = Carbon::now();

        if($order->save()) {

            foreach ($order->orderDetails as $detail) {

                $owner_id = $detail->product->company->user_id;
                break;
            }

            // notify admin
             $notification = new Notification([

                'user_id' => $owner_id, //must be the user id of superadmin
                'type' => 'Order',
                'subject' => 'Order',
                'content' => 'Order#'.$orderno.' has been delivered',
                'date' => Carbon::now()

            ]);

            $notification->save();

            // notify driver
             $notification = new Notification([

                'user_id' => $order->driver_id, //must be the user id of superadmin
                'type' => 'Order',
                'subject' => 'Order',
                'content' => 'Order#'.$orderno.' has been delivered',
                'date' => Carbon::now(),
                'latitude' => 0,
                'longitude' =>0

            ]);

            $notification->save();


            return response()->json(['success'=>'1', 'message'=>'Order delivered']);
        } else {

            return response()->json(['success'=>'0']);
        }
    }

    public function rateExperience(Request $request) {

        $user_id = $request->get('user_id');
        $subject_id = $request->get('subject_id');
        $rate = $request->get('rate');
        $feedback = $request->get('feedback');
        $type = $request->get('type');

        $rating = new Rating([
            'subject_id' => $subject_id,
            'user_id' => $user_id,
            'rate' => $rate,
            'feedback' => $feedback,
            'rated_date' => Carbon::now(),
            'subject_type' => $type
        ]);

        if($rating->save()) {

            $user = User::find($user_id);
            $company = Company::find($subject_id);

            if($company!==null) {

                // notify admin
                $notification = new Notification([

                    'user_id' => $company->user_id,
                    'type' => 'Ratings and Reviews',
                    'subject' => 'Ratings and Reviews',
                    'content' => $user->fname.' '.$user->lname.' submitted a review',
                    'date' => Carbon::now()

                ]);

                $notification->save();
            }

            // notify driver
            $notification = new Notification([

                'user_id' => $subject_id,
                'type' => 'Ratings and Reviews',
                'subject' => 'Ratings and Reviews',
                'content' => $user->fname.' '.$user->lname.' submitted a review',
                'date' => Carbon::now(),
                'latitude' => 0,
                'longitude' => 0

            ]);

            $notification->save();

            return response()->json(['success'=>'1', 'message'=>'Thank you for taking the time to give us your valuable feedback']);
        } else {
            return response()->json(['success'=>'0']);
        }
        // $rating->save(); 
        // return response()->json(['success'=>'1', 'test'=>$rate, 'test2'=>$feedback, 'driver_id'=>$driver_id, 'user_id'=>$user_id]);
    }

    public function getReviews(Request $request) {

        $user_id = $request->get('user_id');

        $ratings = Rating::where('subject_id', $user_id)->where('subject_type', 'Driver')->orderBy('rated_date', 'DESC')->get();

        $numReviews = count($ratings);

        $sum = 0;
        $overall_rating = 0;

        if($numReviews > 0) {
            foreach($ratings as $rating) {

            $sum+=$rating->rate;

            $row['id'] = $rating->id;
            $row['image'] = $rating->user->image;
            $row['fname'] = $rating->user->fname;
            $row['lname'] = $rating->user->lname;
            $row['feedback'] = $rating->feedback;
            $row['date'] = date("F j, Y, g:i a", strtotime($rating->rated_date));
            $row['rate'] = $rating->rate;
            $data[] = $row;

            }

            $overall_rating = $sum / $numReviews;

            return response()->json(['success'=>'1', 'overall_rating'=>$overall_rating, 'num_reviews'=>$numReviews, 'data'=>$data]);
        } else {
             return response()->json(['success'=>'0', 'overall_rating'=>$overall_rating, 'num_reviews'=>$numReviews]);
        }
    }

     public function getRatings(Request $request) {

        $company_id = $request->get('company_id');

        $ratings = Rating::where('subject_id', $company_id)->where('subject_type', 'Seller')->get();

        $numReviews = count($ratings);

        $sum = 0;
        $overall_rating = 0;

        if($numReviews > 0) {

            foreach($ratings as $rating) {

                $sum+=$rating->rate;
            }

            $overall_rating = $sum / $numReviews;

            return response()->json(['success'=>'1', 'overall_rating'=>$overall_rating, 'num_reviews'=>$numReviews]);
        } else {
            return response()->json(['success'=>'0', 'overall_rating'=>$overall_rating, 'num_reviews'=>$numReviews]);
        }
    }

    public function getCompanyByID(Request $request) {

        $company_id = $request->get('company_id');

        $company = Company::find($company_id);

        if($company) {

           $ratings = Rating::where('subject_id', $company_id)->where('subject_type', 'Seller')->orderBy('rated_date', 'DESC')->get();

           $counter = count($ratings);

           $sum = 0;
           $overall_rating = 0;

           $data = array();

           if($counter > 0) {

                foreach($ratings as $rating) {

                    $sum+=$rating->rate;

                    $row['id'] = $rating->id;
                    $row['image'] = $rating->user->image;
                    $row['fname'] = $rating->user->fname;
                    $row['lname'] = $rating->user->lname;
                    $row['feedback'] = $rating->feedback;
                    $row['date'] = date("F j, Y, g:i a", strtotime($rating->rated_date));
                    $row['rate'] = $rating->rate;
                    $data[] = $row;

                }

                $overall_rating = $sum / $counter;
           }

            return response()->json([
                'success'=>'1', 
                'name'=>$company->name, 
                'location'=>$company->location, 
                'latitude'=>$company->latitude, 
                'longitude'=>$company->longitude,
                'contact'=>$company->contact,
                'num_reviews'=>$counter,
                'overall_rating'=>$overall_rating,
                'data'=>$data
            ]);
        } else {

             return response()->json(['success'=>'0']);
        }

    }

    public function getUserByID(Request $request) {

        $user_id = $request->get('user_id');

        $user = User::find($user_id);

        if($user) {

            return response()->json(['success'=>'1', 'image'=>$user->image, 'fname'=>$user->fname, 'lname'=>$user->lname]);
        } else {

            return response()->json(['success'=>'0']);
        }
    }

    public function getCoupons(Request $request) {

        $id = $request->get('company_id');

        $company = Company::find($id);

        if($company!==null) {

            if(count($company->coupons) > 0) {
                foreach ($company->coupons as $coupon) {
            
                    $row['id'] = $coupon->id;
                    $row['image'] = $company->image;
                    $row['desc'] = $coupon->description;
                    $row['status'] = ($coupon->is_enabled==1) ? 'Available'  : 'Not Available';
                    $data[] = $row;
                }

                return response()->json(['success'=>'1', 'data'=>$data]);
            } else {

                return response()->json(['success'=>'0']);
            }
        } else {
            return response()->json(['success'=>'0']);
        }
    }

    public function useCoupon(Request $request) {

        $id = $request->get('coupon_id');

        $coupon = Coupon::find($id);

        if($coupon) {

            return response()->json(['success'=>'1', 'coupon_id'=>$coupon->id, 'coupon_code'=>$coupon->code, 'coupon_desc'=>$coupon->description, 'coupon_value'=>$coupon->value, 'coupon_constraint'=>$coupon->constraint, 'coupon_type'=>$coupon->type, 'coupon_status'=>($coupon->is_enabled==1) ? 'Available'  : 'Not Available']);
        }
    }

    public function addFavorite(Request $request) {

        $user_id = $request->get('user_id');
        $product_id = $request->get('product_id');

        $favorite = new Favorite([

            'user_id' => $user_id,
            'product_id' => $product_id,
            'date_added' => Carbon::now()
        ]);

        if($favorite->save()) {

            return response()->json(['success'=>'1', 'message'=>'Added to favorites']);
        }
    }

    public function getFavorites(Request $request) {

        $user_id = $request->get('user_id');

        $favorites = Favorite::where('user_id', $user_id)->orderBy('date_added', 'DESC')->get();

         if(count($favorites)>0) {

             foreach ($favorites as $favorite) {
            
                $row['id'] = $favorite->product->id;
                $row['image'] = $favorite->product->image;
                $row['name'] = $favorite->product->name;
                $row['desc'] = $favorite->product->description;
                $row['price'] = $favorite->product->price;

                $data[] = $row;
            }

            return response()->json(['success'=>1, 'data'=>$data]);
        } else {
            return response()->json(['success'=>0]);
        }
    }

    public function removeFavorite(Request $request) {

        $user_id = $request->get('user_id');
        $product_id = $request->get('product_id');

        $favorite = Favorite::where('user_id', $user_id)->where('product_id', $product_id)->first();

        if($favorite->delete()) {

            return response()->json(['success'=>'1', 'message'=>'Removed from favorites']);
        }
    }

    public function getTopFood() {

        // $order = OrderDetail::with()->groupBy('product_id')->select('id', 'product_id', DB::raw('count(product_id) as total'))->get();

        $productIDs = array();

        $products = DB::select('select o.id, od.product_id, count(od.product_id) as total from orders o inner join order_details od on o.id = od.order_id where o.status = "Delivered" and yearweek(o.date, 1) = yearweek(curdate(), 1) group by od.product_id order by total desc limit 10');

        foreach($products as $product) {

            array_push($productIDs, $product->product_id);
        }

        $productids_ordered = implode(',', $productIDs);

        if($productids_ordered!="") {

            $results = Product::whereIn('id', $productIDs)->orderByRaw("FIELD(id, $productids_ordered)")->get();

            if(count($results)>0) {

                 foreach ($results as $res) {
                
                    $row['id'] = $res->id;
                    $row['image'] = $res->image;
                    $row['name'] = $res->name;
                    $row['desc'] = $res->description;
                    $row['price'] = $res->price;
                    $row['qty'] = $res->qty;

                    $data[] = $row;
                }

                return response()->json(['success'=>'1', 'data'=>$data]);

            }
             
        } else {
            return response()->json(['success'=>'0']);
        }
    }

    public function getTopRestaurant() {

        $companyIDs = array();

        $companies = DB::select('select c.id, avg(rate) as Average from companies c inner join ratings r on c.id = r.subject_id where r.subject_type = "Seller" group by c.id order by Average desc');

        foreach($companies as $company) {

            array_push($companyIDs, $company->id);
        }

        $companyids_ordered = implode(',', $companyIDs);

        if($companyids_ordered!="") {

             $results = Company::whereIn('id', $companyIDs)->orderByRaw("FIELD(id, $companyids_ordered)")->get();

             if(count($results)>0) {

                foreach ($results as $res) {
                
                    $row['id'] = $res->id;
                    $row['name'] = $res->name;
                    $row['image'] = $res->image;
                    $row['latitude'] = $res->latitude;
                    $row['longitude'] = $res->longitude;
                    $data[] = $row;

                }

                return response()->json(['success'=>'1', 'data'=>$data]);

             }

        } else {
            return response()->json(['success'=>'0']);
        }
    }

    public function getRiderNotifications(Request $request) {

        $id = $request->get('user_id');

        $notifications = Notification::where('user_id', $id)->where('is_seen', 0)->get();

        if(count($notifications) > 0) {

            foreach($notifications as $notification) {

                $row['id'] = $notification->id;
                $row['type'] = $notification->type;
                $row['subject'] = $notification->subject;
                $row['content'] = $notification->content;
                $row['latitude'] = $notification->latitude;
                $row['longitude'] = $notification->longitude;
                $data[] = $row;
            }

            return response()->json(['success'=>'1', 'data'=>$data]);

        } else {

            return response()->json(['success'=>'0']);
        }
    }

    public function updateRiderNotifications(Request $request) {

        $id = $request->get('user_id');

        $notification = Notification::where('user_id', $id)->where('is_seen', 0)->update(['is_seen' => 1]);

        if($notification) {

            return response()->json(['success'=>'1']);
        } else {

            return response()->json(['success'=>'0']);
        }
    }

     public function getCustomerNotifications(Request $request) {

        $id = $request->get('user_id');

        $notifications = Notification::where('user_id', $id)->where('is_seen', 0)->get();

        if(count($notifications) > 0) {

            foreach($notifications as $notification) {

                $row['id'] = $notification->id;
                $row['type'] = $notification->type;
                $row['subject'] = $notification->subject;
                $row['content'] = $notification->content;

                $data[] = $row;
            }

            return response()->json(['success'=>'1', 'data'=>$data]);

        } else {

            return response()->json(['success'=>'0']);
        }
    }

    public function updateCustomerNotifications(Request $request) {

        $id = $request->get('user_id');

        $notification = Notification::where('user_id', $id)->where('is_seen', 0)->update(['is_seen' => 1]);

        if($notification) {

            return response()->json(['success'=>'1']);
        } else {

            return response()->json(['success'=>'0']);
        }
    }

    public function countCartNotifications(Request $request) {

        $id = $request->get('user_id');

        $cart = Cart::where('user_id', $id)->get();

        if($cart) {

            return response()->json(['success'=>'1', 'counter'=>count($cart)]);
        } else {

            return response()->json(['success'=>'0']);
        }
    }

    public function distance($lat1, $lon1, $lat2, $lon2, $unit) {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    public function getUserDetails(Request $request) {

        $id = $request->get('user_id');

        $user = User::find($id);

        if($user) {
            return response()->json(['success'=>'1', 'fname'=>$user->fname, 'lname'=>$user->lname, 'phone'=>$user->phone]);
        } else {
            return response()->json(['success'=>'0']);
        }
    }

    public function getRiderDetails(Request $request) {

        $id = $request->get('user_id');

        $user = User::find($id);

        if($user) {
            return response()->json(['success'=>'1', 'fname'=>$user->fname, 'lname'=>$user->lname, 'phone'=>$user->phone, 'type'=>$user->driver->vehicle_type, 'color'=>$user->driver->vehicle_color]);
        } else {
            return response()->json(['success'=>'0']);
        }

    }
}
