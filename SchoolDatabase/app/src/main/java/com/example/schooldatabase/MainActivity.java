package com.example.schooldatabase;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;

public class MainActivity extends AppCompatActivity {
    public static final String EXTRA_MESSAGE = "com.example.schooldatabase.MESSAGE";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
    }
    /** when user presses Send button...*/
    public void sendMessage(View view) {
        Intent intent = new Intent(this, DisplayMessageActivity.class);
        EditText editText = (EditText) findViewById(R.id.editText);
        String message = editText.getText().toString();
        intent.putExtra(EXTRA_MESSAGE, message);
        startActivity(intent);
    }

    public void gotoRegisterParent(View view) {
        Intent intent = new Intent(this, RegisterParent.class);
        startActivity(intent);
    }

//    public void gotoRegisterStudent(View view) {
//        Intent intent = new Intent(this, RegisterStudent.class);
//        startActivity(intent);
//    }
}
