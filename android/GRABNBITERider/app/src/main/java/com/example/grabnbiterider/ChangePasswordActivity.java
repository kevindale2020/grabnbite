package com.example.grabnbiterider;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class ChangePasswordActivity extends AppCompatActivity {
    private EditText etCurrent;
    private EditText etConfirm;
    private EditText etNew;
    private Button btnSave;
    SessionManager sessionManager;
    private String user_id;
    private String currentPassword;
    private String confirmPassword;
    private String newPassword;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_change_password);

        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        //for the session values
        sessionManager = new SessionManager(this);
        sessionManager.checkLogin();
        HashMap<String, String> user = sessionManager.getUserDetails();
        user_id = user.get(sessionManager.USER_ID);

        etCurrent = findViewById(R.id.etCurrent);
        etConfirm = findViewById(R.id.etConfirm);
        etNew = findViewById(R.id.etNew);
        btnSave = findViewById(R.id.btnSave);

        btnSave.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                currentPassword = etCurrent.getText().toString().trim();
                confirmPassword = etConfirm.getText().toString().trim();
                newPassword = etNew.getText().toString().trim();

                if(!validateCurrentPassword() | !validateNewPassword() | !validateConfirmPassword()) {
                    return;
                }

                if(!validatePasswordMatch()) {
                    return;
                }

                changePassword();

            }
        });

        etCurrent.requestFocus();
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

    public void changePassword() {
        final ProgressDialog progressDialog;
        progressDialog = createProgressDialog(ChangePasswordActivity.this);
        progressDialog.show();
        String url = "http://192.168.137.1:8000/mobile/changepassword";
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

                                progressDialog.dismiss();
                                AlertDialog.Builder builder = new AlertDialog.Builder(ChangePasswordActivity.this);

                                builder.setTitle("Change Password");
                                builder.setMessage(message);

                                builder.setPositiveButton("OK", new DialogInterface.OnClickListener() {

                                    public void onClick(DialogInterface dialog, int which) {
                                        // Do nothing but close the dialog
//                                        Intent intent = new Intent(getApplicationContext(), MainActivity.class);
//                                        startActivity(intent);
//                                        finish();
                                        etCurrent.setText("");
                                        etNew.setText("");
                                        etConfirm.setText("");
                                        etCurrent.requestFocus();
                                        dialog.dismiss();
                                    }
                                });

                                AlertDialog alert = builder.create();
                                alert.show();

                            } else {
                                progressDialog.dismiss();
                                AlertDialog.Builder builder = new AlertDialog.Builder(ChangePasswordActivity.this);

                                builder.setTitle("Change Password");
                                builder.setMessage(message);

                                builder.setPositiveButton("OK", new DialogInterface.OnClickListener() {

                                    public void onClick(DialogInterface dialog, int which) {
                                        // Do nothing but close the dialog
//                                        Intent intent = new Intent(getApplicationContext(), MainActivity.class);
//                                        startActivity(intent);
//                                        finish();
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
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();
                params.put("user_id", user_id);
                params.put("current_password", currentPassword);
                params.put("new_password", newPassword);

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

    public boolean validateCurrentPassword() {

        if(currentPassword.isEmpty()) {
            etCurrent.setError("This is required");
            return false;
        } else {
            etCurrent.setError(null);
            return true;
        }
    }

    public boolean validateConfirmPassword() {

        if(confirmPassword.isEmpty()) {
            etConfirm.setError("This is required");
            return false;
        } else {
            etConfirm.setError(null);
            return true;
        }
    }

    public boolean validateNewPassword() {

        if(newPassword.isEmpty()) {
            etNew.setError("This is required");
            return false;
        } else {
            etNew.setError(null);
            return true;
        }
    }

    public boolean validatePasswordMatch() {

        if(!newPassword.equals(confirmPassword)) {
            etNew.setError("Password not match");
            etConfirm.setError("Password not match");
            return false;
        } else {
            etNew.setError(null);
            etConfirm.setError(null);
            return true;
        }
    }
}
