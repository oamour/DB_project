package com.example.schooldatabase;

import android.content.Context;
import android.content.Intent;
import android.support.constraint.ConstraintLayout;
import android.support.constraint.ConstraintSet;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import android.widget.Toast;
import android.R.drawable;

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

    // Constant values for IDs of objects generated by buildSectionListing
    public class IdValues {
        public static final int TITLE = 100;
        public static final int CARET = 200;
        public static final int CONSTRAINT_LAYOUT = 300;
        public static final int MENTEE_HEADER = 400;
        public static final int MENTOR_HEADER = 500;
        public static final int MENTEE_NAME = 1000;
        public static final int MENTEE_GRADE = 2000;
        public static final int MENTOR_NAME = 3000;
        public static final int MENTOR_GRADE = 4000;
    };

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
            sectionInfo.setMinWidth(1000);
            sectionInfo.setMinHeight(250);
            sectionInfo.setBackgroundResource(drawable.editbox_background);

            layout.addView(title, 0);
            layout.addView(sectionInfo);
            set.clone(layout);

            //position title for section
            if(i == 0) {
                //first element, constrain to top
                set.connect(title.getId(), ConstraintSet.TOP, layout.getId(), ConstraintSet.TOP, 8);
                set.connect(title.getId(), ConstraintSet.LEFT, layout.getId(), ConstraintSet.LEFT, 8);
                set.connect(title.getId(), ConstraintSet.RIGHT, layout.getId(), ConstraintSet.RIGHT, 8);

            } else {
                //not first element, constrain to previous
                ConstraintLayout prev_view = findViewById(IdValues.CONSTRAINT_LAYOUT + i - 1);
                set.connect(title.getId(), ConstraintSet.TOP, prev_view.getId(), ConstraintSet.BOTTOM, 60);
                set.connect(title.getId(), ConstraintSet.LEFT, layout.getId(), ConstraintSet.LEFT, 60);
                set.connect(title.getId(), ConstraintSet.RIGHT, layout.getId(), ConstraintSet.RIGHT, 60);
            }
            //position sectionInfo constraintLayout
            set.connect(sectionInfo.getId(), ConstraintSet.TOP, title.getId(), ConstraintSet.BOTTOM, 0);
            set.connect(sectionInfo.getId(), ConstraintSet.LEFT, layout.getId(), ConstraintSet.LEFT, 8);
            set.connect(sectionInfo.getId(), ConstraintSet.RIGHT, layout.getId(), ConstraintSet.RIGHT, 8);

            set.applyTo(layout);


            // work on sectionInfo
            ConstraintSet sectionSet = new ConstraintSet();

            // Create mentee header
            TextView menteeHeader = new TextView(getApplicationContext());
            menteeHeader.setText(R.string.mentee);
            menteeHeader.setTextSize(20);
            menteeHeader.setLayoutParams(
                    new ConstraintLayout.LayoutParams(
                            ViewGroup.LayoutParams.MATCH_PARENT,
                            ViewGroup.LayoutParams.WRAP_CONTENT));
            menteeHeader.setBackgroundResource(R.drawable.drawable_bottom_border);
            menteeHeader.setId(IdValues.MENTEE_HEADER + i);

            sectionInfo.addView(menteeHeader);
            sectionSet.clone(sectionInfo);

            sectionSet.connect(menteeHeader.getId(), ConstraintSet.TOP, sectionInfo.getId(), ConstraintSet.TOP);
            sectionSet.connect(menteeHeader.getId(), ConstraintSet.LEFT, sectionInfo.getId(), ConstraintSet.LEFT);
            sectionSet.connect(menteeHeader.getId(), ConstraintSet.RIGHT, sectionInfo.getId(), ConstraintSet.RIGHT);

            sectionSet.applyTo(sectionInfo);

            // create mentee elements
            JSONArray menteeList = section.getJSONArray("mentees");
            int mentee_index = 0;
            while(!menteeList.isNull(mentee_index)) {
                JSONObject mentee = menteeList.getJSONObject(mentee_index);
                
                TextView mentee_name = new TextView(getApplicationContext());
                mentee_name.setText(mentee.getString("name"));
                mentee_name.setId(IdValues.MENTEE_NAME + 10*i + mentee_index);
                
                TextView mentee_grade = new TextView(getApplicationContext());
                mentee_grade.setText(mentee.getString("grade"));
                mentee_grade.setId(IdValues.MENTEE_GRADE + 10*i + mentee_index);

                sectionInfo.addView(mentee_name);
                sectionInfo.addView(mentee_grade);
                sectionSet.clone(sectionInfo);

                if(mentee_index == 0) {
                    //attach to header
                    sectionSet.connect(mentee_name.getId(), ConstraintSet.TOP, menteeHeader.getId(), ConstraintSet.BOTTOM, 16);
                    sectionSet.createHorizontalChain(sectionInfo.getId(), ConstraintSet.LEFT,
                            sectionInfo.getId(), ConstraintSet.RIGHT,
                            new int[] {mentee_name.getId(), mentee_grade.getId()}, null, ConstraintSet.CHAIN_SPREAD);
                    sectionSet.connect(mentee_grade.getId(), ConstraintSet.BASELINE, mentee_name.getId(), ConstraintSet.BASELINE);
                } else {
                    //attach to previous mentee element
                    sectionSet.connect(mentee_name.getId(), ConstraintSet.TOP, mentee_name.getId() - 1, ConstraintSet.BOTTOM);
                    sectionSet.createHorizontalChain(sectionInfo.getId(), ConstraintSet.LEFT,
                            sectionInfo.getId(), ConstraintSet.RIGHT,
                            new int[] {mentee_name.getId(), mentee_grade.getId()}, null, ConstraintSet.CHAIN_SPREAD);
                    sectionSet.connect(mentee_grade.getId(), ConstraintSet.BASELINE, mentee_name.getId(), ConstraintSet.BASELINE);

                }

                mentee_index++;

                sectionSet.applyTo(sectionInfo);
            }

            // create mentor header
            TextView mentorHeader = new TextView(getApplicationContext());
            mentorHeader.setText(getResources().getText(R.string.mentor));
            mentorHeader.setTextSize(20);
            mentorHeader.setLayoutParams(
                    new ConstraintLayout.LayoutParams(
                            ViewGroup.LayoutParams.MATCH_PARENT,
                            ViewGroup.LayoutParams.WRAP_CONTENT));
            mentorHeader.setBackgroundResource(R.drawable.drawable_bottom_border);
            mentorHeader.setId(IdValues.MENTOR_HEADER + i);

            sectionInfo.addView(mentorHeader);
            sectionSet.clone(sectionInfo);

            //bind to last mentee name
            sectionSet.connect(mentorHeader.getId(), ConstraintSet.TOP, IdValues.MENTEE_NAME + i*10 + mentee_index-1, ConstraintSet.BOTTOM, 24);
            sectionSet.connect(mentorHeader.getId(), ConstraintSet.LEFT, sectionInfo.getId(), ConstraintSet.LEFT);
            sectionSet.connect(mentorHeader.getId(), ConstraintSet.RIGHT, sectionInfo.getId(), ConstraintSet.RIGHT);

            sectionSet.applyTo(sectionInfo);

            //create mentor elements
            JSONArray mentorList = section.getJSONArray("mentors");
            int mentor_index = 0;
            while(!mentorList.isNull(mentor_index)) {
                JSONObject mentor = mentorList.getJSONObject(mentor_index);

                TextView mentor_name = new TextView(getApplicationContext());
                mentor_name.setText(mentor.getString("name"));
                mentor_name.setId(IdValues.MENTOR_NAME + 10*i + mentor_index);

                TextView mentor_grade = new TextView(getApplicationContext());
                mentor_grade.setText(mentor.getString("grade"));
                mentor_grade.setId(IdValues.MENTOR_GRADE + 10*i + mentor_index);

                sectionInfo.addView(mentor_name);
                sectionInfo.addView(mentor_grade);
                sectionSet.clone(sectionInfo);

                if(mentor_index == 0) {
                    //attach to header
                    sectionSet.connect(mentor_name.getId(), ConstraintSet.TOP, mentorHeader.getId(), ConstraintSet.BOTTOM, 16);
                    sectionSet.createHorizontalChain(sectionInfo.getId(), ConstraintSet.LEFT,
                            sectionInfo.getId(), ConstraintSet.RIGHT,
                            new int[] {mentor_name.getId(), mentor_grade.getId()}, null, ConstraintSet.CHAIN_SPREAD);
                    sectionSet.connect(mentor_grade.getId(), ConstraintSet.BASELINE, mentor_name.getId(), ConstraintSet.BASELINE);
                } else {
                    //attach to previous mentor element
                    sectionSet.connect(mentor_name.getId(), ConstraintSet.TOP, mentor_name.getId() - 1, ConstraintSet.BOTTOM);
                    sectionSet.createHorizontalChain(sectionInfo.getId(), ConstraintSet.LEFT,
                            sectionInfo.getId(), ConstraintSet.RIGHT,
                            new int[] {mentor_name.getId(), mentor_grade.getId()}, null, ConstraintSet.CHAIN_SPREAD);
                    sectionSet.connect(mentor_grade.getId(), ConstraintSet.BASELINE, mentor_name.getId(), ConstraintSet.BASELINE);

                }

                mentor_index++;

                sectionSet.applyTo(sectionInfo);
            }
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
