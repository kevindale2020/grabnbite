package com.example.grabnbiterider.ui.history;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.drawable.ColorDrawable;
import android.location.Location;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.WindowManager;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AlertDialog;
import androidx.cardview.widget.CardView;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentTransaction;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.example.grabnbiterider.AppController;
import com.example.grabnbiterider.HomeActivity;
import com.example.grabnbiterider.Order;
import com.example.grabnbiterider.OrderAdapter;
import com.example.grabnbiterider.R;
import com.example.grabnbiterider.SessionManager;
import com.example.grabnbiterider.ui.profile.ProfileFragment;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class HistoryFragment extends Fragment {
    private SessionManager sessionManager;
    private String user_id;
    private List<Order> orderList;
    private ListView listView;
    private SwipeRefreshLayout refreshLayout;
    private OrderAdapter adapter;
    private CardView label;
    private TextView tv_empty;
    private double currentLatitude;
    private double currentLongitude;

    public View onCreateView(@NonNull LayoutInflater inflater,
                             ViewGroup container, Bundle savedInstanceState) {

        View root = inflater.inflate(R.layout.fragment_history, container, false);

        //for the session values
        sessionManager = new SessionManager(getContext());
        sessionManager.checkLogin();
        HashMap<String, String> user = sessionManager.getUserDetails();
        user_id = user.get(sessionManager.USER_ID);

        listView = root.findViewById(R.id.list);
        orderList = new ArrayList<>();
        adapter = new OrderAdapter(orderList, getContext());
        refreshLayout = root.findViewById(R.id.swipe_refresh_layout);
        label = root.findViewById(R.id.cardview_label);
        tv_empty = root.findViewById(R.id.tv_empty);

        currentLatitude = HomeActivity.getmInstanceActivity().getCurrentLatitude();
        currentLongitude = HomeActivity.getmInstanceActivity().getCurrentLongitude();

        refreshLayout.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
                orderList.clear();
                getOrderHistory();
                refreshLayout.setRefreshing(false);
            }
        });

        getRiderDetails();

        return root;
    }

    public void getRiderDetails() {

        final ProgressDialog progressDialog;
        progressDialog = createProgressDialog(getContext());
        progressDialog.show();
        String url = "http://192.168.137.1:8000/mobile/getriderdetails";
        StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            Log.e("Products: ", response);
                            JSONObject jsonObject = new JSONObject(response);
                            String success = jsonObject.getString("success");
                            String fname = jsonObject.getString("fname");
                            String lname = jsonObject.getString("lname");
                            String phone = jsonObject.getString("phone");
                            String type = jsonObject.getString("type");
                            String color = jsonObject.getString("color");

                            if (success.equals("1")) {
                                progressDialog.dismiss();

                                if (fname.isEmpty() || lname.isEmpty() || phone.isEmpty() || type.isEmpty() || color.isEmpty()) {

                                    AlertDialog.Builder builder = new AlertDialog.Builder(getContext());

                                    builder.setTitle("Incomplete Profile");
                                    builder.setMessage("Please complete your profile to continue.");

                                    builder.setPositiveButton("OK", new DialogInterface.OnClickListener() {

                                        public void onClick(DialogInterface dialog, int which) {
                                            // Do nothing but close the dialog
                                            Intent intent = new Intent(getContext(), HomeActivity.class);
                                            startActivity(intent);
                                            dialog.dismiss();
                                        }
                                    });

                                    AlertDialog alert = builder.create();
                                    alert.show();

                                }
                            }

                        } catch (JSONException e) {
                            progressDialog.dismiss();
                            e.printStackTrace();
                            Toast.makeText(getContext(), "Failed" + e.toString(), Toast.LENGTH_SHORT).show();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        progressDialog.dismiss();
                        error.printStackTrace();
                        Toast.makeText(getContext(), "Failed" + error.toString(), Toast.LENGTH_SHORT).show();
                    }
                }) {
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
                params.put("user_id", user_id);

                return params;
            }
        };
        //RequestQueue requestQueue = Volley.newRequestQueue(this);
        //requestQueue.add(stringRequest);
        AppController.getmInstance().addToRequestQueue(stringRequest);
    }

    public void getOrderHistory() {

        final ProgressDialog progressDialog;
        progressDialog = createProgressDialog(getContext());
        progressDialog.show();
        String url = "http://192.168.137.1:8000/mobile/getorderhistory2";

        StringRequest stringRequest = new StringRequest(Request.Method.GET, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            Log.e("Products: ", response);
                            JSONObject jsonObject = new JSONObject(response);
                            String success = jsonObject.getString("success");

                            if (success.equals("1")) {
                                progressDialog.dismiss();

                                label.setVisibility(CardView.VISIBLE);
                                tv_empty.setVisibility(TextView.GONE);

                                JSONArray jsonArray = jsonObject.getJSONArray("data");

                                for (int i = 0; i < jsonArray.length(); i++) {

                                    JSONObject object = jsonArray.getJSONObject(i);
                                    double orderLatitude = object.getDouble("latitude");
                                    double orderLongitude = object.getDouble("longitude");
                                    float final_distance = distance(currentLatitude, currentLongitude, orderLatitude, orderLongitude);

                                    // delivery radius range up to 10KM only
                                    if (final_distance <= 1500) {

                                        Order order = new Order();
                                        order.setId(object.getInt("id"));
                                        order.setName(object.getString("name"));
                                        order.setDate(object.getString("date"));
                                        order.setStatus(object.getString("status"));
                                        order.setTotal(object.getDouble("new_total"));
                                        order.setFee(object.getDouble("delivery_fee"));

                                        orderList.add(order);
                                    }
                                }

                                adapter = new OrderAdapter(orderList, getContext());
                                listView.setAdapter(adapter);
                                adapter.notifyDataSetChanged();
                            } else {
                                progressDialog.dismiss();
                                label.setVisibility(CardView.GONE);
                                tv_empty.setVisibility(TextView.VISIBLE);
                            }
                        } catch (JSONException e) {
                            progressDialog.dismiss();
                            e.printStackTrace();
                            Toast.makeText(getContext(), "Failed" + e.toString(), Toast.LENGTH_SHORT).show();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        progressDialog.dismiss();
                        error.printStackTrace();
                        Toast.makeText(getContext(), "Failed" + error.toString(), Toast.LENGTH_SHORT).show();
                    }
                });
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

    public float distance(double currentlatitude, double currentlongitude, double originLat, double originLng) {

        float[] results = new float[1];
        Location.distanceBetween(currentlatitude, currentlongitude, originLat, originLng, results);
        float distanceInMeters = results[0];

        return distanceInMeters;
    }

    @Override
    public void onResume() {
        super.onResume();
        orderList.clear();
        getOrderHistory();
    }
}
