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
    private static final String KEY_GRADE = "grade";
    private static final String KEY_PARENT = "parent";
    private static final String KEY_MENTOR = "mentor";
    private static final String KEY_MENTEE = "mentee";
    private static final String KEY_MODERATOR = "moderator";
    private static final String KEY_EMPTY = "";
    private Context mContext;
    private SharedPreferences.Editor mEditor;
    private SharedPreferences mPreferences;

    public SessionManager(Context mContext) {
        this.mContext = mContext;
        mPreferences = mContext.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE);
        this.mEditor = mPreferences.edit();
    }

    public void loginUser(String email, String name, String id, int grade, boolean parent,
                          boolean mentor, boolean mentee, boolean moderator) {
        mEditor.putString(KEY_EMAIL, email);
        mEditor.putString(KEY_NAME, name);
        mEditor.putString(KEY_ID, id);
        mEditor.putInt(KEY_GRADE, grade);
        mEditor.putBoolean(KEY_PARENT, parent);
        mEditor.putBoolean(KEY_MENTOR, mentor);
        mEditor.putBoolean(KEY_MENTEE, mentee);
        mEditor.putBoolean(KEY_MODERATOR, moderator);

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
        user.setGrade(mPreferences.getInt(KEY_GRADE, 0));

        // set flags
        user.setParent(mPreferences.getBoolean(KEY_PARENT, false));
        user.setMentor(mPreferences.getBoolean(KEY_MENTOR, false));
        user.setMentee(mPreferences.getBoolean(KEY_MENTEE, false));
        user.setModerator(mPreferences.getBoolean(KEY_MODERATOR, false));

        return user;
    }

    public void updateName(String name) {
        mEditor.putString(KEY_NAME, name);
        mEditor.commit();
    }

    public void logoutUser() {
        mEditor.clear();
        mEditor.commit();
    }

}
