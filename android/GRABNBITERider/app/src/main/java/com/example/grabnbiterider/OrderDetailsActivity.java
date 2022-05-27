package com.example.grabnbiterider;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.content.ContextCompat;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.graphics.drawable.ColorDrawable;
import android.graphics.drawable.Drawable;
import android.os.Bundle;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.MapView;
import com.google.android.gms.maps.OnMapReadyCallback;
import com.google.android.gms.maps.model.BitmapDescriptor;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.MarkerOptions;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class OrderDetailsActivity extends AppCompatActivity implements OnMapReadyCallback {
    SessionManager sessionManager;
    private String user_id;
    private TextView tv_order_no;
    private TextView tv_date;
    private TextView tv_address;
    private List<Product> productList;
    private ListView listView;
    private ProductAdapter adapter;
    private double subtotal = 0;
    private double total = 0;
    private TextView tv_subtotal_value;
    private TextView tv_delivery_fee_value;
    private TextView tv_total_value;
    private TextView tv_recipient_name;
    private TextView tv_recipient_contact;
    private TextView tv_status;
    private TextView tv_status_date;
    private TextView tv_coupon_value;
    private TextView tv_coupon_display;
    private TextView tv_coupon_display_value;
    private TextView tv_new_total_value;
    private Button btnConfirm;
    private Button btnTransit;
    private int orderno;
    private GoogleMap mMap;
    private MapView mapView;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_order_details);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        //for the session values
        sessionManager = new SessionManager(this);
        sessionManager.checkLogin();
        HashMap<String, String> user = sessionManager.getUserDetails();
        user_id = user.get(sessionManager.USER_ID);

        tv_order_no = findViewById(R.id.tv_order_no);
        tv_date = findViewById(R.id.tv_date);
        tv_address = findViewById(R.id.tv_address);
        listView = findViewById(R.id.list);
        tv_subtotal_value = findViewById(R.id.tv_subtotal_value);
        tv_delivery_fee_value = findViewById(R.id.tv_delivery_fee_value);
        tv_total_value = findViewById(R.id.tv_total_value);
        tv_recipient_name = findViewById(R.id.recipient_name);
        tv_recipient_contact = findViewById(R.id.recipient_contact);
        tv_coupon_value = findViewById(R.id.tv_coupon_value);
        tv_coupon_display = findViewById(R.id.tv_coupon_display);
        tv_coupon_display_value = findViewById(R.id.tv_coupon_display_value);
        tv_new_total_value = findViewById(R.id.tv_new_total_value);
        mapView = findViewById(R.id.map);
        btnConfirm = findViewById(R.id.confirm_order);
        btnTransit = findViewById(R.id.transit_order);
        tv_status = findViewById(R.id.status);
        tv_status_date = findViewById(R.id.status_date);

        productList = new ArrayList<>();
        adapter = new ProductAdapter(productList, OrderDetailsActivity.this);

        Intent intent = getIntent();
        orderno = intent.getIntExtra(OrderAdapter.ORDER_NO, 0);

//        currentLatitude = Double.parseDouble(HomeActivity.getmInstanceActivity().getCurrentLatitude());
//        currentLongitude = Double.parseDouble(HomeActivity.getmInstanceActivity().getCurrentLongitude());
        mapView.onCreate(savedInstanceState);
        mapView.getMapAsync(this);
        getOrderDetails();

        btnConfirm.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                AlertDialog.Builder builder = new AlertDialog.Builder(OrderDetailsActivity.this);

                builder.setTitle("Confirm Order");
                builder.setMessage("Are you sure you want to confirm this order?");

                builder.setPositiveButton("YES", new DialogInterface.OnClickListener() {

                    public void onClick(DialogInterface dialog, int which) {
                        // Do nothing but close the dialog
                        confirm();
                        dialog.dismiss();
                    }
                });

                builder.setNegativeButton("NO", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int i) {
                        dialog.dismiss();
                    }
                });

                AlertDialog alert = builder.create();
                alert.show();
            }
        });

        btnTransit.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                transit();
            }
        });
    }

    public void getOrderDetails() {

        String url = "http://192.168.137.1:8000/mobile/orderdetails2";

        StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            Log.e("Products: ", response);
                            JSONObject jsonObject = new JSONObject(response);
                            String success = jsonObject.getString("success");
                            String coupon_desc = jsonObject.getString("coupon_desc");
                            String coupon_type = jsonObject.getString("coupon_type");
                            double currentLatitude = HomeActivity.getmInstanceActivity().getCurrentLatitude();
                            double currentLongitude = HomeActivity.getmInstanceActivity().getCurrentLongitude();
                            double recipientLat = jsonObject.getDouble("latitude");
                            double recipientLng = jsonObject.getDouble("longitude");
                            double businessLat = jsonObject.getDouble("business_lat");
                            double businessLng = jsonObject.getDouble("business_lng");
                            double delivery_fee = jsonObject.getDouble("delivery_fee");
                            double coupon_value = jsonObject.getInt("coupon_value");
                            double discount = 0;
                            double new_total = 0;
                            int coupon_constraint = jsonObject.getInt("coupon_constraint");
                            String businessName = jsonObject.getString("business_name");
                            String status = jsonObject.getString("status");

                            if (success.equals("1")) {

                                if(!coupon_desc.equals("")) {

                                    tv_coupon_value.setVisibility(TextView.VISIBLE);
                                    tv_coupon_value.setText(coupon_desc);

                                    tv_coupon_display.setVisibility(TextView.VISIBLE);
                                    tv_coupon_display_value.setVisibility(TextView.VISIBLE);

                                    tv_new_total_value.setVisibility(TextView.VISIBLE);

                                    tv_total_value.setPaintFlags(tv_total_value.getPaintFlags() | Paint.STRIKE_THRU_TEXT_FLAG);
                                } else {

                                    tv_coupon_value.setVisibility(TextView.GONE);
                                    tv_coupon_value.setText("");

                                    tv_coupon_display.setVisibility(TextView.GONE);
                                    tv_coupon_display_value.setVisibility(TextView.GONE);

                                    tv_new_total_value.setVisibility(TextView.GONE);

                                    tv_total_value.setPaintFlags(tv_total_value.getPaintFlags() & (~ Paint.STRIKE_THRU_TEXT_FLAG));
                                }

                                JSONArray jsonArray = jsonObject.getJSONArray("data");

                                for (int i = 0; i < jsonArray.length(); i++) {

                                    JSONObject object = jsonArray.getJSONObject(i);

                                    double price = object.getDouble("price");
                                    int qty = object.getInt("qty");
                                    double addons_total = object.getDouble("total");

                                    subtotal += (price * qty) + addons_total;

                                    Product product = new Product();
                                    product.setId(object.getInt("id"));
                                    product.setName(object.getString("name"));
                                    product.setAddons(object.getString("addons"));
                                    product.setPrice(object.getDouble("price"));
                                    product.setQty(object.getInt("qty"));
                                    product.setTotal(object.getDouble("total"));

                                    productList.add(product);
                                }
                                adapter = new ProductAdapter(productList, OrderDetailsActivity.this);
                                listView.setAdapter(adapter);
                                adapter.notifyDataSetChanged();

                                total = subtotal + delivery_fee;

                                if(coupon_type.equals("Fixed")) {
                                    discount = coupon_value;
                                    new_total = (subtotal + delivery_fee) - discount;
                                    if(new_total < 0) {
                                        tv_new_total_value.setVisibility(TextView.GONE);
                                        total = 0;
                                        tv_total_value.setPaintFlags(tv_total_value.getPaintFlags() & (~Paint.STRIKE_THRU_TEXT_FLAG));
                                    } else {
                                        tv_new_total_value.setVisibility(TextView.VISIBLE);
                                        tv_total_value.setPaintFlags(tv_total_value.getPaintFlags() | Paint.STRIKE_THRU_TEXT_FLAG);
                                    }
                                } else if(coupon_type.equals("Percent off")) {
                                    discount = (coupon_value / 100) * subtotal;
                                    new_total = (subtotal + delivery_fee) - discount;
                                    if(new_total < 0) {
                                        tv_new_total_value.setVisibility(TextView.GONE);
                                        total = 0;
                                        tv_total_value.setPaintFlags(tv_total_value.getPaintFlags() & (~Paint.STRIKE_THRU_TEXT_FLAG));
                                    } else {
                                        tv_new_total_value.setVisibility(TextView.VISIBLE);
                                        tv_total_value.setPaintFlags(tv_total_value.getPaintFlags() | Paint.STRIKE_THRU_TEXT_FLAG);
                                    }

                                } else if(coupon_type.equals("Minimum")) {

                                    if(subtotal < coupon_constraint) {

                                        tv_coupon_value.setVisibility(TextView.GONE);
                                        tv_coupon_value.setText("");

                                        tv_coupon_display.setVisibility(TextView.GONE);
                                        tv_coupon_display_value.setVisibility(TextView.GONE);
                                        tv_new_total_value.setVisibility(TextView.GONE);

                                        tv_total_value.setPaintFlags(tv_total_value.getPaintFlags() & (~ Paint.STRIKE_THRU_TEXT_FLAG));

                                    } else {

                                        tv_coupon_value.setVisibility(TextView.VISIBLE);
                                        tv_coupon_value.setText(coupon_desc);

                                        tv_coupon_display.setVisibility(TextView.VISIBLE);
                                        tv_coupon_display_value.setVisibility(TextView.VISIBLE);

                                        discount = coupon_value;
                                        new_total = (subtotal+delivery_fee) - discount;

                                        if(new_total < 0) {
                                            tv_new_total_value.setVisibility(TextView.GONE);
                                            total = 0;
                                            tv_total_value.setPaintFlags(tv_total_value.getPaintFlags() & (~Paint.STRIKE_THRU_TEXT_FLAG));
                                        } else {
                                            tv_new_total_value.setVisibility(TextView.VISIBLE);
                                            tv_total_value.setPaintFlags(tv_total_value.getPaintFlags() | Paint.STRIKE_THRU_TEXT_FLAG);
                                        }
                                    }
                                } else {
                                    // nothing
                                }

                                tv_coupon_display.setText("Coupon("+coupon_desc+")");
                                tv_coupon_display_value.setText("-₱" + String.format("%.2f", discount));

                                // marker for your location
//                                LatLng latLng = new LatLng(currentLatitude, currentLongitude);
//                                MarkerOptions markerOptions = new MarkerOptions().position(latLng).title(HomeActivity.getmInstanceActivity().getLocation(OrderDetailsActivity.this, currentLatitude, currentLongitude));
//                                mMap.addMarker(markerOptions);
                                mMap.moveCamera(CameraUpdateFactory.newLatLngZoom(new LatLng(currentLatitude, currentLongitude), 12.1f));
                                mMap.setMyLocationEnabled(true);

                                // marker for recipient
                                LatLng latLng2 = new LatLng(recipientLat, recipientLng);
                                MarkerOptions markerOptions2 = new MarkerOptions().position(latLng2).icon(BitmapDescriptorFactory.defaultMarker(BitmapDescriptorFactory.HUE_RED)).title(HomeActivity.getmInstanceActivity().getLocation(OrderDetailsActivity.this, recipientLat, recipientLng));
                                mMap.addMarker(markerOptions2);

                                // marker for business
                                LatLng latLng3 = new LatLng(businessLat, businessLng);
                                MarkerOptions markerOptions3 = new MarkerOptions().position(latLng3).icon(BitmapDescriptorFactory.defaultMarker(BitmapDescriptorFactory.HUE_GREEN)).title(businessName);
                                mMap.addMarker(markerOptions3);

                                tv_order_no.setText("Order#" + jsonObject.getInt("orderno"));
                                tv_date.setText("Placed on " + jsonObject.getString("date"));
                                tv_address.setText(HomeActivity.getmInstanceActivity().getLocation(OrderDetailsActivity.this, recipientLat, recipientLng));

                                tv_recipient_name.setText(jsonObject.getString("recipient_name"));
                                tv_recipient_contact.setText(jsonObject.getString("recipient_contact"));

                                tv_subtotal_value.setText("₱" + String.format("%.2f", subtotal));
                                tv_delivery_fee_value.setText("₱" + String.format("%.2f", delivery_fee));
                                tv_total_value.setText("₱" + String.format("%.2f", total));
                                tv_new_total_value.setText("₱" + String.format("%.2f", new_total));

                                tv_status.setText(status);

                                if(status.equals("Pending")) {
                                    tv_status.setTextColor(Color.parseColor("#5cb85c"));
                                    tv_status_date.setVisibility(TextView.GONE);
                                    btnConfirm.setVisibility(Button.VISIBLE);
                                    btnTransit.setVisibility(Button.GONE);
                                } else if(status.equals("Confirmed")) {
                                    tv_status.setTextColor(Color.parseColor("#5cb85c"));
                                    tv_status_date.setText("Confirmed on " + jsonObject.getString("confirmed_date"));
                                    tv_status_date.setVisibility(TextView.VISIBLE);
                                    btnTransit.setVisibility(Button.VISIBLE);
                                    btnConfirm.setVisibility(Button.GONE);
                                } else if(status.equals("Delivered")) {
                                    tv_status.setTextColor(Color.parseColor("#5cb85c"));
                                    tv_status_date.setText("Delivered on " + jsonObject.getString("delivered_date"));
                                    tv_status_date.setVisibility(TextView.VISIBLE);
                                    btnTransit.setVisibility(Button.GONE);
                                    btnConfirm.setVisibility(Button.GONE);
                                } else {
                                    tv_status.setTextColor(Color.parseColor("#5cb85c"));
                                    tv_status_date.setVisibility(TextView.GONE);
                                    btnTransit.setVisibility(Button.GONE);
                                    btnConfirm.setVisibility(Button.GONE);
                                }

                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
//                            Toast.makeText(getApplicationContext(), "Failed" + e.toString(), Toast.LENGTH_SHORT).show();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        error.printStackTrace();
                        Toast.makeText(getApplicationContext(), "Failed" + error.toString(), Toast.LENGTH_SHORT).show();
                    }
                }) {
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
                params.put("orderno", String.valueOf(orderno));

                return params;
            }
        };
        //RequestQueue requestQueue = Volley.newRequestQueue(this);
        //requestQueue.add(stringRequest);
        AppController.getmInstance().addToRequestQueue(stringRequest);
    }

    public void confirm() {

        final ProgressDialog progressDialog;
        progressDialog = createProgressDialog(OrderDetailsActivity.this);
        progressDialog.show();
        String url = "http://192.168.137.1:8000/mobile/confirmorder";
        StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            Log.e("Products: ", response);
                            JSONObject jsonObject = new JSONObject(response);
                            String success = jsonObject.getString("success");
                            String message = jsonObject.getString("message");

                            if (success.equals("1")) {
                                progressDialog.dismiss();

                                AlertDialog.Builder builder = new AlertDialog.Builder(OrderDetailsActivity.this);

                                builder.setTitle("Message");
                                builder.setMessage(message);

                                builder.setPositiveButton("OK", new DialogInterface.OnClickListener() {

                                    public void onClick(DialogInterface dialog, int which) {
                                        // Do nothing but close the dialog
                                        finish();
                                        dialog.dismiss();
                                    }
                                });

                                AlertDialog alert = builder.create();
                                alert.show();

                            }
                        } catch (JSONException e) {
                            progressDialog.dismiss();
                            e.printStackTrace();
                            Toast.makeText(getApplicationContext(), "Failed" + e.toString(), Toast.LENGTH_SHORT).show();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        progressDialog.dismiss();
                        error.printStackTrace();
                        Toast.makeText(getApplicationContext(), "Failed" + error.toString(), Toast.LENGTH_SHORT).show();
                    }
                }) {
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
                params.put("user_id", user_id);
                params.put("orderno", String.valueOf(orderno));

                return params;
            }
        };
        //RequestQueue requestQueue = Volley.newRequestQueue(this);
        //requestQueue.add(stringRequest);
        AppController.getmInstance().addToRequestQueue(stringRequest);
    }

    public void transit() {

        final ProgressDialog progressDialog;
        progressDialog = createProgressDialog(OrderDetailsActivity.this);
        progressDialog.show();
        String url = "http://192.168.137.1:8000/mobile/transitorder";
        StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            Log.e("Products: ", response);
                            JSONObject jsonObject = new JSONObject(response);
                            String success = jsonObject.getString("success");
                            String message = jsonObject.getString("message");

                            if (success.equals("1")) {
                                progressDialog.dismiss();

                                AlertDialog.Builder builder = new AlertDialog.Builder(OrderDetailsActivity.this);

                                builder.setTitle("Message");
                                builder.setMessage(message);

                                builder.setPositiveButton("OK", new DialogInterface.OnClickListener() {

                                    public void onClick(DialogInterface dialog, int which) {
                                        // Do nothing but close the dialog
                                        finish();
                                        dialog.dismiss();
                                    }
                                });

                                AlertDialog alert = builder.create();
                                alert.show();

                            }
                        } catch (JSONException e) {
                            progressDialog.dismiss();
                            e.printStackTrace();
                            Toast.makeText(getApplicationContext(), "Failed" + e.toString(), Toast.LENGTH_SHORT).show();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        progressDialog.dismiss();
                        error.printStackTrace();
                        Toast.makeText(getApplicationContext(), "Failed" + error.toString(), Toast.LENGTH_SHORT).show();
                    }
                }) {
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
                params.put("orderno", String.valueOf(orderno));

                return params;
            }
        };
        //RequestQueue requestQueue = Volley.newRequestQueue(this);
        //requestQueue.add(stringRequest);
        AppController.getmInstance().addToRequestQueue(stringRequest);
    }

    public static ProgressDialog createProgressDialog(Context context) {
        ProgressDialog dialog = new ProgressDialog(context);
        try {
            dialog.show();
        } catch (WindowManager.BadTokenException e) {

        }
        dialog.setCancelable(false);
        dialog.getWindow()
                .setBackgroundDrawable(new ColorDrawable(android.graphics.Color.TRANSPARENT));
        dialog.setContentView(R.layout.progressdialog);
        // dialog.setMessage(Message);
        return dialog;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                //action
                finish();
                break;
        }
        return super.onOptionsItemSelected(item);
    }

    @Override
    public void onMapReady(@NonNull GoogleMap googleMap) {
        mMap = googleMap;
    }

    @Override
    public void onResume() {
        mapView.onResume();
        super.onResume();
    }

    @Override
    public void onPause() {
        super.onPause();
        mapView.onPause();
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        mapView.onDestroy();
    }

    @Override
    public void onLowMemory() {
        super.onLowMemory();
        mapView.onLowMemory();
    }

//    private BitmapDescriptor bitmapDescriptorFromVector(Context context, int vectorResId) {
//        Drawable vectorDrawable = ContextCompat.getDrawable(context, vectorResId);
//        vectorDrawable.setBounds(0, 0, vectorDrawable.getIntrinsicWidth(), vectorDrawable.getIntrinsicHeight());
//        Bitmap bitmap = Bitmap.createBitmap(vectorDrawable.getIntrinsicWidth(), vectorDrawable.getIntrinsicHeight(), Bitmap.Config.ARGB_8888);
//        Canvas canvas = new Canvas(bitmap);
//        vectorDrawable.draw(canvas);
//        return BitmapDescriptorFactory.fromBitmap(bitmap);
//    }
}
