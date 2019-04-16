package com.example.schooldatabase;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.widget.Button;
import android.widget.TextView;

public class LoginActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        //get intent, extract string
        Intent intent = getIntent();
        String login_type = (intent.getStringExtra(MainActivity.LOGIN_TYPE));
        Log.d("login_type", login_type);
        if (login_type == "student") {
            Button registerButton = findViewById(R.id.login_register_button);
            registerButton.setText("@string/index_register_student");
            TextView registerText = findViewById(R.id.login_register_text);
            registerText.setText("@string/login_register_student");
        } else {
            // leave as default (parent items)
        }
    }
}
