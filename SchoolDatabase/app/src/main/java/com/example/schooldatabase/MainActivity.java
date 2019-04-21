package com.example.schooldatabase;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;

public class MainActivity extends AppCompatActivity {
    public static final String EXTRA_MESSAGE = "com.example.schooldatabase.MESSAGE";
    public static final String LOGIN_TYPE = "com.example.schooldatabase.LOGIN_TYPE";
    public static final String HREF = "http://192.168.56.1";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
    }
    /*/** when user presses Send button...
    public void sendMessage(View view) {
        Intent intent = new Intent(this, DisplayMessageActivity.class);
        EditText editText = (EditText) findViewById(R.id.editText);
        String message = editText.getText().toString();
        intent.putExtra(EXTRA_MESSAGE, message);
        startActivity(intent);
    }*/
    public void gotoLoginParent(View view) {
        Intent intent = new Intent(this, LoginActivity.class);
        intent.putExtra(LOGIN_TYPE, "parent");
        startActivity(intent);
    }

    public void gotoLoginStudent(View view) {
        Intent intent = new Intent(this, LoginActivity.class);
        intent.putExtra(LOGIN_TYPE, "student");
        startActivity(intent);
    }

    public void gotoRegisterParent(View view) {
        Intent intent = new Intent(this, RegisterParent.class);
        startActivity(intent);
    }

    public void gotoRegisterStudent(View view) {
        Intent intent = new Intent(this, RegisterStudent.class);
        startActivity(intent);
    }
}
