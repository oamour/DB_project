package com.example.schooldatabase;

import android.content.Context;
import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class SelectChildProfileActivity extends AppCompatActivity {
    private SessionManager session;
    private RequestQueue queue;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_select_child_profile);

        session = new SessionManager(getApplicationContext());
        queue = Volley.newRequestQueue(this);

        User user = session.getUserDetails();

        if(!session.isLoggedIn() || !user.isParent()) {
            Context context = getApplicationContext();
            CharSequence message = "You do not have permission to access this page";
            int duration = Toast.LENGTH_SHORT;
            Toast toast = Toast.makeText(context, message, duration);
            toast.show();

            Intent intent = new Intent(this, MainActivity.class);
            startActivity(intent);
            finish();
        }

        //generate TextAreas, buttons corresponding to each child

    }

    public void getChildrenInfo(View view) {
        User user = session.getUserDetails();
        String url = MainActivity.HREF + "/code/project/api/children_parent.php";
        JSONArray requestContent = new JSONArray();
        try {
            requestContent.put(0, user.getID());
        } catch (JSONException e) {
            Log.d("JsonException", e.toString());
        }

        Log.d("SelectChildProfile", requestContent.toString());

        JsonArrayRequest getRequest = new JsonArrayRequest(Request.Method.GET, url, requestContent,
                new Response.Listener<JSONArray>()
                {
                    @Override
                    public void onResponse(JSONArray response) {
                        // response
                        Log.d("Response", response.toString());

                        try {
                            int i = 0;
                            while (!response.isNull(i)) {
                                JSONObject child = response.getJSONObject(i);
                                Log.d("SelectChildProfile", "Child " + i + " is " + child.toString());
                                i++;
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
        queue.add(getRequest);
    }

}
