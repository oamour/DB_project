package com.example.schooldatabase;

import android.app.SearchManager;
import android.content.Context;
import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
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

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;

public class ViewScheduleActivity extends AppCompatActivity {
    private SessionManager session;
    private RequestQueue queue;
    private JSONArray sections = new JSONArray();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_view_schedule);

        session = new SessionManager(getApplicationContext());
        queue = Volley.newRequestQueue(this);

        User user = session.getUserDetails();

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

        getClassInfo();
    }

    public void getClassInfo() {
        User user = session.getUserDetails();
        String url = MainActivity.HREF + "/code/project/api/schedule.php";
        JSONArray requestContent = new JSONArray();
        try {
            requestContent.put(0,  Integer.parseInt(user.getID()));
        } catch (JSONException e) {
            Log.d("JsonException", e.toString());
        }

        Log.d("getClassInfo", requestContent.toString());
        JsonArrayRequest getRequest = new JsonArrayRequest(Request.Method.POST, url, requestContent,
                new Response.Listener<JSONArray>()
                {
                    @Override
                    public void onResponse(JSONArray response) {
                        // response
                        Log.d("Response", response.toString());

                        try {
                            sections = response;
                            createScheduleButtons();
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

    public void createScheduleButtons() {
        try {
            int i = 0;
            while(!sections.isNull(i)) {
                JSONObject section = sections.getJSONObject(i);
                //check if class is in past or future
                SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd");
                Date startDate, endDate;
                try {
                    startDate = sdf.parse(section.getString("startDate"));
                    endDate = sdf.parse(section.getString("endDate"));
                } catch (ParseException e) {
                    // If date fails to parse, just display the class.
                    Log.d("ParseException", e.toString());
                    startDate = new Date();
                    endDate = new Date();
                }
                Date today = new Date();
                Log.d("createScheduleButtons", "StartDate: " + startDate.toString() +
                        "\nEndDate: " + endDate.toString() + "\ntoday: " + today.toString());
                if (startDate.after(today) || endDate.before(today)) {
                    // class not currently in progress, skip
                    i++;
                    continue;
                }

                int timeSlotID = section.getInt("timeSlot");
                String day = "";
                String time = "";
                Log.d("createScheduleButtons", "TimeSlot is " + timeSlotID);
                switch(timeSlotID) {
                    case 1:
                        day = "mwf";
                        time = "800";
                        break;
                    case 2:
                        day = "mwf";
                        time = "900";
                        break;
                    case 3:
                        day = "mwf";
                        time = "1000";
                        break;
                    case 4:
                        day = "mwf";
                        time = "1100";
                        break;
                    case 5:
                        day = "mwf";
                        time = "1200";
                        break;
                    case 6:
                        day = "mwf";
                        time = "1300";
                        break;
                    case 7:
                        day = "mwf";
                        time = "1400";
                        break;
                    case 8:
                        day = "mwf";
                        time = "1500";
                        break;
                    case 9:
                        day = "mwf";
                        time = "1600";
                        break;
                    case 10:
                        day = "mwf";
                        time = "1700";
                        break;
                    case 11:
                        day = "tth";
                        time = "800";
                        break;
                    case 12:
                        day = "tth";
                        time = "930";
                        break;
                    case 13:
                        day = "tth";
                        time = "1100";
                        break;
                    case 14:
                        day = "tth";
                        time = "1230";
                        break;
                    case 15:
                        day = "tth";
                        time = "1400";
                        break;
                    case 16:
                        day = "tth";
                        time = "1530";
                        break;
                    case 17:
                        day = "tth";
                        time = "1700";
                        break;
                    case 18:
                        day = "sat";
                        time = "800";
                        break;
                    case 19:
                        day = "sat";
                        time = "1100";
                        break;
                    case 20:
                        day = "sat";
                        time = "1400";
                        break;
                    default:
                }
                Log.d("createScheduleButtons", "Day is " + day + ", time is " + time);
                Log.d("createScheduleButtons", "" + getResources().getIdentifier("mon_" + time, "id", getPackageName()));

                final int index = i;
                View.OnClickListener listener = new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        showClassInfo(index);
                    }
                };
                if(day.equals("mwf")) {
                    // monday, wednesday. friday classes
                    TextView monView = findViewById(getResources().getIdentifier("mon_" + time, "id", getPackageName()));
                    TextView wedView = findViewById(getResources().getIdentifier("wed_" + time, "id", getPackageName()));
                    TextView friView = findViewById(getResources().getIdentifier("fri_" + time, "id", getPackageName()));

                    monView.setText(section.getString("sectionName"));
                    wedView.setText(section.getString("sectionName"));
                    friView.setText(section.getString("sectionName"));

                    monView.setBackgroundResource(R.color.peach);
                    wedView.setBackgroundResource(R.color.peach);
                    friView.setBackgroundResource(R.color.peach);

                    monView.setOnClickListener(listener);
                    wedView.setOnClickListener(listener);
                    friView.setOnClickListener(listener);
                } else if(day.equals("tth")) {
                    // tuesday, thursday classes
                    TextView tueView = findViewById(getResources().getIdentifier("tue_" + time, "id", getPackageName()));
                    TextView thursView = findViewById(getResources().getIdentifier("thu_" + time, "id", getPackageName()));

                    tueView.setText(section.getString("sectionName"));
                    thursView.setText(section.getString("sectionName"));

                    tueView.setBackgroundResource(R.color.peach);
                    thursView.setBackgroundResource(R.color.peach);

                    tueView.setOnClickListener(listener);
                    thursView.setOnClickListener(listener);
                } else if(day.equals("sat")) {
                    // saturday classes
                    TextView satView = findViewById(getResources().getIdentifier("sat_" + time, "id", getPackageName()));
                    satView.setText(section.getString("sectionName"));
                    satView.setBackgroundResource(R.color.peach);
                    satView.setOnClickListener(listener);
                }
                i++;
            }
        } catch (JSONException e) {
            Log.d("JsonException", e.toString());
        }
    }

    private void showClassInfo(int sectionIndex) {
        try {
            Log.d("showClassInfo", "Triggered!");
            // send class info to new activity
            Intent intent = new Intent(this, ScheduleSectionActivity.class);
            intent.putExtra("section", sections.getJSONObject(sectionIndex).toString());
            startActivity(intent);
        } catch (JSONException e){
            Log.d("JsonException", e.toString());
        }

    }
}
