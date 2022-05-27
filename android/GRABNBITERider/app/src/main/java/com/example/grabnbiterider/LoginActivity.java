package com.example.grabnbiterider;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ProgressBar;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class LoginActivity extends AppCompatActivity {
    private EditText etEmail;
    private EditText etPassword;
    private Button btnLogin;
    private String email;
    private String password;
    private ProgressBar loading;
    SessionManager sessionManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        // setting up the login sessions
        sessionManager = new SessionManager(this);

        // setting up the variables
        etEmail = findViewById(R.id.etEmail);
        etPassword = findViewById(R.id.etPassword);
        btnLogin = findViewById(R.id.btnLogin);
        loading = findViewById(R.id.loading);

        etEmail.requestFocus();

        btnLogin.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                email = etEmail.getText().toString().trim();
                password = etPassword.getText().toString().trim();

                if (!validateEmail() | !validatePassword()) {
                    return;
                }

                login();
            }
        });
    }

    public void login() {
        loading.setVisibility(View.VISIBLE);
        btnLogin.setVisibility(View.GONE);
        String url = "http://192.168.137.1:8000/mobile/login";
        //String url = "http://192.168.43.44:8080/IRO/Android/login.php";
        StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            Log.e("Login: ", response);
                            JSONObject jsonObject = new JSONObject(response);
                            String success = jsonObject.getString("success");
                            String message = jsonObject.getString("message");

                            if (success.equals("2")) {

                                loading.setVisibility(View.GONE);
                                btnLogin.setVisibility(View.VISIBLE);

                                int id = jsonObject.getInt("id");
                                String image = jsonObject.getString("image");
                                String fname = jsonObject.getString("fname");
                                String lname = jsonObject.getString("lname");
                                int is_verified = jsonObject.getInt("verified");

//                                JSONArray jsonArray = jsonObject.getJSONArray("data");
//
//                                JSONObject object = jsonArray.getJSONObject(0);
//                                int id = object.getInt("id");
//                                String image = object.getString("image");
//                                String fname = object.getString("fname");
//                                String lname = object.getString("lname");
//                                String address = object.getString("address");
//                                String email = object.getString("email");
//                                String phone = object.getString("contact");

                                if(is_verified==1) {

                                    sessionManager.createSession(String.valueOf(id), image, fname, lname);

                                    Intent intent = new Intent(getApplicationContext(), HomeActivity.class);
                                    startActivity(intent);
                                    finish();
                                } else {

                                    Intent intent = new Intent(getApplicationContext(), NotVerifiedActivity.class);
                                    startActivity(intent);
                                    finish();
                                }

                            } else {
                                AlertDialog.Builder builder = new AlertDialog.Builder(LoginActivity.this);

                                builder.setTitle("Failed to login");
                                builder.setMessage("Invalid credentials");

                                builder.setPositiveButton("OK", new DialogInterface.OnClickListener() {

                                    public void onClick(DialogInterface dialog, int which) {
                                        // Do nothing but close the dialog
                                        dialog.dismiss();
                                    }
                                });

                                AlertDialog alert = builder.create();
                                alert.show();
                                loading.setVisibility(View.GONE);
                                btnLogin.setVisibility(View.VISIBLE);
                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                            Toast.makeText(getApplicationContext(), "Failed" + e.toString(), Toast.LENGTH_SHORT).show();
                            loading.setVisibility(View.GONE);
                            btnLogin.setVisibility(View.VISIBLE);
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        error.printStackTrace();
                        Toast.makeText(getApplicationContext(), "Failed" + error.toString(), Toast.LENGTH_SHORT).show();
                        loading.setVisibility(View.GONE);
                        btnLogin.setVisibility(View.VISIBLE);
                    }
                }) {
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
                params.put("email", email);
                params.put("password", password);

                return params;
            }
        };
        AppController.getmInstance().addToRequestQueue(stringRequest);
    }

    public boolean validateEmail() {

        if (email.isEmpty()) {
            etEmail.setError("This is required");
            return false;
        } else {
            etEmail.setError(null);
            return true;
        }
    }

    public boolean validatePassword() {

        if (password.isEmpty()) {
            etPassword.setError("This is required");
            return false;
        } else {
            etPassword.setError(null);
            return true;
        }
    }

    public void register(View view) {
        Intent intent = new Intent(this, RegisterActivity.class);
        startActivity(intent);
    }

}
