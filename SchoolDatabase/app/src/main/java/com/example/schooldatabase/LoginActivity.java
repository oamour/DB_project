package com.example.schooldatabase;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

public class LoginActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

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
