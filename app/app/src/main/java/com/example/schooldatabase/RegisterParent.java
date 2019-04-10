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
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class RegisterParent extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register_parent);
    }

    public void postRegisterParent(View view) {
        RequestQueue queue = Volley.newRequestQueue(this);
        String url = "http://10.209.25.133/code/project/api/register_parent.php";
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
                        CharSequence text = "Received response!";
                        int duration = Toast.LENGTH_LONG;

                        Toast toast = Toast.makeText(context, text, duration);
                        toast.show();
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
            EditText editText = (EditText) findViewById(R.id.name_rp);
            String val = editText.getText().toString();
            params.put("name", val);

            //EMAIL
            editText = (EditText) findViewById(R.id.email_rp);
            val = editText.getText().toString();
            params.put("email", val);

            //PASSWORD
            editText = (EditText) findViewById(R.id.pass_rp);
            val = editText.getText().toString();
            params.put("pass", val);

            //CONFIRM PASSWORD
            editText = (EditText) findViewById(R.id.pass_confirm_rp);
            val = editText.getText().toString();
            params.put("pass_confirm", val);

            //ROLE
            RadioGroup radioGroup = (RadioGroup) findViewById(R.id.roleSelect_rp);
            RadioButton radioButton = (RadioButton) findViewById(
                    radioGroup.getCheckedRadioButtonId());
            val = radioButton.getText().toString();
            params.put("role", val);

            //PHONE
            editText = (EditText) findViewById(R.id.phone_rp);
            val = editText.getText().toString();
            params.put("phone", val);

            //CITY
            editText = (EditText) findViewById(R.id.city_rp);
            val = editText.getText().toString();
            params.put("city", val);

            //STATE
            editText = (EditText) findViewById(R.id.state_rp);
            val = editText.getText().toString();
            params.put("state", val);
        } catch (JSONException e) {
            Log.d("Json", "Failed to get parameter list");
            params = null;
        }
        return params;
    }
}
