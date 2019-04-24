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
                            buildSectionListing(response);
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

    public void buildSectionListing(JSONArray sectionList) throws JSONException {
        ConstraintLayout layout = findViewById(R.id.mentorStatus_layout);
        ConstraintSet set = new ConstraintSet();
        if(!sectionList.isNull(0)) {
            //hide default text
            TextView textView = (TextView) findViewById(R.id.mentor_no_sections);
            textView.setVisibility(View.GONE);
        }
        for (int i = 0; !sectionList.isNull(i); i++) {
            JSONObject section = sectionList.getJSONObject(i);
            Log.d("buildSectionListing", section.toString());
            Log.d("buildSectionListing", section.getString("name"));

            // STRUCTURE:
            // - TITLE (clickable, reveals ConstraintLayout
            // - CONSTRAINTLAYOUT (holds student info)
            // --- MENTEES (header for mentees)
            // --- (generated mentees)
            // --- MENTORS (header for mentors)
            // --- (generated mentors)
            TextView title = new TextView(getApplicationContext());
            title.setText(section.getString("name"));
            title.setTextSize(24);
            title.setClickable(true);
            title.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    toggleSectionInfo(v);
                }
            });
            title.setId(IdValues.TITLE + i);

            title.setCompoundDrawablesWithIntrinsicBounds(drawable.arrow_down_float, 0, 0, 0);
            //ImageView caret = new ImageView(getApplicationContext());
            //caret.setImageResource(drawable.arrow_down_float);
            //title.setId(IdValues.CARET + i);

            ConstraintLayout sectionInfo = new ConstraintLayout(getApplicationContext());
            sectionInfo.setVisibility(View.GONE);
            sectionInfo.setId(IdValues.CONSTRAINT_LAYOUT + i);
            sectionInfo.setMinWidth(800);
            sectionInfo.setMinHeight(250);
            sectionInfo.setBackgroundResource(drawable.editbox_background);

            layout.addView(title, 0);
            layout.addView(sectionInfo);
            set.clone(layout);

            //position title for section
            if(i == 0) {
                //first element, constrain to top
                set.connect(title.getId(), ConstraintSet.TOP, layout.getId(), ConstraintSet.TOP, 60);
                set.connect(title.getId(), ConstraintSet.LEFT, layout.getId(), ConstraintSet.LEFT, 60);
                set.connect(title.getId(), ConstraintSet.RIGHT, layout.getId(), ConstraintSet.RIGHT, 60);

            } else {
                //not first element, constrain to previous
                ConstraintLayout prev_view = findViewById(IdValues.CONSTRAINT_LAYOUT + i - 1);
                set.connect(title.getId(), ConstraintSet.TOP, prev_view.getId(), ConstraintSet.BOTTOM, 60);
                set.connect(title.getId(), ConstraintSet.LEFT, layout.getId(), ConstraintSet.LEFT, 60);
                set.connect(title.getId(), ConstraintSet.RIGHT, layout.getId(), ConstraintSet.RIGHT, 60);
            }
            //position sectionInfo constraintLayout
            set.connect(sectionInfo.getId(), ConstraintSet.TOP, title.getId(), ConstraintSet.BOTTOM, 60);
            set.connect(sectionInfo.getId(), ConstraintSet.LEFT, layout.getId(), ConstraintSet.LEFT, 60);
            set.connect(sectionInfo.getId(), ConstraintSet.RIGHT, layout.getId(), ConstraintSet.RIGHT, 60);

            set.applyTo(layout);


            //work on sectionInfo
            ConstraintSet sectionSet = new ConstraintSet();

            TextView menteeHeader = new TextView(getApplicationContext());
            menteeHeader.setText(R.string.mentee);
            menteeHeader.setTextSize(20);
            menteeHeader.setLayoutParams(new ConstraintLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.WRAP_CONTENT));
            menteeHeader.setBackgroundResource(R.drawable.drawable_bottom_border);
            menteeHeader.setId(IdValues.MENTEEHEADER + i);

            //create mentee elements
            JSONArray menteeList = section.getJSONArray("mentees");
            
            sectionInfo.addView(menteeHeader);
            sectionSet.clone(sectionInfo);

            sectionSet.connect(menteeHeader.getId(), ConstraintSet.TOP, sectionInfo.getId(), ConstraintSet.TOP);
            sectionSet.connect(menteeHeader.getId(), ConstraintSet.LEFT, sectionInfo.getId(), ConstraintSet.LEFT);
            sectionSet.connect(menteeHeader.getId(), ConstraintSet.RIGHT, sectionInfo.getId(), ConstraintSet.RIGHT);

            sectionSet.applyTo(sectionInfo);
        }
    }

    public void toggleSectionInfo(View view) {
        int sectionInfoId = view.getId() - IdValues.TITLE + IdValues.CONSTRAINT_LAYOUT;
        ConstraintLayout sectionInfo = findViewById(sectionInfoId);
        Log.d("toggleSectionInfo", "Triggered!");
        TextView textView = (TextView) view;
        if(sectionInfo.isShown()) {
            textView.setCompoundDrawablesWithIntrinsicBounds(drawable.arrow_down_float, 0, 0, 0);
            sectionInfo.setVisibility(View.GONE);
        } else {
            textView.setCompoundDrawablesWithIntrinsicBounds(drawable.arrow_up_float, 0, 0, 0);
            sectionInfo.setVisibility(View.VISIBLE);
        }
    }
}
