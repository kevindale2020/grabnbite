<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Role;
use App\Models\Company;
use App\Models\Product;
use App\Models\Cart;
use App\Models\AddOn;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Rating;
use App\Models\Coupon;
use App\Models\Notification;
use App\Models\Report;
use Auth;
use PDF;
use App;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");

    }
    public function index() {

        return view('admin.index');
    }

    public function account() {

        return view('admin.account');
    }

    public function getProfile() {

        $user = User::find(Auth::user()->id);

            if($user) {
                return response()->json([
                    'image' => $user->image,
                    'fname' => $user->fname,
                    'lname' => $user->lname,
                    'address' => $user->address,
                    'email' => $user->email,
                    'phone' => $user->phone
                ]);
            }   
    }

    public function upload(Request $request) {

        if($request->ajax()) {
            
            $imageName = Auth::user()->id.'.'.$request->image->extension();

            $request->image->move(public_path('images/users'), $imageName);

            $user = User::find(Auth::user()->id);

            if($user!==null) {

                $user->image = $imageName;

                if($user->save()) {
                    return response()->json(['success'=>1]);
                }
            } else {

                $user = new User([
                    'image' => $imageName
                ]);

                if($user->save()) {
                    return response()->json(['success'=>1]);
                }
            }
        }
    }

    public function saveProfile(Request $request) {

        if($request->ajax()) {

            $user = User::find(Auth::user()->id);
            $user->fname = $request->get('fname');
            $user->lname = $request->get('lname');
            $user->address = $request->get('address');
            $user->email = $request->get('email');
            $user->phone = $request->get('phone');
            $user->updated_at = Carbon::now();


            if($user->save()) {
                return response()->json(['success'=>1]);
            }

        }
    }

    public function getUsers(Request $request) {

        $users = User::with('roles')->withTrashed()->whereHas('roles', function($q){
            $q->where('name', '!=', 'Superadmin');
        })->get();

        if($request->ajax()) {

            return response()->json(['users'=>$users]);       
        }

        return view('admin.users');
    }

    public function deleteUser(Request $request) {

        if($request->ajax()) {

            $id = $request->get('id');

            $user = User::find($id);

            if($user->delete()) {

                return response()->json(['success'=>1]);
            }
        }
    }

    public function restoreUser(Request $request) {

        if($request->ajax()) {

            $id = $request->get('id');

            $user = User::withTrashed()->where('id', $id)->restore();

            if($user) {

                return response()->json(['success'=>1]);
            }
        }
    }

    public function companyProfile(Request $request) {

        return view('admin.company');
    }

    public function saveCompany(Request $request) {

        if($request->ajax()) {

            $name = $request->get('cname');
            $contact = $request->get('ccontact');
            $desc = $request->get('description');
            $location = $request->get('location');
            $lat = $request->get('lat');
            $lng = $request->get('lng');

            $validator = Company::where('user_id', Auth::user()->id)->first();

            // add company
            if($validator===null) {

                // file upload
                $filename = "";
                $filename2 = "";
                $filename3 = "";
                $filename4 = "";

                if($request->hasFile('doc1')) {

                    $file = $request->file('doc1');
                    $filename = $file->getClientOriginalName();
                    $filename = strtolower($filename);
                    $destinationPath = public_path('documents');
                    $imagePath = $destinationPath. "/".  $filename;
                    $file->move($destinationPath, $filename);
                }

                if($request->hasFile('doc2')) {

                    $file = $request->file('doc2');
                    $filename2 = $file->getClientOriginalName();
                    $filename2 = strtolower($filename2);
                    $destinationPath = public_path('documents');
                    $imagePath = $destinationPath. "/".  $filename2;
                    $file->move($destinationPath, $filename2);
                }

                if($request->hasFile('doc3')) {

                    $file = $request->file('doc3');
                    $filename3 = $file->getClientOriginalName();
                    $filename3 = strtolower($filename3);
                    $destinationPath = public_path('documents');
                    $imagePath = $destinationPath. "/".  $filename3;
                    $file->move($destinationPath, $filename3);
                }

                if($request->hasFile('doc4')) {

                    $file = $request->file('doc4');
                    $filename4 = $file->getClientOriginalName();
                    $filename4 = str_replace(' ', '-', $filename4);
                    $filename4 = strtolower($filename4);
                    $destinationPath = public_path('documents');
                    $imagePath = $destinationPath. "/".  $filename4;
                    $file->move($destinationPath, $filename4);
                }

                 $company = new Company([
                    'user_id' => Auth::user()->id,
                    'name' => $name,
                    'contact' => $contact,
                    'description' => $desc,
                    'location' => $location,
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'business_permit' => ($filename!="") ? $filename : '',
                    'dti_cert' => ($filename2!="") ? $filename2 : '',
                    'dti_form' => ($filename3!="") ? $filename3 : '',
                    'valid_id' => ($filename4!="") ? $filename4 : ''
                ]);

                if($company->save()) {

                    return response()->json(['success'=>1]);
                }
            // edit company
            } else {

                $company = Company::where('user_id', Auth::user()->id)->first();

                 // file upload
                $filename = "";
                $filename2 = "";
                $filename3 = "";
                $filename4 = "";

                if($request->hasFile('doc1')) {

                    $file = $request->file('doc1');
                    $filename = $file->getClientOriginalName();
                    $filename = strtolower($filename);
                    $destinationPath = public_path('documents');
                    $imagePath = $destinationPath. "/".  $filename;
                    $file->move($destinationPath, $filename);
                }

                if($request->hasFile('doc2')) {

                    $file = $request->file('doc2');
                    $filename2 = $file->getClientOriginalName();
                    $filename2 = strtolower($filename2);
                    $destinationPath = public_path('documents');
                    $imagePath = $destinationPath. "/".  $filename2;
                    $file->move($destinationPath, $filename2);
                }

                if($request->hasFile('doc3')) {

                    $file = $request->file('doc3');
                    $filename3 = $file->getClientOriginalName();
                    $filename3 = strtolower($filename3);
                    $destinationPath = public_path('documents');
                    $imagePath = $destinationPath. "/".  $filename3;
                    $file->move($destinationPath, $filename3);
                }

                if($request->hasFile('doc4')) {

                    $file = $request->file('doc4');
                    $filename4 = $file->getClientOriginalName();
                    $filename4 = str_replace(' ', '-', $filename4);
                    $filename4 = strtolower($filename4);
                    $destinationPath = public_path('documents');
                    $imagePath = $destinationPath. "/".  $filename4;
                    $file->move($destinationPath, $filename4);
                }

                $company->name = $name;
                $company->contact = $contact;
                $company->description = $desc;
                $company->location = $location;
                $company->latitude = $lat;
                $company->longitude = $lng;
                $company->business_permit = ($filename!="") ? $filename : $company->business_permit;
                $company->dti_cert = ($filename2!="") ? $filename2 : $company->dti_cert;
                $company->dti_form = ($filename3!="") ? $filename3 : $company->dti_form;
                $company->valid_id = ($filename4!="") ? $filename4 : $company->valid_id;

                if($company->save()) {

                    return response()->json(['success'=>1]);
                }
            }
        }
    }

    public function getCompany() {

        $company =  Company::where('user_id', Auth::user()->id)->first();

        if($company===null) {
            
            return response()->json([

                'success' => 0,
            ]);
        } else {

             return response()->json([
                'success' => 1,
                'image' => $company->image,
                'contact' => $company->contact,
                'name' => $company->name,
                'desc' => $company->description,
                'location' => $company->location,
                'lat' => $company->latitude,
                'lng' => $company->longitude,
                'business_permit' => $company->business_permit,
                'dti_cert' => $company->dti_cert,
                'dti_form' => $company->dti_form,
                'valid_id' => $company->valid_id
            ]);
        }   
    }

     public function uploadLogo(Request $request) {

        if($request->ajax()) {
            
            $imageName = Auth::user()->id.'.'.$request->image->extension();

            $request->image->move(public_path('images/company'), $imageName);

            $company = Company::where('user_id', Auth::user()->id)->first();

            if($company!==null) {

                 $company->image = $imageName;

                if($company->save()) {
                    return response()->json(['success'=>1]);
                }
            } else {

                $company = new Company([
                    'image' => $imageName,
                    'user_id' => Auth::user()->id
                ]);

                if($company->save()) {
                    return response()->json(['success'=>1]);
                }
            }
        }
    }

    public function changePassword(Request $request) {

        if($request->ajax()) {

            $id = Auth::user()->id;
            // $current = Hash::make($request->get('current'));
            $current = $request->get('current');
            $newpassword = Hash::make($request->get('newpass'));

            $user = User::where('id', $id)->first();

            if(Hash::check($current, $user->password)) {

                $user->password = $newpassword;
                $user->updated_at = Carbon::now();
                $user->save();

                return response()->json(['success'=>1]);
            } else {
                
                return response()->json(['success'=>0]);
            }
        }
    }

    public function product() {

        return view('admin.product');
    }

    public function getProducts() {

        $products = Product::where('company_id', Auth::user()->company->id)->get();

        return response()->json(['products'=>$products]);
    }

    public function addProduct(Request $request) {

        if($request->ajax()) {

            $imageName = $request->get('product-name').'.'.$request->image->extension();

            $request->image->move(public_path('images/products'), $imageName);

            $company_id = Auth::user()->company->id;

            $validator = Product::where('name', $request->get('product-name'))->where('company_id', $company_id)->first();

            if($validator===null) {

                $product = new Product([
                    'company_id' => Auth::user()->company->id,
                    'image' => $imageName,
                    'name' => $request->get('product-name'),
                    'description' => $request->get('product-desc'),
                    'price' => $request->get('product-price'),
                    'qty' => $request->get('product-qty'),
                    'addon_id' => $request->get('product-addons'),
                    'category_id' => $request->get('product-category')
                ]);

                if($product->save()) {

                    return response()->json(['success'=>1]);
                }   

            } else {

                return response()->json(['success'=>0]);
            }
        }
    }

    public function getProductByID($id) {

        $product = Product::find($id);

        $addons = AddOn::orderBy('name', 'ASC')->get();

        $categories = Category::all();

        $current_category = Category::where('id', $product->category_id)->first();

        return response()->json(['details'=>$product, 'addons'=>$addons, 'categories'=>$categories, 'current'=>$current_category]);
    }

    public function editProduct(Request $request) {

         if($request->ajax()) {

            $id = $request->get('current-product-id');

            $product = Product::find($id);

            $imageName = '';

            if($request->hasfile('image')) {

                File::delete('images/products/'.$product->image);

                $imageName = $request->get('product-name').'.'.$request->image->extension();

                $request->image->move(public_path('images/products'), $imageName);

            } else {

                $imageName = $product->image;
            }

            // $location = $request->get('edit-event-street').' '.$request->get('event-city').' '.$request->get('event-stateprov').' '.$request->get('event-postalcode');

            // $validator = Product::where('name', $request->get('product-name'))->first();


            $product->image = $imageName;
            $product->name = $request->get('product-name');
            $product->description = $request->get('product-desc');
            $product->price = $request->get('product-price');
            $product->qty = $request->get('product-qty');
            $product->addon_id = $request->get('product-addons') ? $request->get('product-addons') : '';
            $product->category_id = $request->get('product-category');
            
            if($product->save()) {

                return response()->json(['success'=>1]);
            }
        }
    }

    public function deleteProduct(Request $request) {

        if($request->ajax()) {

            $id = $request->get('id');

            $product = Product::find($id);

            if($product->delete()) {

                return response()->json(['success'=>1]);
            }

        }
    }

    public function addOns(Request $request) {

        if($request->ajax()) {

            $name = $request->get('product-name');
            $price = $request->get('product-price');
            $qty = $request->get('product-qty');

            $validator = AddOn::where('name', $name)->first();

            if($validator!==null) {

                return response()->json(['success'=>0]);
            } else {

                $addon = new AddOn([

                    'name' => $name,
                    'price' => $price,
                    'qty' => $qty
                ]);

                if($addon->save()) {

                    return response()->json(['success'=>1]);
                }
            }
        }
    }

    public function getAddOns() {

        // $addons = AddOn::orderBy('id', 'DESC')->get();

        $addons = AddOn::all();

        return response()->json(['addons'=>$addons]);
    }

    public function getAddOnByID($id) {

        $addon = AddOn::find($id);

        return response()->json(['details'=>$addon]);
    }

    public function editAddOn(Request $request) {

        if($request->ajax()) {

            $id = $request->get('current-addon-id');
            $name = $request->get('product-name');
            $price = $request->get('product-price');
            $qty = $request->get('product-qty');

            $addon = AddOn::find($id);

            $addon->name = $name;
            $addon->price = $price;
            $addon->qty = $qty;

            if($addon->save()) {

                return response()->json(['success'=>1]);
            }

        }
    }

     public function deleteAddOn(Request $request) {

        if($request->ajax()) {

            $id = $request->get('id');

            $addon = AddOn::find($id);

            if($addon->delete()) {

                return response()->json(['success'=>1]);
            }

        }
    }

    public function loadAddOns(Request $request) {

        if($request->ajax()) {

            $addons = AddOn::orderBy('name', 'ASC')->get();

            return response()->json(['addons'=>$addons]);
        }
    }

    public function loadCategories(Request $request) {

        if($request->ajax()) {

            $categories = Category::all();

            return response()->json(['categories'=>$categories]);
        }
    }

    public function userDetails($id) {

        $user = User::with('company')->with('roles')->with('driver')->find($id);

        // company reviews
        $company = Company::where('user_id', $id)->first();
        $reviews = [];

        if($company!==null) {
             $company_reviews = Rating::where('subject_id', $company->id)->get();

             foreach($company_reviews as $res) {

                $row['date'] = $res->rated_date;
                $row['name'] = $res->user->fname.' '.$res->user->lname;
                $row['feedback'] = $res->feedback;
                $row['rate'] = $res->rate;
                $reviews[] = $row;
             }
        }

        // driver reviews
        $driver_reviews = Rating::where('subject_id', $id)->get(); 
        $reviews2 = [];

        foreach($driver_reviews as $res) {

            $row['date'] = $res->rated_date;
            $row['name'] = $res->user->fname.' '.$res->user->lname;
            $row['feedback'] = $res->feedback;
            $row['rate'] = $res->rate;
            $reviews2[] = $row;
        }

    
        return response()->json(['details'=>$user, 'reviews'=>$reviews, 'reviews2'=>$reviews2]);

    }

    public function orderView() {

        return view('admin.orders');
    }

    public function getOrders() {

        $orderno = array();

        $details = OrderDetail::with('product')->whereHas('product', function($q){
            $user = User::find(Auth::user()->id);
            $q->where('company_id', '=', $user->company->id);
        })->get();

        foreach($details as $detail) {

            array_push($orderno, $detail->order_id);
        }

        $orders = Order::with('user')->whereIn('id', $orderno)->orderBy('date', 'DESC')->get();

        return response()->json(['orders'=>$orders]);


    }

    public function getOrderByID($id) {

        $order = Order::with('user')->where('id', $id)->first();

        foreach($order->orderDetails as $detail) {

            $addon_id = explode(',', $detail->addon_id);

                $addons = AddOn::whereIn('id', $addon_id)->get();

                // $result = array();

                $str_addons = "";
                $total_addons = 0;

                $qty = $detail->qty;

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

            $row['id'] = $detail->product->id;
            $row['image'] = $detail->product->image;
            $row['name'] = $detail->product->name;
            $row['price'] = $detail->product->price;
            $row['qty'] = $detail->qty;
            $row['addons'] = ($str_addons!="") ? substr($str_addons, 2) : '';
            $row['total'] = $total_addons;

            $data[] = $row;
        }

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

        return response()->json([
            'orderno'=>$order->id,
            'delivery_fee'=>$order->delivery_fee,
            'fname'=>$order->user->fname, 
            'lname' => $order->user->lname,
            'date' => date("F j, Y", strtotime($order->date)),
            'location_str' => $order->location_str,
            'cancel_reason' => $order->cancel_reason,
            'cancelled_date' => date("F j, Y", strtotime($order->cancelled_date)),
            'status' => $order->status,
            'coupon_id'=>$coupon_id, 
            'coupon_code'=>$coupon_code, 
            'coupon_desc'=>$coupon_desc, 
            'coupon_value'=>$coupon_value, 
            'coupon_type'=>$coupon_type, 
            'coupon_constraint'=>$coupon_constraint,
            'details'=>$data
        ]);

    }

    public function reviewPage() {

        return view('admin.reviews');
    }

    public function getReviews(Request $request) {

        if($request->ajax()) {

            $subject_id = Auth::user()->company->id;

            $reviews = Rating::with('user')->where('subject_id', $subject_id)->orderBy('rated_date', 'DESC')->get();

            if($reviews) {

                return response()->json(['reviews'=>$reviews]);
            }
        }
    }

    public function deleteReview(Request $request) {

        if($request->ajax()) {

            $id = $request->get('id');

            $review = Rating::find($id);

            if($review->delete()) {

                return response()->json(['success'=>1]);
            }

        }
    }

    public function couponPage() {

        return view('admin.coupons');
    }

    public function addCoupon(Request $request) {

        if($request->ajax()) {

            $code = $request->get('coupon-code');
            $desc = $request->get('coupon-desc');
            $value = $request->get('coupon-value');
            $constraint = ($request->get('coupon-constraint')!=null) ? $request->get('coupon-constraint') : 0;
            $type = $request->get('coupon-type');

            $validator = Coupon::where('code', $code)->where('company_id', Auth::user()->company->id)->first();

            if($validator!==null) {

                return response()->json(['success'=>0]);
            } else {

                $coupon = new Coupon([
                    'company_id' => Auth::user()->company->id,
                    'code' => $code,
                    'description' => $desc,
                    'value' => $value,
                    'constraint' => $constraint,
                    'type' => $type
                ]);

                if($coupon->save()) {

                    return response()->json(['success'=>1]);
                }
            }


        }
    }

    public function getCoupons(Request $request) {

        if($request->ajax()) {

            $coupons = Coupon::where('company_id', Auth::user()->company->id)->orderBy('id', 'ASC')->get();

            if($coupons) {

                return response()->json(['coupons'=>$coupons]);
            }
        }
    }

    public function getCouponByID($id) {

        $coupon = Coupon::find($id);

        if($coupon) {

            return response()->json(['details'=>$coupon]);
        }
    }

    public function editCoupon(Request $request) {

        if($request->ajax()) {

            $id = $request->get('current-coupon-id');

            $coupon = Coupon::find($id);

            $coupon->code = $request->get('coupon-code');
            $coupon->description = $request->get('coupon-desc');
            $coupon->value = $request->get('coupon-value');
            $coupon->constraint = $request->get('coupon-constraint');
            $coupon->type = $request->get('coupon-type');

            if($coupon->save()) {

                return response()->json(['success'=>1]);
            }
        }
    }

    public function deleteCoupon(Request $request) {

         if($request->ajax()) {

            $id = $request->get('id');

            $coupon = Coupon::find($id);

            if($coupon->delete()) {

                return response()->json(['success'=>1]);
            }

        }
    }

    public function disableCoupon(Request $request) {

         if($request->ajax()) {

            $id = $request->get('id');

            $coupon = Coupon::find($id);

            $coupon->is_enabled = 0;

            if($coupon->save()) {

                return response()->json(['success'=>1]);
            }

        }
    }

    public function enableCoupon(Request $request) {

         if($request->ajax()) {

            $id = $request->get('id');

            $coupon = Coupon::find($id);

            $coupon->is_enabled = 1;

            if($coupon->save()) {

                return response()->json(['success'=>1]);
            }

        }
    }

    public function getReports() {

        return view('admin.reports');
    }

    public function getReportByDate(Request $request) {

        $start = $request->get('start_date');
        $end = $request->get('end_date');

        $total = 0;
        $total_addons = 0;
        $str_addons = "";
        $subtotal = 0;
        $discount = 0;
        $final_total = 0;

        $format_start = date("Y-m-d H:i:s", strtotime($start));
        $format_end = date("Y-m-d H:i:s", strtotime($end));

        $orderno = array();

        $details = OrderDetail::with('product')->whereHas('product', function($q){
            $user = User::find(Auth::user()->id);
            $q->where('company_id', '=', $user->company->id);
        })->get();

        foreach($details as $detail) {

            array_push($orderno, $detail->order_id);
        }

        $orders = Order::where('status', 'Delivered')->whereBetween('date', [$format_start, $format_end])->orderBy('date', 'ASC')->whereIn('id', $orderno)->get();

        if(count($orders) > 0) {

            foreach($orders as $order) {

                $row['date'] = date("Y-m-d", strtotime($order->date));
                $row['id'] = $order->id;
                $row['fname'] = $order->user->fname;
                $row['lname'] = $order->user->lname;

                    foreach($order->orderDetails as $detail) {

                        $price = $detail->product->price;
                        $qty = $detail->qty;

                         $addon_id = explode(',', $detail->addon_id);

                         $addons = AddOn::whereIn('id', $addon_id)->get();

                         $qty = $detail->qty;

                        foreach($addons as $addon) {

                            if($qty > 1) {
                                $total_addons += $addon->price * $qty;
                            } else {
                                $total_addons += $addon->price;
                            }

                            $str_addons .= ", $addon->name";
                        }

                        $total += $price * $qty;
                    }

                $delivery_fee = $order->delivery_fee;

                $subtotal = $total + $total_addons;

                $coupon = Coupon::find($order->coupon_id);

                if($coupon!==null) {

                    if($coupon->type=='Fixed') {

                        $final_total = $subtotal - $coupon->value;

                    } else if($coupon->type=='Percent off') {

                        $discount = ($coupon->value / 100) * $subtotal;
                        $final_total = $subtotal - $discount;
                    } else if($coupon->type=='Minimum') {

                        if($subtotal >= $coupon->constraint) {

                            $final_total = $subtotal - $coupon->value;
                        }
                    } else {

                        //
                    }
                } else {

                    $final_total = $subtotal;
                }

                $row['total'] = $final_total + $delivery_fee;

                $data[] = $row;

                $total = 0;

                $total_addons = 0;
            }

            return response()->json(['success'=>1, 'reports'=>$data]);
        } else {

            return response()->json(['success'=>0]);
        }
    }

    public function convertPDF($start, $end) {

        $total = 0;
        $total_addons = 0;
        $str_addons = "";
        $subtotal = 0;
        $discount = 0;
        $final_total = 0;
        $total_val = 0;

        $format_start = date("Y-m-d H:i:s", strtotime($start));
        $format_end = date("Y-m-d H:i:s", strtotime($end));

        $orderno = array();

        $details = OrderDetail::with('product')->whereHas('product', function($q){
            $user = User::find(Auth::user()->id);
            $q->where('company_id', '=', $user->company->id);
        })->get();

        foreach($details as $detail) {

            array_push($orderno, $detail->order_id);
        }

        $orders = Order::where('status', 'Delivered')->whereBetween('date', [$format_start, $format_end])->orderBy('date', 'ASC')->whereIn('id', $orderno)->get();

        if(count($orders) > 0) {

            foreach($orders as $order) {

                $row['date'] = date("Y-m-d", strtotime($order->date));
                $row['id'] = $order->id;
                $row['fname'] = $order->user->fname;
                $row['lname'] = $order->user->lname;

                    foreach($order->orderDetails as $detail) {

                        $price = $detail->product->price;
                        $qty = $detail->qty;

                         $addon_id = explode(',', $detail->addon_id);

                         $addons = AddOn::whereIn('id', $addon_id)->get();

                        
                         $qty = $detail->qty;

                        foreach($addons as $addon) {

                            if($qty > 1) {
                                $total_addons += $addon->price * $qty;
                            } else {
                                $total_addons += $addon->price;
                            }
                            
                            $str_addons .= ", $addon->name";
                        }

                        $total += $price * $qty;
                    }

                $delivery_fee = $order->delivery_fee;

                $subtotal = $total + $total_addons;

                $coupon = Coupon::find($order->coupon_id);

                if($coupon!==null) {

                    if($coupon->type=='Fixed') {

                        $final_total = $subtotal - $coupon->value;

                    } else if($coupon->type=='Percent off') {

                        $discount = ($coupon->value / 100) * $subtotal;
                        $final_total = $subtotal - $discount;
                    } else if($coupon->type=='Minimum') {

                        if($subtotal >= $coupon->constraint) {

                            $final_total = $subtotal - $coupon->value;
                        }
                    } else {

                        //
                    }
                } else {
                     $final_total = $subtotal;
                }

                $row['total'] = str_replace(',', '', number_format($final_total + $delivery_fee, 2));
                $total_val += $final_total + $delivery_fee;

                $data[] = $row;

                $total = 0;

                $total_addons = 0;
            }

            $report = array(
                'start' => $start,
                'end' => $end,
                'results' => $data,
                'total' => str_replace(',', '', number_format($total_val, 2))
            );

            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('admin.myReport', compact('report'));
            $pdf->render();

            return $pdf->stream();
        }
    }

    public function getNotifications(Request $request) {

        if($request->ajax()) {

            $notifications = Notification::where('user_id', Auth::user()->id)->orderBy('date', 'DESC')->get();

            $counter = 0;
            $overall = 0;

            foreach($notifications as $notification) {

                if($notification->is_seen==0) {
                    $counter++;
                }

                $overall++;
            }

            if($notifications!==null) {

                return response()->json(['success'=>1, 'rows'=>$counter, 'overall'=>$overall, 'notifications'=>$notifications]);
            } else {
                
                return response()->json(['success'=>0]);
            }
        }
    }

    public function updateNotification(Request $request) {

        if($request->ajax()) {

            $post = $request->get('admin-update-notification');

            if($post==1) {

                $notification = Notification::where('user_id', Auth::user()->id)->where('is_seen', 0)->update(['is_seen' => 1]);

                if($notification) {

                    return response()->json(['success'=>1]);
                }
            }
        }
    }

    public function loadChart(Request $request) {

        if($request->ajax()) {

            $total = 0;
            $total_addons = 0;
            $str_addons = "";
            $subtotal = 0;
            $discount = 0;
            $final_total = 0;

            $orderno = array();

            $details = OrderDetail::with('product')->whereHas('product', function($q){
                $user = User::find(Auth::user()->id);
                $q->where('company_id', '=', $user->company->id);
            })->get();

            foreach($details as $detail) {

                array_push($orderno, $detail->order_id);
            }

            $orders = Order::where('status', 'Delivered')->orderBy('date', 'ASC')->whereIn('id', $orderno)->get();

            if(count($orders) > 0) {

                foreach($orders as $order) {

                    $row['date'] = $order->date;

                        foreach($order->orderDetails as $detail) {

                            $price = $detail->product->price;
                            $qty = $detail->qty;

                             $addon_id = explode(',', $detail->addon_id);

                             $addons = AddOn::whereIn('id', $addon_id)->get();

                            
                            $qty = $detail->qty;

                            foreach($addons as $addon) {

                                if($qty > 1) {
                                    $total_addons += $addon->price * $qty;
                                } else {
                                    $total_addons += $addon->price;
                                }
                                
                                $str_addons .= ", $addon->name";
                            }

                            $total += $price * $qty;
                        }

                    $delivery_fee = $order->delivery_fee;

                    $subtotal = $total + $total_addons;

                    $coupon = Coupon::find($order->coupon_id);

                    if($coupon!==null) {

                        if($coupon->type=='Fixed') {

                            $final_total = $subtotal - $coupon->value;

                        } else if($coupon->type=='Percent off') {

                            $discount = ($coupon->value / 100) * $subtotal;
                            $final_total = $subtotal - $discount;
                        } else if($coupon->type=='Minimum') {

                            if($subtotal >= $coupon->constraint) {

                                $final_total = $subtotal - $coupon->value;
                            }
                        } else {

                            //
                        }
                    } else {

                        $final_total = $subtotal;
                    }

                    $row['total'] = $final_total + $delivery_fee;

                    $data[] = $row;

                    $total = 0;

                    $total_addons = 0;

                    $clear = Report::where('company_id', Auth::user()->company->id);
                    $clear->delete();

                    foreach($data as $row) {

                        $report = new Report([
                            'date' => $row['date'],
                            'total_earnings' => $row['total'],
                            'company_id' => Auth::user()->company->id
                        ]);

                        $report->save();
                       
                    }

                    $reports = Report::select(
                                DB::raw('sum(total_earnings) as value'), 
                                DB::raw("DATE_FORMAT(date,'%M') as months")
                    )->groupBy('months')->where('company_id', Auth::user()->company->id)->get();

                    $months = [];
                    $monthValues = [];
                    // $total_values = 0;

                    foreach($reports as $report) {

                        $months[] = $report->months;
                        $monthValues[] = $report->value;
                    }

                }

                return response()->json(['success'=>1, 'reports'=>$reports, 'months'=>$months, 'values'=>$monthValues]);
            } else {

                return response()->json(['success'=>0]);
            }

        }
    }

    public function loadSummary(Request $request) {

        if($request->ajax()) {

            // count number of products
            $products = Product::where('company_id', Auth::user()->company->id)->get();

            // count completed orders
            $orderno = array();

            $details = OrderDetail::with('product')->whereHas('product', function($q){
                $user = User::find(Auth::user()->id);
                $q->where('company_id', '=', $user->company->id);
            })->get();

            foreach($details as $detail) {

                array_push($orderno, $detail->order_id);
            }

            $orders = Order::where('status', 'Delivered')->orderBy('date', 'ASC')->whereIn('id', $orderno)->get();

            // get overall or average ratings
            $ratings = Rating::where('subject_id', Auth::user()->company->id)->get();

            $sum = 0;
            $avg = 0;
            $count = 0;

            foreach($ratings as $rating) {

                $sum += $rating->rate;
                $count++;

            }

            $avg = $sum / $count;

            // count total earnings
            $reports = Report::where('company_id', Auth::user()->company->id)->get();

            $total_earnings = 0;

            foreach($reports as $report) {

                $total_earnings += $report->total_earnings;
            }


            return response()->json(['success'=>1, 'products'=>count($products), 'orders'=>count($orders), 'ratings'=>number_format($avg, 1), 'earnings'=>$total_earnings]);
        }
    }

    public function loadSummary2(Request $request) {

        if($request->ajax()) {

            $customers = User::with('roles')->withTrashed()->whereHas('roles', function($q){
                $q->where('name', '=', 'User');
            })->get();

            $riders = User::with('roles')->withTrashed()->whereHas('roles', function($q){
                $q->where('name', '=', 'Driver');
            })->get();

            $merchants = User::with('roles')->withTrashed()->whereHas('roles', function($q){
                $q->where('name', '=', 'Admin');
            })->get();

            $users = User::with('roles')->withTrashed()->whereHas('roles', function($q){
                $q->where('name', '!=', 'Superadmin');
            })->get();

            return response()->json(['success'=>1, 'customers'=>count($customers), 'riders'=>count($riders), 'merchants'=>count($merchants), 'users'=>count($users)]);
        }
    }

    public function allRatings() {

        return view('admin.ratings');
    }

    public function getMerchantRatings(Request $request) {

        if($request->ajax()) {

             $merchants = DB::select('select c.id, concat(u.fname," ",u.lname) as name, c.name as business, avg(r.rate) as value from users u inner join companies c on u.id = c.user_id inner join ratings r on r.subject_id = c.id where r.subject_type="Seller" group by name order by value desc');

             if($merchants) {

                 return response()->json(['success'=>1, 'merchants'=>$merchants]);
             }
        }
    }

    public function getRiderRatings(Request $request) {

        if($request->ajax()) {

             $merchants = DB::select('select u.id, concat(u.fname," ",u.lname) as name, avg(r.rate) as value from users u inner join ratings r on r.subject_id = u.id where r.subject_type="Driver" group by name order by value desc');

             if($merchants) {

                 return response()->json(['success'=>1, 'merchants'=>$merchants]);
             }
        }
    }

    public function getMerchantRatingsByID($id) {

        $reviews = [];

        $company = Company::find($id);

        $company_reviews = Rating::where('subject_id', $id)->get();

        foreach($company_reviews as $res) {

            $row['date'] = $res->rated_date;
            $row['name'] = $res->user->fname.' '.$res->user->lname;
            $row['feedback'] = $res->feedback;
            $row['rate'] = $res->rate;
            $reviews[] = $row;
        }

        if($company_reviews) {

            return response()->json(['reviews'=>$reviews, 'label'=>$company->name]);
        }
    }

     public function getRiderRatingsByID($id) {

        $reviews = [];

        $user = User::find($id);

        $rider_reviews = Rating::where('subject_id', $id)->get();

        foreach($rider_reviews as $res) {

            $row['date'] = $res->rated_date;
            $row['name'] = $res->user->fname.' '.$res->user->lname;
            $row['feedback'] = $res->feedback;
            $row['rate'] = $res->rate;
            $reviews[] = $row;
        }

        if($rider_reviews) {

            return response()->json(['reviews'=>$reviews, 'label'=>$user->fname.' '.$user->lname]);
        }
    }
}
