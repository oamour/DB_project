package com.example.schooldatabase;

import android.content.Context;
import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

public class LoginActivity extends AppCompatActivity {
    private SessionManager session;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);
        session = new SessionManager(getApplicationContext());

        // if logged in, go straight to dashboard
        if (session.isLoggedIn()) {
            gotoDashboard();
        }

        //get intent, extract string
        Intent intent = getIntent();
        String loginType = (intent.getStringExtra(MainActivity.LOGIN_TYPE));
        //Log.d("login_type", login_type);
        if (loginType.equals("student")) {
            Button registerButton = findViewById(R.id.login_register_button);
            registerButton.setText(getResources().getString(R.string.index_login_student));
            TextView registerText = findViewById(R.id.login_register_text);
            registerText.setText(getResources().getString(R.string.login_register_student));
        } else {
            // leave as default (parent items)
        }
    }
    public void gotoDashboard() {
        Intent intent = getIntent();
        // TODO: write dashboard activity
    }

    public void gotoRegisterPage(View view) {
        Intent intent = getIntent();
        String loginType =  (intent.getStringExtra(MainActivity.LOGIN_TYPE));

        if (loginType.equals("student")) {
            Intent newIntent = new Intent(this, RegisterStudent.class);
            startActivity(newIntent);
        } else {
            Intent newIntent = new Intent(this, RegisterParent.class);
            startActivity(newIntent);
        }
    }
}
