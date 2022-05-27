package com.example.grabnbiterider.ui.profile;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.graphics.Bitmap;
import android.graphics.drawable.ColorDrawable;
import android.net.Uri;
import android.os.Bundle;
import android.provider.MediaStore;
import android.provider.OpenableColumns;
import android.util.Base64;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.Fragment;
import androidx.lifecycle.Observer;
import androidx.lifecycle.ViewModelProviders;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.example.grabnbiterider.AppController;
import com.example.grabnbiterider.ChangePasswordActivity;
import com.example.grabnbiterider.DocumentActivity;
import com.example.grabnbiterider.MainActivity;
import com.example.grabnbiterider.R;
import com.example.grabnbiterider.RegisterActivity;
import com.example.grabnbiterider.SessionManager;
import com.squareup.picasso.Picasso;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.IOException;
import java.util.HashMap;
import java.util.Map;

import static android.app.Activity.RESULT_OK;

public class ProfileFragment extends Fragment  {

    private SessionManager sessionManager;
    private String user_id;
    private ImageView imgProfile;
    private ImageView iconEdit;
    private EditText etFname;
    private EditText etLname;
    private EditText etAddress;
    private EditText etEmail;
    private EditText etPhone;
    private TextView tv_name;
    private TextView tv_address;
    private Button btnSave;
    private Button btnChangePassword;
    private Button btnInfo;
    private Bitmap bitmap;
    private String displayName;
    private String image;
    private String fname;
    private String lname;
    private String address;
    private String email;
    private String phone;

    private static final int PICKFILE_RESULT_CODE = 1;

    public View onCreateView(@NonNull LayoutInflater inflater,
                             ViewGroup container, Bundle savedInstanceState) {

        View root = inflater.inflate(R.layout.fragment_profile, container, false);

        //for the session values
        sessionManager = new SessionManager(getContext());
        sessionManager.checkLogin();
        HashMap<String, String> user = sessionManager.getUserDetails();
        user_id = user.get(sessionManager.USER_ID);

        // setting up the variables
        imgProfile = root.findViewById(R.id.imgProfile);
        iconEdit = root.findViewById(R.id.iconEdit);
        etFname = root.findViewById(R.id.etFname);
        etLname = root.findViewById(R.id.etLname);
        etAddress = root.findViewById(R.id.etAddress);
        etEmail = root.findViewById(R.id.etEmail);
        etPhone = root.findViewById(R.id.etPhone);
        btnSave = root.findViewById(R.id.btnSave);
        btnChangePassword = root.findViewById(R.id.btnChangePassword);
        btnInfo = root.findViewById(R.id.btnInfo);
        tv_name = root.findViewById(R.id.tv_name);
        tv_address = root.findViewById(R.id.tv_address);

        getProfile();

        iconEdit.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                chooseFile();
            }
        });

        btnChangePassword.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
//                Toast.makeText(getContext(), "This feature is not yet available", Toast.LENGTH_SHORT).show();
                Intent intent = new Intent(getContext(), ChangePasswordActivity.class);
                startActivity(intent);
            }
        });

        btnSave.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                fname = etFname.getText().toString().trim();
                lname = etLname.getText().toString().trim();
                address = etAddress.getText().toString().trim();
                email = etEmail.getText().toString().trim();
                phone = etPhone.getText().toString().trim();

                if(!validateFname() | !validateLname() | !validateAddress() | !validateEmail() | !validatePhone()) {
                    return;
                }

                // save profile
                if (bitmap != null) {
                    image = getStringImage(bitmap);
                    saveProfile();
                } else {
                    image = "empty";
                    saveProfile();
                }
            }
        });

        btnInfo.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(getContext(), DocumentActivity.class);
                startActivity(intent);
            }
        });

        return root;
    }

    public void saveProfile() {
//        loading.setVisibility(View.VISIBLE);
//        btnSave.setVisibility(View.GONE);
        final ProgressDialog progressDialog;
        progressDialog = createProgressDialog(getContext());
        progressDialog.show();
        String url = "http://192.168.137.1:8000/mobile/saveprofile";
        StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            Log.e("Save Profile: ", response);
                            JSONObject jsonObject = new JSONObject(response);
                            String success = jsonObject.getString("success");
                            String message = jsonObject.getString("message");

                            if (success.equals("1")) {
                                getProfile();
//                                loading.setVisibility(View.GONE);
//                                btnSave.setVisibility(View.VISIBLE);

                                AlertDialog.Builder builder = new AlertDialog.Builder(getContext());

                                builder.setTitle("Update Profile");
                                builder.setMessage(message);

                                builder.setPositiveButton("OK", new DialogInterface.OnClickListener() {

                                    public void onClick(DialogInterface dialog, int which) {
                                        // Do nothing but close the dialog
                                        progressDialog.dismiss();
                                        dialog.dismiss();
                                    }
                                });

                                AlertDialog alert = builder.create();
                                alert.show();
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
                params.put("id", user_id);
                params.put("image", image);
                params.put("fname", fname);
                params.put("lname", lname);
                params.put("address", address);
                params.put("email", email);
                params.put("phone", phone);

                return params;
            }
        };
        //RequestQueue requestQueue = Volley.newRequestQueue(this);
        //requestQueue.add(stringRequest);
        AppController.getmInstance().addToRequestQueue(stringRequest);
    }

    public void getProfile() {
        final ProgressDialog progressDialog;
        progressDialog = createProgressDialog(getContext());
        progressDialog.show();
        String url = "http://192.168.137.1:8000/mobile/getprofile";
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
                                String address = jsonObject.getString("address");
                                String email = jsonObject.getString("email");
                                String phone = jsonObject.getString("phone");

                                StringBuilder sb = new StringBuilder("http://192.168.137.1:8000/images/users/");
                                sb.append(image);

                                String imageURL = sb.toString();

                                // place data to UI
                                Picasso.get().load(imageURL).into(imgProfile);
                                tv_name.setText(fname+" "+lname);
                                tv_address.setText(address);
                                etFname.setText(fname);
                                etLname.setText(lname);
                                etAddress.setText(address);
                                etEmail.setText(email);
                                etPhone.setText(phone);

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
                params.put("id", user_id);

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

    private void chooseFile() {
        Intent intent = new Intent();
        intent.setType("image/*");
        intent.setAction(Intent.ACTION_GET_CONTENT);
        startActivityForResult(Intent.createChooser(intent, "Select Picture"), 1);
    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        try {
            // When an Image is picked
            if (requestCode == PICKFILE_RESULT_CODE && resultCode == RESULT_OK
                    && null != data) {

                if (data.getData() != null) {
                    // Get the Uri of the selected file
                    Uri uri = data.getData();
                    String uriString = uri.toString();
                    File myFile = new File(uriString);
                    displayName = null;

                    if (uriString.startsWith("content://")) {
                        Cursor cursor = null;
                        try {
                            bitmap = MediaStore.Images.Media.getBitmap(getContext().getContentResolver(), uri);
                            imgProfile.setImageBitmap(bitmap);
                            cursor = getContext().getContentResolver().query(uri, null, null, null, null);
                            if (cursor != null && cursor.moveToFirst()) {
                                displayName = cursor.getString(cursor.getColumnIndex(OpenableColumns.DISPLAY_NAME));
                                //imagesEncodedList.add(displayName);
                            }
                        } catch (IOException e) {
                            e.printStackTrace();
                        } finally {
                            cursor.close();
                        }
                    } else if (uriString.startsWith("file://")) {
                        displayName = myFile.getName();
                    }
                }
            } else {
                Toast.makeText(getContext(), "You haven't picked Image",
                        Toast.LENGTH_LONG).show();
            }
        } catch (Exception e) {
            Toast.makeText(getContext(), "Something went wrong " + e, Toast.LENGTH_LONG).show();
        }
        super.onActivityResult(requestCode, resultCode, data);
    }

    public String getStringImage(Bitmap bitmap) {

        ByteArrayOutputStream byteArrayOutputStream = new ByteArrayOutputStream();
        bitmap.compress(Bitmap.CompressFormat.JPEG, 100, byteArrayOutputStream);

        byte[] imageByteArray = byteArrayOutputStream.toByteArray();
        String encodedImage = Base64.encodeToString(imageByteArray, Base64.NO_WRAP);

        return encodedImage;
    }

    public boolean validateFname() {
        if (fname.isEmpty()) {
            etFname.setError("This is required");
            return false;
        } else {
            etFname.setError(null);
            return true;
        }
    }

    public boolean validateLname() {
        if (lname.isEmpty()) {
            etLname.setError("This is required");
            return false;
        } else {
            etLname.setError(null);
            return true;
        }
    }

    public boolean validateAddress() {
        if (address.isEmpty()) {
            etAddress.setError("This is required");
            return false;
        } else {
            etAddress.setError(null);
            return true;
        }
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

    public boolean validatePhone() {
        if (phone.isEmpty()) {
            etPhone.setError("This is required");
            return false;
        } else {
            etPhone.setError(null);
            return true;
        }
    }

    @Override
    public void onResume() {
        super.onResume();
        ((AppCompatActivity)getActivity()).getSupportActionBar().hide();
    }
    @Override
    public void onStop() {
        super.onStop();
        ((AppCompatActivity)getActivity()).getSupportActionBar().show();
    }

}
