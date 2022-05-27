package com.example.grabnbiterider;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;

import java.util.HashMap;

public class SessionManager {

    SharedPreferences sharedPreferences;
    public SharedPreferences.Editor editor;
    public Context context;
    int PRIVATE_MODE = 0;

    private static final String PREF_NAME = "LOGIN";
    private static final String LOGIN = "IS_LOGIN";
    public static final String USER_ID = "USER_ID";
    public static final String USER_IMAGE = "USER_IMAGE";
    public static final String FIRST_NAME = "FIRST_NAME";
    public static final String LAST_NAME = "LAST_NAME";
    public static final String ADDRESS = "ADDRESS";
    public static final String EMAIL = "EMAIL";
    public static final String PHONE = "PHONE";

    public SessionManager(Context context) {
        this.context = context;
        sharedPreferences = context.getSharedPreferences(PREF_NAME, PRIVATE_MODE);
        editor = sharedPreferences.edit();
    }

//    public void createSession(String id, String image, String fname, String lname, String address, String email, String phone) {
//        editor.putBoolean(LOGIN, true);
//        editor.putString(USER_ID, id);
//        editor.putString(USER_IMAGE, image);
//        editor.putString(FIRST_NAME, fname);
//        editor.putString(LAST_NAME, lname);
//        editor.putString(ADDRESS,address);
//        editor.putString(EMAIL, email);
//        editor.putString(PHONE, phone);
//        editor.apply();
//    }

    public void createSession(String id, String image, String fname, String lname) {
        editor.putBoolean(LOGIN, true);
        editor.putString(USER_ID, id);
        editor.putString(USER_IMAGE, image);
        editor.putString(FIRST_NAME, fname);
        editor.putString(LAST_NAME, lname);
        editor.apply();
    }

    public boolean isLoggin() {
        return sharedPreferences.getBoolean(LOGIN, false);
    }

    public void checkLogin() {
        if(!this.isLoggin()) {
            Intent intent = new Intent(context, MainActivity.class);
            context.startActivity(intent);
            ((HomeActivity) context).finish();
        }
    }

    public HashMap<String, String> getUserDetails() {
        HashMap<String, String> user = new HashMap<>();
        user.put(USER_ID, sharedPreferences.getString(USER_ID,null));
        user.put(USER_IMAGE, sharedPreferences.getString(USER_IMAGE,null));
        user.put(FIRST_NAME, sharedPreferences.getString(FIRST_NAME,null));
        user.put(LAST_NAME, sharedPreferences.getString(LAST_NAME,null));
//        user.put(ADDRESS, sharedPreferences.getString(ADDRESS,null));
//        user.put(EMAIL, sharedPreferences.getString(EMAIL,null));
//        user.put(PHONE, sharedPreferences.getString(PHONE,null));

        return user;

    }

    public void logout() {
        editor.clear();
        editor.commit();
        Intent intent = new Intent(context, MainActivity.class);
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK);
        intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
        context.startActivity(intent);
        //((HomeActivity) context).finish();
    }
}
