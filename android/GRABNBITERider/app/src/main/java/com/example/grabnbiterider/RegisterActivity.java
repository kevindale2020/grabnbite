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

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class RegisterActivity extends AppCompatActivity {

    private EditText etEmail;
    private EditText etPassword1;
    private EditText etPassword2;
    private Button btnRegister;
    private String email;
    private String pass1;
    private String pass2;
    private ProgressBar loading;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register);

        // setting up the variables
        etEmail = findViewById(R.id.etEmail);
        etPassword1 = findViewById(R.id.etPassword1);
        etPassword2 = findViewById(R.id.etPassword2);
        btnRegister = findViewById(R.id.btnRegister);
        loading = findViewById(R.id.loading);

        etEmail.requestFocus();

        btnRegister.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                email = etEmail.getText().toString().trim();
                pass1 = etPassword1.getText().toString().trim();
                pass2 = etPassword2.getText().toString().trim();

                if (!validateEmail() | !validatePass1() | !validatePass2()) {
                    return;
                } else {
                    if (!validatePassMatch()) {
                        return;
                    }
                    register();
                }
            }
        });
    }

    public void register() {
        loading.setVisibility(View.VISIBLE);
        btnRegister.setVisibility(View.GONE);
        String url = "http://192.168.137.1:8000/mobile/register2";
        // url = "http://192.168.43.44:8080/IRO/Android/register.php";
        StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            Log.e("Register: ", response);
                            JSONObject jsonObject = new JSONObject(response);
                            String success = jsonObject.getString("success");
                            String message = jsonObject.getString("message");
                            if (success.equals("1")) {

                                loading.setVisibility(View.GONE);
                                btnRegister.setVisibility(View.VISIBLE);

                                AlertDialog.Builder builder = new AlertDialog.Builder(RegisterActivity.this);

                                builder.setTitle("Registration");
                                builder.setMessage(message);

                                builder.setPositiveButton("OK", new DialogInterface.OnClickListener() {

                                    public void onClick(DialogInterface dialog, int which) {
                                        // Do nothing but close the dialog
                                        Intent intent = new Intent(getApplicationContext(), MainActivity.class);
                                        startActivity(intent);
                                        finish();
                                        //dialog.dismiss();
                                    }
                                });

                                AlertDialog alert = builder.create();
                                alert.show();

                            } else {
                                etEmail.setError(message);
                                loading.setVisibility(View.GONE);
                                btnRegister.setVisibility(View.VISIBLE);
                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                            Toast.makeText(getApplicationContext(), "Failed" + e.toString(), Toast.LENGTH_SHORT).show();
                            loading.setVisibility(View.GONE);
                            btnRegister.setVisibility(View.VISIBLE);
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        error.printStackTrace();
                        Toast.makeText(getApplicationContext(), "Failed" + error.toString(), Toast.LENGTH_SHORT).show();
                        loading.setVisibility(View.GONE);
                        btnRegister.setVisibility(View.VISIBLE);
                    }
                }) {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();
                params.put("email", email);
                params.put("password", pass1);

                return params;
            }
        };
        //RequestQueue requestQueue = Volley.newRequestQueue(this);
        //requestQueue.add(stringRequest);
        AppController.getmInstance().addToRequestQueue(stringRequest);
    }

    public void register2() {
        loading.setVisibility(View.VISIBLE);
        btnRegister.setVisibility(View.GONE);
        String url = "http://192.168.137.1:8000/mobile/register2";
        // url = "http://192.168.43.44:8080/IRO/Android/register.php";
        StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            Log.e("Register: ", response);
                            JSONObject jsonObject = new JSONObject(response);
                            String success = jsonObject.getString("success");
                            String message = jsonObject.getString("message");
                            if (success.equals("1")) {

                                loading.setVisibility(View.GONE);
                                btnRegister.setVisibility(View.VISIBLE);

                                AlertDialog.Builder builder = new AlertDialog.Builder(RegisterActivity.this);

                                builder.setTitle("Registration");
                                builder.setMessage(message);

                                builder.setPositiveButton("OK", new DialogInterface.OnClickListener() {

                                    public void onClick(DialogInterface dialog, int which) {
                                        // Do nothing but close the dialog
                                        Intent intent = new Intent(getApplicationContext(), MainActivity.class);
                                        startActivity(intent);
                                        finish();
                                        //dialog.dismiss();
                                    }
                                });

                                AlertDialog alert = builder.create();
                                alert.show();

                            } else {
                                etEmail.setError(message);
                                loading.setVisibility(View.GONE);
                                btnRegister.setVisibility(View.VISIBLE);
                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                            Toast.makeText(getApplicationContext(), "Failed" + e.toString(), Toast.LENGTH_SHORT).show();
                            loading.setVisibility(View.GONE);
                            btnRegister.setVisibility(View.VISIBLE);
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        error.printStackTrace();
                        Toast.makeText(getApplicationContext(), "Failed" + error.toString(), Toast.LENGTH_SHORT).show();
                        loading.setVisibility(View.GONE);
                        btnRegister.setVisibility(View.VISIBLE);
                    }
                }) {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();
                params.put("email", email);
                params.put("password", pass1);

                return params;
            }
        };
        //RequestQueue requestQueue = Volley.newRequestQueue(this);
        //requestQueue.add(stringRequest);
        AppController.getmInstance().addToRequestQueue(stringRequest);
    }

    public void login(View view) {
        Intent intent = new Intent(this, LoginActivity.class);
        startActivity(intent);
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

    public boolean validatePass1() {
        if (pass1.isEmpty()) {
            etPassword1.setError("This is required");
            return false;
        } else {
            etPassword1.setError(null);
            return true;
        }
    }

    public boolean validatePass2() {
        if (pass2.isEmpty()) {
            etPassword2.setError("This is required");
            return false;
        } else {
            etPassword2.setError(null);
            return true;
        }
    }

    public boolean validatePassMatch() {

        if (!pass1.equals(pass2)) {
            etPassword1.setError("Password not match");
            etPassword2.setError("Password not match");
            return false;
        } else {
            etPassword1.setError(null);
            etPassword2.setError(null);
            return true;
        }
    }


}
