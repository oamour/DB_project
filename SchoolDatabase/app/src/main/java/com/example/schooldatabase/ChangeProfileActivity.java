package com.example.schooldatabase;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.EditText;
import android.widget.ProgressBar;
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

public class ChangeProfileActivity extends AppCompatActivity {
    private SessionManager session;
    private RequestQueue queue;
    private String name = "";
    private String phone = "";
    private String city = "";
    private String state = "";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_change_profile);

        session = new SessionManager(getApplicationContext());
        queue = Volley.newRequestQueue(this);

        if(!session.isLoggedIn()) {
            Context context = getApplicationContext();
            CharSequence message = "You do not have permission to access this page";
            int duration = Toast.LENGTH_SHORT;
            Toast toast = Toast.makeText(context, message, duration);
            toast.show();

            Intent intent = new Intent(this, MainActivity.class);
            startActivity(intent);
            finish();
        }

        //get user info, update editText elements
        getProfileInfo();

    }

    public void getProfileInfo() {
        User user = session.getUserDetails();
        String url = MainActivity.HREF + "/code/project/api/user_info.php";
        JSONObject requestContent = new JSONObject();
        try {
            requestContent.put("userID", user.getID());
        } catch (JSONException e) {
            Log.d("ChangeProfile", e.toString());
        }
        Log.d("ChangeProfile", "requestContent = " + requestContent.toString());
        JsonObjectRequest getRequest = new JsonObjectRequest(Request.Method.GET, url, requestContent,
                new Response.Listener<JSONObject>()
                {
                    @Override
                    public void onResponse(JSONObject response) {
                        // response
                        Log.d("Response", response.toString());

                        try {
                            name = response.getString("name");
                            phone = response.getString("phone");
                            city = response.getString("city");
                            state = response.getString("state");

                            // Set text boxes to hold current values
                            EditText editText = (EditText) findViewById(R.id.name_change);
                            editText.setText(name, TextView.BufferType.EDITABLE);

                            editText = (EditText) findViewById(R.id.phone_change);
                            if(phone.length() == 10) {
                                CharSequence phone_fmt = phone.substring(0,3) + "-"
                                        + phone.substring(3,6) + "-"
                                        + phone.substring(6,10);
                                editText.setText(phone_fmt, TextView.BufferType.EDITABLE);
                            }

                            editText = (EditText) findViewById(R.id.city_change);
                            editText.setText(city, TextView.BufferType.EDITABLE);

                            editText = (EditText) findViewById(R.id.state_change);
                            editText.setText(state, TextView.BufferType.EDITABLE);

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
        queue.add(getRequest);
    }

    public void postChangeProfile(View view) {
        RequestQueue queue = Volley.newRequestQueue(this);
        String url = MainActivity.HREF + "/code/project/api/change_profile.php";
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
                         */
                        try {
                            int result = Integer.parseInt(response.get("result").toString());

                            String message;
                            switch (result) {
                                case 0:
                                    message = "Profile updated!";
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
                                // Update stored name if changed
                                session.updateName(name);
                                finish();
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
            User user = session.getUserDetails();

            //USERID
            params.put("userID", user.getID());

            //NAME
            EditText editText = (EditText) findViewById(R.id.name_change);
            String val = editText.getText().toString();
            if(val.equals("")) {
                val = name;
            } else {
                name = val;
            }
            params.put("name", val);

            //PASSWORD
            editText = (EditText) findViewById(R.id.pass_change);
            val = editText.getText().toString();
            params.put("pass", val);

            //CONFIRM PASSWORD
            editText = (EditText) findViewById(R.id.pass_confirm_change);
            val = editText.getText().toString();
            params.put("pass_confirm", val);

            //PHONE
            editText = (EditText) findViewById(R.id.phone_change);
            val = editText.getText().toString();
            if(val.equals("")) {
                val = phone;
            }
            params.put("phone", val);

            //CITY
            editText = (EditText) findViewById(R.id.city_change);
            val = editText.getText().toString();
            if(val.equals("")) {
                val = city;
            }
            params.put("city", val);

            //STATE
            editText = (EditText) findViewById(R.id.state_change);
            val = editText.getText().toString();
            if(val.equals("")) {
                val = state;
            }
            params.put("state", val);
        } catch (JSONException e) {
            Log.d("Json", "Failed to get parameter list");
            params = null;
        }
        return params;
    }
}
