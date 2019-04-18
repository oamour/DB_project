package com.example.schooldatabase;

import android.content.Context;
import android.content.SharedPreferences;

import java.util.Date;

// THIS AND USER.JS BASED ON
// http://www.androiddeft.com/2018/01/21/login-registration-android-php-mysql/

public class SessionManager {
    private static final String PREF_NAME = "Session";
    private static final String KEY_EMAIL = "email";
    private static final String KEY_ID = "id";
    private static final String KEY_NAME = "name";
    private static final String KEY_EXPIRES = "expires";
    private static final String KEY_EMPTY = "";
    private Context mContext;
    private SharedPreferences.Editor mEditor;
    private SharedPreferences mPreferences;

    public SessionManager(Context mContext) {
        this.mContext = mContext;
        mPreferences = mContext.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE);
        this.mEditor = mPreferences.edit();
    }

    public void loginUser(String email, String name, String id) {
        mEditor.putString(KEY_EMAIL, email);
        mEditor.putString(KEY_NAME, name);
        mEditor.putString(KEY_ID, id);
        Date date = new Date();

        // Session is valid for 1 day
        long ms = date.getTime() + (24*60*60*1000);
        mEditor.putLong(KEY_EXPIRES, ms);

        mEditor.commit();
    }

    public boolean isLoggedIn() {
        Date currentDate = new Date();

        long ms = mPreferences.getLong(KEY_EXPIRES, 0);

        if (ms == 0) {
            return false;
        }
        Date expirationDate = new Date(ms);

        return currentDate.before(expirationDate);
    }

    public User getUserDetails() {
        if(!isLoggedIn()) {
            return null;
        }
        User user = new User();
        user.setEmail(mPreferences.getString(KEY_EMAIL, KEY_EMPTY));
        user.setID(mPreferences.getString(KEY_ID, KEY_EMPTY));
        user.setName(mPreferences.getString(KEY_NAME, KEY_EMPTY));
        user.setExpirationDate(new Date(mPreferences.getLong(KEY_EXPIRES, 0)));

        return user;
    }

    public void logoutUser() {
        mEditor.clear();
        mEditor.commit();
    }

}
