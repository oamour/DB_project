package com.example.schooldatabase;

import android.content.Context;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.EditText;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

public class RegisterStudent extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register_student);
    }
    public void postRegisterStudent(View view) {
        RequestQueue queue = Volley.newRequestQueue(this);
        String url = "http://192.168.56.1/code/project/api/register_student.php";
        JSONObject requestContent = getParams();
        System.out.println("creating request");
        JsonObjectRequest postRequest = new JsonObjectRequest(Request.Method.POST, url, requestContent,
                new Response.Listener<JSONObject>()
                {
                    @Override
                    public void onResponse(JSONObject response) {
                        // response
                        Log.d("Response", response.toString());
                        Context context = getApplicationContext();
                        /* ERROR CODES:
                         * 0 - successfully added user
                         * 1 - email not valid
                         * 2 - email already registered
                         * 3 - password does not meet requirements
                         * 4 - password confirmation failure
                         * 5 - phone number invalid
                         * 6 - Database error
                         * 7 - parent email not mapped to parent
                         */
                        try {
                            int result = Integer.parseInt(response.get("result").toString());

                            String message;
                            switch (result) {
                                case 0:
                                    message = "Successfully registered!";
                                    break;
                                case 1:
                                    message = "Invalid email address!";
                                    break;
                                case 2:
                                    message = "Email already registered!";
                                    break;
                                case 3:
                                    message = "Password must be at least 8 characters long!";
                                    break;
                                case 4:
                                    message = "Passwords do not match!";
                                    break;
                                case 5:
                                    message = "Invalid phone number entered!";
                                    break;
                                case 6:
                                    message = "Failed to write user info to database!";
                                    break;
                                case 7:
                                    message = "Parent email does not map to a parent account!";
                                    break;
                                default:
                                    // should not get here
                                    message = "Unknown error!";
                                    break;
                            }
                            int duration = Toast.LENGTH_LONG;

                            Toast toast = Toast.makeText(context, message, duration);
                            toast.show();

                            //if case 0, redirect to main view
                            if (result == 0) {
                                RegisterStudent.super.finish();
                            }
                        } catch (JSONException e) {
                            Log.d("JsonException", e.toString());
                        }
                    }
                },
                new Response.ErrorListener()
                {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        // error
                        Log.d("Error.Response", error.toString());
                        Context context = getApplicationContext();
                        CharSequence text = "Error response!" + error.toString();
                        int duration = Toast.LENGTH_LONG;

                        Toast toast = Toast.makeText(context, text, duration);
                        toast.show();
                    }
                }
        );
        queue.add(postRequest);
    }

    private JSONObject getParams()
    {
        JSONObject params = new JSONObject();
        try {
            //NAME
            EditText editText = (EditText) findViewById(R.id.name_reg);
            String val = editText.getText().toString();
            params.put("name", val);

            //EMAIL
            editText = (EditText) findViewById(R.id.email_reg);
            val = editText.getText().toString();
            params.put("email", val);

            //PARENT EMAIL
            editText = (EditText) findViewById(R.id.parent_email_reg);
            val = editText.getText().toString();
            params.put("parent_email", val);

            //PASSWORD
            editText = (EditText) findViewById(R.id.pass_reg);
            val = editText.getText().toString();
            params.put("pass", val);

            //CONFIRM PASSWORD
            editText = (EditText) findViewById(R.id.pass_confirm_reg);
            val = editText.getText().toString();
            params.put("pass_confirm", val);

            //ROLE
            RadioGroup radioGroup = (RadioGroup) findViewById(R.id.role_select_reg);
            RadioButton radioButton = (RadioButton) findViewById(
                    radioGroup.getCheckedRadioButtonId());
            val = radioButton.getText().toString();
            params.put("role", val);

            //GRADE
            radioGroup = (RadioGroup) findViewById(R.id.grade_reg);
            radioButton = (RadioButton) findViewById(
                    radioGroup.getCheckedRadioButtonId());
            val = radioButton.getText().toString();
            params.put("grade", val);
            
            //PHONE
            editText = (EditText) findViewById(R.id.phone_reg);
            val = editText.getText().toString();
            params.put("phone", val);

            //CITY
            editText = (EditText) findViewById(R.id.city_reg);
            val = editText.getText().toString();
            params.put("city", val);

            //STATE
            editText = (EditText) findViewById(R.id.state_reg);
            val = editText.getText().toString();
            params.put("state", val);
        } catch (JSONException e) {
            Log.d("Json", "Failed to get parameter list");
            params = null;
        }
        return params;
    }
}
