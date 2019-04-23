package com.example.schooldatabase;

import android.content.Context;
import android.content.Intent;
import android.support.constraint.ConstraintLayout;
import android.support.constraint.ConstraintSet;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class MentorStatusActivity extends AppCompatActivity {
    private SessionManager session;
    private RequestQueue queue;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_mentor_status);

        session = new SessionManager(getApplicationContext());
        queue = Volley.newRequestQueue(this);

        User user = session.getUserDetails();

        if(!session.isLoggedIn() && !user.isMentor()) {
            Context context = getApplicationContext();
            CharSequence message = "You do not have permission to access this page";
            int duration = Toast.LENGTH_SHORT;
            Toast toast = Toast.makeText(context, message, duration);
            toast.show();

            Intent intent = new Intent(this, MainActivity.class);
            startActivity(intent);
            finish();
        }

        //get info for classes
        getMentorInfo();
    }

    public void getMentorInfo() {
        User user = session.getUserDetails();
        String url = MainActivity.HREF + "/code/project/api/mentor.php";
        JSONArray requestContent = new JSONArray();
        try {
            requestContent.put(0,  Integer.parseInt(user.getID()));
        } catch (JSONException e) {
            Log.d("JsonException", e.toString());
        }

        Log.d("MentorStatusActivity", requestContent.toString());

        JsonArrayRequest getRequest = new JsonArrayRequest(Request.Method.POST, url, requestContent,
                new Response.Listener<JSONArray>()
                {
                    @Override
                    public void onResponse(JSONArray response) {
                        // response
                        Log.d("Response", response.toString());

                        try {
                            /*int i = 0;
                            ConstraintLayout layout = findViewById(R.id.selectChildProfile_layout);
                            ConstraintSet set = new ConstraintSet();

                            if(response.isNull(0)) {
                                //no children
                                TextView textView = (TextView) findViewById(R.id.no_children_found);
                                textView.setVisibility(View.VISIBLE);
                            }
                            while (!response.isNull(i)) {
                                JSONObject child = response.getJSONObject(i);
                                Log.d("SelectChildProfile", "Child " + i + " is " + child.toString());
                                //generate textArea, button per child
                                TextView child_view = new TextView(getApplicationContext());
                                child_view.setText(child.getString("name"));
                                child_view.setId(100 + i);

                                //generate button
                                Button child_button = new Button(getApplicationContext());
                                child_button.setText(getResources().getString(R.string.change_profile));
                                child_button.setId(-100-i);
                                child_button.setOnClickListener(new View.OnClickListener() {
                                    @Override
                                    public void onClick(View v) {
                                    }
                                });

                                layout.addView(child_button, 0);
                                layout.addView(child_view, 0);
                                set.clone(layout);

                                if(i == 0) {
                                    //first element, constrain to top
                                    set.connect(child_view.getId(), ConstraintSet.TOP, layout.getId(), ConstraintSet.TOP, 60);
                                    set.connect(child_view.getId(), ConstraintSet.LEFT, layout.getId(), ConstraintSet.LEFT, 60);
                                } else {
                                    //not first element, constrain to previous
                                    TextView prev_view = findViewById(100 + i - 1);
                                    set.connect(child_view.getId(), ConstraintSet.TOP, prev_view.getId(), ConstraintSet.BOTTOM, 60);
                                    set.connect(child_view.getId(), ConstraintSet.LEFT, layout.getId(), ConstraintSet.LEFT, 60);
                                }


                                set.connect(child_button.getId(), ConstraintSet.LEFT, R.id.selectChildProfile_guideline, ConstraintSet.RIGHT);
                                set.connect(child_view.getId(), ConstraintSet.BASELINE, child_button.getId(), ConstraintSet.BASELINE);

                                if(i == 0) {
                                    set.connect(child_button.getId(), ConstraintSet.TOP, layout.getId(), ConstraintSet.TOP, 60);
                                } else {
                                    Button prev_button = findViewById(-100 - i + 1);
                                    set.connect(child_button.getId(), ConstraintSet.TOP, prev_button.getId(), ConstraintSet.BOTTOM, 60);
                                }

                                set.applyTo(layout);
                                i++;
                            }*/
                        } catch (Exception e) {
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
