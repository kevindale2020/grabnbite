package com.example.grabnbiterider;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.graphics.Bitmap;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.net.Uri;
import android.os.Bundle;
import android.provider.MediaStore;
import android.provider.OpenableColumns;
import android.util.Base64;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.view.WindowManager;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.RelativeLayout;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.artjimlop.altex.AltexImageDownloader;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class DocumentActivity extends AppCompatActivity implements View.OnClickListener {
    SessionManager sessionManager;
    private String user_id;
    private TextView file;
    private TextView file2;
    private TextView file3;
    private TextView file4;
    private TextView tv_plate_no;
    private TextView tv_driver_license;
    private ImageView iconAttach;
    private ImageView iconAttach2;
    private ImageView iconAttach3;
    private ImageView iconAttach4;
    private Button btnAttach;
    private Button btnAttach2;
    private Button btnAttach3;
    private Button btnAttach4;
    private Button btnSave;
    private ProgressBar loading;
    private Boolean flag = false;
    private Boolean flag2 = false;
    private Boolean flag3 = false;
    private Boolean flag4 = false;
    private String filename = "";
    private String filename2 = "";
    private String filename3 = "";
    private String filename4 = "";
    private String color = "";
    private String number = "";
    private String type = null;
    private Bitmap file_value = null;
    private Bitmap file_value2 = null;
    private Bitmap file_value3 = null;
    private Bitmap file_value4 = null;
    private Spinner spinner;
    private EditText etColor;
    private EditText etNumber;
    private TextView errorText = null;
    private RelativeLayout layout_file4;

    private static final int PICKFILE_RESULT_CODE = 1;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_document);

        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        //for the session values
        sessionManager = new SessionManager(this);
        sessionManager.checkLogin();
        HashMap<String, String> user = sessionManager.getUserDetails();
        user_id = user.get(sessionManager.USER_ID);

        // assign values
        file = findViewById(R.id.file);
        file2 = findViewById(R.id.file2);
        file3 = findViewById(R.id.file3);
        file4 = findViewById(R.id.file4);

        iconAttach = findViewById(R.id.iconAttach);
        iconAttach2 = findViewById(R.id.iconAttach2);
        iconAttach3 = findViewById(R.id.iconAttach3);
        iconAttach4 = findViewById(R.id.iconAttach4);
        loading = findViewById(R.id.loading);

        spinner = findViewById(R.id.spinner1);

        etColor = findViewById(R.id.etColor);
        etNumber = findViewById(R.id.etNumber);

        tv_plate_no = findViewById(R.id.tv_plate_no);
        tv_driver_license = findViewById(R.id.tv_driver_license);
        layout_file4 = findViewById(R.id.layout_file4);

        initClick();

        file.setClickable(false);

        addItemOnSpinner();

        spinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {

                String selectedItem = parent.getItemAtPosition(position).toString();

                if (selectedItem.equals("Bike")) {

                    tv_plate_no.setVisibility(TextView.GONE);
                    etNumber.setVisibility(EditText.GONE);

                    tv_driver_license.setVisibility(TextView.GONE);
                    btnAttach4.setVisibility(TextView.GONE);
                    layout_file4.setVisibility(RelativeLayout.GONE);

                } else {

                    tv_plate_no.setVisibility(TextView.VISIBLE);
                    etNumber.setVisibility(EditText.VISIBLE);

                    tv_driver_license.setVisibility(TextView.VISIBLE);
                    btnAttach4.setVisibility(TextView.VISIBLE);
                    layout_file4.setVisibility(RelativeLayout.VISIBLE);
                }
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {

            }
        });

        getDriver();
    }

    private void initClick() {
        btnAttach = findViewById(R.id.btnAttach);
        btnAttach.setOnClickListener(this);

        btnAttach2 = findViewById(R.id.btnAttach2);
        btnAttach2.setOnClickListener(this);

        btnAttach3 = findViewById(R.id.btnAttach3);
        btnAttach3.setOnClickListener(this);

        btnAttach4 = findViewById(R.id.btnAttach4);
        btnAttach4.setOnClickListener(this);

        btnSave = findViewById(R.id.btnSave);
        btnSave.setOnClickListener(this);
    }

    @Override
    public void onClick(View v) {

        switch (v.getId()) {

            case R.id.btnAttach:
                flag = true;
                chooseFile();
                break;

            case R.id.btnAttach2:
                flag2 = true;
                chooseFile();
                break;

            case R.id.btnAttach3:
                flag3 = true;
                chooseFile();
                break;

            case R.id.btnAttach4:
                flag4 = true;
                chooseFile();
                break;

            case R.id.btnSave:

                type = spinner.getSelectedItem().toString();
                color = etColor.getText().toString().trim();
                number = etNumber.getText().toString().trim();

                if (!validateType() | !validateColor()) {
                    return;
                }

                save();

                break;
        }
    }

    private void chooseFile() {
        Intent intent = new Intent();
        intent.setType("image/*");
        intent.setAction(Intent.ACTION_GET_CONTENT);
        startActivityForResult(Intent.createChooser(intent, "Select Picture"), PICKFILE_RESULT_CODE);
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        // TODO Auto-generated method stub
        try {
            // When an Image is picked
            if (requestCode == PICKFILE_RESULT_CODE && resultCode == RESULT_OK
                    && null != data) {
                //one image
                if (data.getData() != null) {
                    // Get the Uri of the selected file
                    Uri uri = data.getData();
                    String uriString = uri.toString();
                    File myFile = new File(uriString);
                    String displayName = "";
                    Bitmap bitmap = null;

                    if (uriString.startsWith("content://")) {
                        Cursor cursor = null;
                        try {
                            bitmap = MediaStore.Images.Media.getBitmap(getContentResolver(), uri);
                            //bitmapList.add(bitmap);
                            cursor = this.getContentResolver().query(uri, null, null, null, null);
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

                    if (flag == true) {
                        filename = displayName;
                        file_value = bitmap;
                        file.setVisibility(TextView.VISIBLE);
                        file.setText(filename);
                        iconAttach.setVisibility(ImageView.VISIBLE);
                        flag = false;
                    }

                    if (flag2 == true) {
                        filename2 = displayName;
                        file_value2 = bitmap;
                        file2.setVisibility(TextView.VISIBLE);
                        file2.setText(filename2);
                        iconAttach2.setVisibility(ImageView.VISIBLE);
                        flag2 = false;
                    }

                    if (flag3 == true) {
                        filename3 = displayName;
                        file_value3 = bitmap;
                        file3.setVisibility(TextView.VISIBLE);
                        file3.setText(filename3);
                        iconAttach3.setVisibility(ImageView.VISIBLE);
                        flag3 = false;
                    }

                    if (flag4 == true) {
                        filename4 = displayName;
                        file_value4 = bitmap;
                        file4.setVisibility(TextView.VISIBLE);
                        file4.setText(filename4);
                        iconAttach4.setVisibility(ImageView.VISIBLE);
                        flag4 = false;
                    }
                }
            } else {
                Toast.makeText(this, "You haven't picked Image",
                        Toast.LENGTH_LONG).show();
            }
        } catch (Exception e) {
            Toast.makeText(this, "Something went wrong " + e, Toast.LENGTH_LONG).show();
        }
        super.onActivityResult(requestCode, resultCode, data);
    }

    public String getStringImage(Bitmap bitmap) {

        ByteArrayOutputStream byteArrayOutputStream = new ByteArrayOutputStream();
        bitmap.compress(Bitmap.CompressFormat.JPEG, 100, byteArrayOutputStream);

        byte[] imageByteArray = byteArrayOutputStream.toByteArray();
        String encodedImage = Base64.encodeToString(imageByteArray, Base64.DEFAULT);

        return encodedImage;
    }

    public void save() {
        loading.setVisibility(View.VISIBLE);
        btnSave.setVisibility(View.GONE);
        String url = "http://192.168.137.1:8000/mobile/savedriver";
        // url = "http://192.168.43.44:8080/IRO/Android/register.php";
        StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            Log.e("Save: ", response);
                            JSONObject jsonObject = new JSONObject(response);
                            String success = jsonObject.getString("success");
                            String message = jsonObject.getString("message");
                            if (success.equals("1")) {

                                loading.setVisibility(View.GONE);
                                btnSave.setVisibility(View.VISIBLE);

                                AlertDialog.Builder builder = new AlertDialog.Builder(DocumentActivity.this);

                                builder.setTitle("Driver Info");
                                builder.setMessage(message);

                                builder.setPositiveButton("OK", new DialogInterface.OnClickListener() {

                                    public void onClick(DialogInterface dialog, int which) {
                                        // Do nothing but close the dialog
//                                        Intent intent = new Intent(getApplicationContext(), MainActivity.class);
//                                        startActivity(intent);
//                                        finish();
                                        getDriver();
                                        dialog.dismiss();
                                    }
                                });

                                AlertDialog alert = builder.create();
                                alert.show();

                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                            Toast.makeText(getApplicationContext(), "Failed" + e.toString(), Toast.LENGTH_SHORT).show();
                            loading.setVisibility(View.GONE);
                            btnSave.setVisibility(View.VISIBLE);
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        error.printStackTrace();
                        Toast.makeText(getApplicationContext(), "Failed" + error.toString(), Toast.LENGTH_SHORT).show();
                        loading.setVisibility(View.GONE);
                        btnSave.setVisibility(View.VISIBLE);
                    }
                }) {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();
                params.put("id", user_id);
                params.put("type", type);
                params.put("color", color);
                params.put("number", number);
                params.put("filename", (filename != "") ? filename : "empty");
                params.put("filename2", (filename2 != "") ? filename2 : "empty");
                params.put("filename3", (filename3 != "") ? filename3 : "empty");
                params.put("filename4", (filename4 != "") ? filename4 : "empty");
                params.put("file", (file_value != null) ? getStringImage(file_value) : "empty");
                params.put("file2", (file_value2 != null) ? getStringImage(file_value2) : "empty");
                params.put("file3", (file_value3 != null) ? getStringImage(file_value3) : "empty");
                params.put("file4", (file_value4 != null) ? getStringImage(file_value4) : "empty");

                return params;
            }
        };
        //RequestQueue requestQueue = Volley.newRequestQueue(this);
        //requestQueue.add(stringRequest);
        AppController.getmInstance().addToRequestQueue(stringRequest);
    }

    public void getDriver() {
        final ProgressDialog progressDialog;
        progressDialog = createProgressDialog(DocumentActivity.this);
        progressDialog.show();
        String url = "http://192.168.137.1:8000/mobile/getdriver";
        StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            Log.e("Products: ", response);
                            JSONObject jsonObject = new JSONObject(response);
                            String success = jsonObject.getString("success");

                            if (success.equals("1")) {
                                progressDialog.dismiss();

                                String current_file = jsonObject.getString("file");
                                String current_file2 = jsonObject.getString("file2");
                                String current_file3 = jsonObject.getString("file3");
                                String current_file4 = jsonObject.getString("file4");

                                String type = jsonObject.getString("type");
                                String color = jsonObject.getString("color");
                                String number = jsonObject.getString("number");

                                if (type.equals("Bike")) {

                                    tv_plate_no.setVisibility(TextView.GONE);
                                    etNumber.setVisibility(EditText.GONE);

                                    tv_driver_license.setVisibility(TextView.GONE);
                                    btnAttach4.setVisibility(TextView.GONE);
                                    layout_file4.setVisibility(RelativeLayout.GONE);

                                    spinner.setSelection(2);
                                } else {

                                    tv_plate_no.setVisibility(TextView.VISIBLE);
                                    etNumber.setVisibility(EditText.VISIBLE);

                                    tv_driver_license.setVisibility(TextView.VISIBLE);
                                    btnAttach4.setVisibility(TextView.VISIBLE);
                                    layout_file4.setVisibility(RelativeLayout.VISIBLE);

                                    spinner.setSelection(1);

                                    if (!number.isEmpty()) {
                                        etNumber.setText(number);
                                    }
                                }

                                if (!color.isEmpty()) {
                                    etColor.setText(color);
                                }

                                if (!current_file.isEmpty()) {

                                    file.setText(current_file);
                                    file.setVisibility(TextView.VISIBLE);
                                    file.setClickable(true);
                                    iconAttach.setVisibility(ImageView.VISIBLE);
                                }

                                if (!current_file2.isEmpty()) {

                                    file2.setText(current_file2);
                                    file2.setVisibility(TextView.VISIBLE);
                                    file2.setClickable(true);
                                    iconAttach2.setVisibility(ImageView.VISIBLE);
                                }

                                if (!current_file3.isEmpty()) {

                                    file3.setText(current_file3);
                                    file3.setVisibility(TextView.VISIBLE);
                                    file3.setClickable(true);
                                    iconAttach3.setVisibility(ImageView.VISIBLE);
                                }

                                if (!current_file4.isEmpty()) {

                                    file4.setText(current_file4);
                                    file4.setVisibility(TextView.VISIBLE);
                                    file4.setClickable(true);
                                    iconAttach4.setVisibility(ImageView.VISIBLE);
                                }

                            } else {
                                progressDialog.dismiss();
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
                params.put("id", user_id);

                return params;
            }
        };
        //RequestQueue requestQueue = Volley.newRequestQueue(this);
        //requestQueue.add(stringRequest);
        AppController.getmInstance().addToRequestQueue(stringRequest);
    }

    public boolean validateType() {

        if (type.equals("Choose Type")) {
            errorText = (TextView) spinner.getSelectedView();
            errorText.setTextColor(Color.RED);//just to highlight that this is an error
            errorText.setText("This is required");//changes the selected item text to this
            return false;
        } else {
            return true;
        }
    }

    public boolean validateColor() {

        if (color.isEmpty()) {
            etColor.setError("This is required");
            return false;
        } else {
            etColor.setError(null);
            return true;
        }
    }

    public boolean validateNumber() {

        if (number.isEmpty()) {
            etNumber.setError("This is required");
            return false;
        } else {
            etNumber.setError(null);
            return true;
        }
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

    public void previewFile(View view) {
        StringBuilder sb = new StringBuilder("http://192.168.137.1:8000/documents/");
        sb.append(file.getText().toString());
        String imageUrl = sb.toString();
        AltexImageDownloader.writeToDisk(getApplicationContext(), imageUrl, "Images");
    }

    public void previewFile2(View view) {
        StringBuilder sb = new StringBuilder("http://192.168.137.1:8000/documents/");
        sb.append(file2.getText().toString());
        String imageUrl = sb.toString();
        AltexImageDownloader.writeToDisk(getApplicationContext(), imageUrl, "Images");
    }

    public void previewFile3(View view) {
        StringBuilder sb = new StringBuilder("http://192.168.137.1:8000/documents/");
        sb.append(file3.getText().toString());
        String imageUrl = sb.toString();
        AltexImageDownloader.writeToDisk(getApplicationContext(), imageUrl, "Images");
    }

    public void previewFile4(View view) {
        StringBuilder sb = new StringBuilder("http://192.168.137.1:8000/documents/");
        sb.append(file4.getText().toString());
        String imageUrl = sb.toString();
        AltexImageDownloader.writeToDisk(getApplicationContext(), imageUrl, "Images");
    }

    public void addItemOnSpinner() {

        List<String> list = new ArrayList<>();
        list.add("Choose Type");
        list.add("Motorcycle");
        list.add("Bike");

        ArrayAdapter<String> dataAdapter = new ArrayAdapter<String>(this,
                android.R.layout.simple_spinner_item, list) {
            @Override
            public boolean isEnabled(int position) {
                if (position == 0) {
                    return false;
                } else {
                    return true;
                }
            }

            @Override
            public View getDropDownView(int position, @Nullable View convertView, @NonNull ViewGroup parent) {
                View view = super.getDropDownView(position, convertView, parent);
                TextView tv = (TextView) view;
                if (position == 0) {
                    // Set the hint text color gray
                    tv.setTextColor(Color.GRAY);
                } else {
                    tv.setTextColor(Color.BLACK);
                }
                return view;
            }
        };
        dataAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinner.setAdapter(dataAdapter);

    }
}
