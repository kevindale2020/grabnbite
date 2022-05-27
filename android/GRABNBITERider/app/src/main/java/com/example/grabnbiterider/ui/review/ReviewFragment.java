package com.example.grabnbiterider.ui.review;

import android.app.ProgressDialog;
import android.content.Context;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.WindowManager;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.RatingBar;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.example.grabnbiterider.AppController;
import com.example.grabnbiterider.R;
import com.example.grabnbiterider.Rating;
import com.example.grabnbiterider.RatingAdapter;
import com.example.grabnbiterider.SessionManager;
import com.squareup.picasso.Picasso;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import androidx.annotation.NonNull;
import androidx.fragment.app.Fragment;

public class ReviewFragment extends Fragment {

    private SessionManager sessionManager;
    private String user_id;
    private String image;
    private String fname;
    private String lname;
    private ImageView imgProfile;
    private List<Rating> ratingList;
    private ListView listView;
    private RatingAdapter adapter;
    private TextView tv_name;
    private RatingBar rating;
    private TextView tv_rating;
    private TextView tv_empty;
    private RelativeLayout rellay1;

    public View onCreateView(@NonNull LayoutInflater inflater,
                             ViewGroup container, Bundle savedInstanceState) {

        View root = inflater.inflate(R.layout.fragment_review, container, false);

        //for the session values
        sessionManager = new SessionManager(getContext());
        sessionManager.checkLogin();
        HashMap<String, String> user = sessionManager.getUserDetails();
        user_id = user.get(sessionManager.USER_ID);

        imgProfile = root.findViewById(R.id.imgProfile);
        rellay1 = root.findViewById(R.id.rellay1);
        tv_name = root.findViewById(R.id.tv_name);
        tv_rating = root.findViewById(R.id.tv_rating);
        tv_empty = root.findViewById(R.id.tv_empty);
        rating = root.findViewById(R.id.rating);

        listView = root.findViewById(R.id.list);
        ratingList = new ArrayList<>();
        adapter = new RatingAdapter(ratingList, getContext());

        getUser();

        getReviews();

        return root;
    }

    public void getUser() {
        final ProgressDialog progressDialog;
        progressDialog = createProgressDialog(getContext());
        progressDialog.show();
        String url = "http://192.168.137.1:8000/mobile/getuser";
        StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            Log.e("Profile: ", response);
                            JSONObject jsonObject = new JSONObject(response);
                            String success = jsonObject.getString("success");

                            if (success.equals("1")) {
                                progressDialog.dismiss();
                                String image = jsonObject.getString("image");
                                String fname = jsonObject.getString("fname");
                                String lname = jsonObject.getString("lname");


                                if(image.equals("user_none.png") && fname.isEmpty() && lname.isEmpty()) {
                                    rellay1.setVisibility(RelativeLayout.GONE);

                                } else {
                                    rellay1.setVisibility(RelativeLayout.VISIBLE);
                                    StringBuilder sb = new StringBuilder("http://192.168.137.1:8000/images/users/");
                                    sb.append(image);

                                    String imageURL = sb.toString();
                                    Picasso.get().load(imageURL).into(imgProfile);
                                    tv_name.setText(fname+" "+lname);
                                }

                            } else {
                                progressDialog.dismiss();
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

    public void getReviews() {
        final ProgressDialog progressDialog;
        progressDialog = createProgressDialog(getContext());
        progressDialog.show();
        String url = "http://192.168.137.1:8000/mobile/getreviews";
        StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            Log.e("Profile: ", response);
                            JSONObject jsonObject = new JSONObject(response);
                            String success = jsonObject.getString("success");
                            float overall_rating = (float) jsonObject.getDouble("overall_rating");
                            int num_reviews = jsonObject.getInt("num_reviews");

                            if (success.equals("1")) {
                                progressDialog.dismiss();

                                rating.setVisibility(RatingBar.VISIBLE);
                                rating.setRating(overall_rating);
                                tv_rating.setVisibility(TextView.VISIBLE);
                                tv_rating.setText(num_reviews+"+ reviews");
                                tv_empty.setVisibility(View.GONE);

                                JSONArray jsonArray = jsonObject.getJSONArray("data");

                                for (int i = 0; i < jsonArray.length(); i++) {

                                    JSONObject object = jsonArray.getJSONObject(i);

                                    Rating rating = new Rating();
                                    rating.setId(object.getInt("id"));
                                    rating.setImage(object.getString("image"));
                                    rating.setFname(object.getString("fname"));
                                    rating.setLname(object.getString("lname"));
                                    rating.setFeedback(object.getString("feedback"));
                                    rating.setDate(object.getString("date"));
                                    rating.setRate((float) object.getDouble("rate"));

                                    ratingList.add(rating);
                                }

                                adapter = new RatingAdapter(ratingList, getContext());
                                listView.setAdapter(adapter);
                                adapter.notifyDataSetChanged();
                            } else {
                                progressDialog.dismiss();
                                rating.setVisibility(RatingBar.VISIBLE);
                                tv_rating.setVisibility(TextView.VISIBLE);
                                tv_rating.setText("0 people rated");
                                tv_empty.setVisibility(View.VISIBLE);
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
}
