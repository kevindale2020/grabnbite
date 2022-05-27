package com.example.grabnbiterider;

import android.Manifest;
import android.annotation.SuppressLint;
import android.app.FragmentManager;
import android.app.Notification;
import android.app.PendingIntent;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.location.Address;
import android.location.Geocoder;
import android.location.Location;
import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.util.Log;
import android.view.MenuItem;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.example.grabnbiterider.ui.history.HistoryFragment;
import com.example.grabnbiterider.ui.home.HomeFragment;
import com.example.grabnbiterider.ui.review.ReviewFragment;
import com.google.android.gms.common.api.GoogleApiClient;
import com.google.android.gms.location.LocationCallback;
import com.google.android.gms.location.LocationListener;
import com.google.android.gms.location.LocationRequest;
import com.google.android.gms.location.LocationResult;
import com.google.android.gms.location.LocationServices;
import com.google.android.gms.location.LocationSettingsRequest;
import com.google.android.gms.location.SettingsClient;
import com.google.android.material.bottomnavigation.BottomNavigationView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.lang.ref.WeakReference;
import java.lang.reflect.Type;
import java.util.HashMap;
import java.util.List;
import java.util.Locale;
import java.util.Map;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.core.app.ActivityCompat;
import androidx.core.app.NotificationCompat;
import androidx.core.app.NotificationManagerCompat;
import androidx.core.content.ContextCompat;
import androidx.fragment.app.FragmentTransaction;
import androidx.navigation.NavController;
import androidx.navigation.Navigation;
import androidx.navigation.ui.AppBarConfiguration;
import androidx.navigation.ui.NavigationUI;

import static com.example.grabnbiterider.AppController.CHANNEL_1_ID;
import static com.example.grabnbiterider.AppController.CHANNEL_2_ID;
import static com.google.android.gms.location.LocationServices.getFusedLocationProviderClient;

public class HomeActivity extends AppCompatActivity implements LocationListener {
    SessionManager sessionManager;
    private String user_id;
    private LocationRequest mLocationRequest;
    private long UPDATE_INTERVAL = 10 * 1000;  /* 10 secs */
    private long FASTEST_INTERVAL = 2 * 1000; /* 2 sec */
    private double currentLatitude;
    private double currentLongitude;
    private TextView tv_current_location;
    public static WeakReference<HomeActivity> weakActivity;
    private NotificationManagerCompat notificationManager;
    private NavController navController;
    Handler handler;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_home);
        BottomNavigationView navView = findViewById(R.id.nav_view);

        Toolbar toolbar = findViewById(R.id.toolbar);
//        TextView mTitle = toolbar.findViewById(R.id.toolbar_title);
        setSupportActionBar(toolbar);
//        mTitle.setText("GRAB N BITE");
        toolbar.showOverflowMenu();

        //for the session values
        sessionManager = new SessionManager(this);
        sessionManager.checkLogin();
        HashMap<String, String> user = sessionManager.getUserDetails();
        user_id = user.get(sessionManager.USER_ID);

        tv_current_location = findViewById(R.id.tv_current_location);

        notificationManager = NotificationManagerCompat.from(this);

        // Passing each menu ID as a set of Ids because each
        // menu should be considered as top level destinations.
        AppBarConfiguration appBarConfiguration = new AppBarConfiguration.Builder(
                R.id.navigation_home, R.id.navigation_profile, R.id.navigation_logout, R.id.navigation_history, R.id.navigation_review)
                .build();
        navController = Navigation.findNavController(this, R.id.nav_host_fragment);
        NavigationUI.setupActionBarWithNavController(this, navController, appBarConfiguration);
        NavigationUI.setupWithNavController(navView, navController);

        navView.setOnNavigationItemSelectedListener(new BottomNavigationView.OnNavigationItemSelectedListener() {
            @Override
            public boolean onNavigationItemSelected(@NonNull MenuItem menuItem) {

                switch (menuItem.getItemId()) {

                    case R.id.navigation_home:
                        navController.navigate(R.id.navigation_home);
                        break;

                    case R.id.navigation_profile:
                        navController.navigate(R.id.navigation_profile);
                        break;

                    case R.id.navigation_history:
                        navController.navigate(R.id.navigation_history);
                        break;

                    case R.id.navigation_review:
                        navController.navigate(R.id.navigation_review);
                        break;

                    case R.id.navigation_logout:
                        AlertDialog.Builder builder = new AlertDialog.Builder(HomeActivity.this);

                        builder.setTitle("Logout");
                        builder.setMessage("Are you sure you want to logout?");

                        builder.setPositiveButton("YES", new DialogInterface.OnClickListener() {

                            public void onClick(DialogInterface dialog, int which) {
                                // Do nothing but close the dialog
                                sessionManager.logout();
                                finish();
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
                        break;
                }
                return true;
            }
        });

        if (checkPermissions()) {
            startLocationUpdates();
        }

        weakActivity = new WeakReference<>(HomeActivity.this);

        handler = new Handler();

        content();

    }

    public void pushNotifications() {
        String url = "http://192.168.137.1:8000/mobile/getridernotifications";
        StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            Log.e("Notifications: ", response);
                            JSONObject jsonObject = new JSONObject(response);
                            String success = jsonObject.getString("success");
                            if (success.equals("1")) {
                                JSONArray jsonArray = jsonObject.getJSONArray("data");
                                for (int i = 0; i < jsonArray.length(); i++) {

                                    JSONObject object = jsonArray.getJSONObject(i);

                                    int id = object.getInt("id");
                                    String subject = object.getString("subject");
                                    String content = object.getString("content");
                                    String type = object.getString("type");

                                    double orderLatitude = object.getDouble("latitude");
                                    double orderLongitude = object.getDouble("longitude");

                                    if(orderLatitude!=0 && orderLongitude!=0) {

                                        float final_distance = distance(currentLatitude, currentLongitude, orderLatitude, orderLongitude);

                                        if(final_distance <= 1500 ) {
                                            sendNotification(subject, content, type);
                                        }
                                    } else {
                                        sendNotification(subject, content, type);
                                    }
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
//                        Toast.makeText(getApplicationContext(), "Failed" + error.toString(), Toast.LENGTH_SHORT).show();
                    }
                }) {
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
                params.put("user_id", user_id);
                return params;
            }
        };
        AppController.getmInstance().addToRequestQueue(stringRequest);
    }

    public void updateNotifications() {
        String url = "http://192.168.137.1:8000/mobile/updateridernotifications";
        StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            Log.e("Notifications: ", response);
                            JSONObject jsonObject = new JSONObject(response);
                            String success = jsonObject.getString("success");
                            if (success.equals("1")) {

                                Log.e("Notification Update", success);
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
//                        Toast.makeText(getApplicationContext(), "Failed" + error.toString(), Toast.LENGTH_SHORT).show();
                    }
                }) {
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
                params.put("user_id", user_id);
                return params;
            }
        };
        AppController.getmInstance().addToRequestQueue(stringRequest);
    }

    @Override
    protected void onNewIntent(Intent intent) {
        super.onNewIntent(intent);
        setIntent(intent);

        String menuFragment = getIntent().getStringExtra("menuFragment");

//        FragmentTransaction fragmentTransaction = getSupportFragmentManager().beginTransaction();

        if(menuFragment!=null) {

            if(menuFragment.equals("HistoryFragment")) {

//                HistoryFragment historyFragment = new HistoryFragment();
//                fragmentTransaction.replace(R.id.homeFragment, historyFragment).commit();
                navController.navigate(R.id.navigation_history);

            } else {

//                ReviewFragment reviewFragment = new ReviewFragment();
//                fragmentTransaction.replace(R.id.homeFragment, reviewFragment).commit();
                navController.navigate(R.id.navigation_review);
            }
        }
    }

    public void sendNotification(String subject, String content, String type) {

//        int requestID = (int) System.currentTimeMillis();

        if (type.equals("Order")) {

            Intent resultIntent = new Intent(getApplicationContext(), HomeActivity.class);
            resultIntent.putExtra("menuFragment", "HistoryFragment");
            resultIntent.setFlags(Intent.FLAG_ACTIVITY_SINGLE_TOP | Intent.FLAG_ACTIVITY_CLEAR_TOP);
            PendingIntent pendingIntent = PendingIntent.getActivity(getApplicationContext(), 1, resultIntent, PendingIntent.FLAG_UPDATE_CURRENT);
            Notification notification = new NotificationCompat.Builder(getApplicationContext(), CHANNEL_1_ID)
                    .setSmallIcon(R.drawable.ic_stat_name)
                    .setContentTitle(subject)
                    .setContentText(content)
                    .setContentIntent(pendingIntent)
                    .setPriority(NotificationCompat.PRIORITY_HIGH)
                    .setCategory(NotificationCompat.CATEGORY_MESSAGE)
                    .setAutoCancel(true)
                    .build();
            //int m = (int) ((new Date().getTime() / 1000L) % Integer.MAX_VALUE);
            notificationManager.notify(1, notification);
        } else {

            Intent resultIntent = new Intent(getApplicationContext(), HomeActivity.class);
            resultIntent.putExtra("menuFragment", "ReviewFragment");
            resultIntent.addFlags(Intent.FLAG_ACTIVITY_SINGLE_TOP | Intent.FLAG_ACTIVITY_CLEAR_TOP);
            PendingIntent pendingIntent = PendingIntent.getActivity(getApplicationContext(), 1, resultIntent, PendingIntent.FLAG_UPDATE_CURRENT);
            Notification notification = new NotificationCompat.Builder(getApplicationContext(), CHANNEL_2_ID)
                    .setSmallIcon(R.drawable.ic_stat_name)
                    .setContentTitle(subject)
                    .setContentText(content)
                    .setContentIntent(pendingIntent)
                    .setPriority(NotificationCompat.PRIORITY_HIGH)
                    .setCategory(NotificationCompat.CATEGORY_MESSAGE)
                    .setAutoCancel(true)
                    .build();
            //int m = (int) ((new Date().getTime() / 1000L) % Integer.MAX_VALUE);
            notificationManager.notify(2, notification);
        }
    }

    public void content() {
        pushNotifications();
        updateNotifications();
        //checkStatus(user_id);
        refresh(1000 * 1);
    }

    private void refresh(int i) {
        final Runnable runnable = new Runnable() {
            @Override
            public void run() {
                //pushNotification();
                //updateNotification(user_id, String.valueOf(notification_type_id));
                content();
            }

        };
        handler.postDelayed(runnable, i);
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        handler.removeCallbacksAndMessages(null);
        handler = null;
    }

    public static HomeActivity getmInstanceActivity() {
        return weakActivity.get();
    }

    private boolean checkPermissions() {
        if (ContextCompat.checkSelfPermission(this,
                Manifest.permission.ACCESS_FINE_LOCATION) == PackageManager.PERMISSION_GRANTED) {
            return true;
        } else {
            requestPermissions();
            return false;
        }
    }

    private void requestPermissions() {
        ActivityCompat.requestPermissions(this,
                new String[]{Manifest.permission.ACCESS_FINE_LOCATION},
                1);
    }

    protected void startLocationUpdates() {

        // Create the location request to start receiving updates
        mLocationRequest = new LocationRequest();
        mLocationRequest.setPriority(LocationRequest.PRIORITY_HIGH_ACCURACY);
        mLocationRequest.setInterval(UPDATE_INTERVAL);
        mLocationRequest.setFastestInterval(FASTEST_INTERVAL);

        // Create LocationSettingsRequest object using location request
        LocationSettingsRequest.Builder builder = new LocationSettingsRequest.Builder();
        builder.addLocationRequest(mLocationRequest);
        LocationSettingsRequest locationSettingsRequest = builder.build();

        // Check whether location settings are satisfied
        // https://developers.google.com/android/reference/com/google/android/gms/location/SettingsClient
        SettingsClient settingsClient = LocationServices.getSettingsClient(this);
        settingsClient.checkLocationSettings(locationSettingsRequest);

        // new Google API SDK v11 uses getFusedLocationProviderClient(this)
        getFusedLocationProviderClient(this).requestLocationUpdates(mLocationRequest, new LocationCallback() {
                    @Override
                    public void onLocationResult(LocationResult locationResult) {
                        // do work here
                        onLocationChanged(locationResult.getLastLocation());
                    }
                },
                Looper.myLooper());
    }

    @Override
    public void onLocationChanged(@NonNull Location location) {

        tv_current_location.setText(getLocation(getApplicationContext(), location.getLatitude(), location.getLongitude()));
        currentLatitude = location.getLatitude();
        currentLongitude = location.getLongitude();

//        HomeFragment fragment = new HomeFragment();
//        FragmentTransaction fragmentTransaction = getSupportFragmentManager().beginTransaction();
//
//        Bundle bundle = new Bundle();
//        bundle.putDouble("current_lat", currentLatitude);
//        bundle.putDouble("current_lng", currentLongitude);
//
//        fragment.setArguments(bundle);
//
//        fragmentTransaction.replace(R.id.homeFragment, fragment).commit();
    }

    public String getLocation(Context context, double LATITUDE, double LONGITUDE) {

        String myCurrentLocation = null;
        //Set Address
        try {
            Geocoder geocoder = new Geocoder(context, Locale.getDefault());
            List<Address> addresses = geocoder.getFromLocation(LATITUDE, LONGITUDE, 1);
            if (addresses != null && addresses.size() > 0) {


                myCurrentLocation = addresses.get(0).getAddressLine(0); // If any additional address line present than only, check with max available address lines by getMaxAddressLineIndex()
                String city = addresses.get(0).getLocality();
                String state = addresses.get(0).getAdminArea();
                String country = addresses.get(0).getCountryName();
                String phone = addresses.get(0).getPhone();
                String postalCode = addresses.get(0).getPostalCode();
                String knownName = addresses.get(0).getFeatureName(); // Only if available else return NULL


                // Toast.makeText(context,phone,Toast.LENGTH_SHORT).show();


            }
        } catch (IOException e) {
            e.printStackTrace();
        }
        return myCurrentLocation;
    }

    public double getCurrentLatitude() {
        return currentLatitude;
    }

    public double getCurrentLongitude() {
        return currentLongitude;
    }

    public float distance(double currentlatitude, double currentlongitude, double originLat, double originLng) {

        float[] results = new float[1];
        Location.distanceBetween(currentlatitude, currentlongitude, originLat, originLng, results);
        float distanceInMeters = results[0];

        return distanceInMeters;
    }
}


