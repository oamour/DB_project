package com.example.schooldatabase;

import android.content.Context;
import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.widget.TextView;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import org.w3c.dom.Text;

public class ScheduleSectionActivity extends AppCompatActivity {
    private SessionManager session;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_schedule_section);

        session = new SessionManager(getApplicationContext());
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

        if(getIntent().getExtras() != null && !getIntent().getExtras().isEmpty()){
            try {
                String mwf = "Monday, Wednesday, Friday ";
                String tth = "Tuesday, Thursday ";
                String sat = "Saturday ";
                JSONObject section = new JSONObject(getIntent().getStringExtra("section"));
                TextView courseName = findViewById(R.id.courseName);
                courseName.setText(section.getString("courseName"));

                TextView sectionID = findViewById(R.id.sectionID);
                sectionID.setText(sectionID.getText() + section.getString("sectionID") + ":");

                TextView sectionName = findViewById(R.id.sectionName);
                sectionName.setText(section.getString("sectionName"));

                TextView courseDesc = findViewById(R.id.courseDesc);
                courseDesc.setText(section.getString("courseDesc"));

                TextView userType = findViewById(R.id.userType);
                userType.setText(section.getString("userType"));

                TextView timeSlot = findViewById(R.id.timeSlot);
                String timeSlotString = "";
                switch(section.getInt("timeSlot")) {
                    case 1:
                        timeSlotString = mwf + "8:00 AM - 8:50 AM";
                        break;
                    case 2:
                        timeSlotString = mwf + "9:00 AM - 9:50 AM";
                        break;
                    case 3:
                        timeSlotString = mwf + "10:00 AM - 10:50 AM";
                        break;
                    case 4:
                        timeSlotString = mwf + "11:00 AM - 11:50 AM";
                        break;
                    case 5:
                        timeSlotString = mwf + "12:00 PM - 12:50 PM";
                        break;
                    case 6:
                        timeSlotString = mwf + "1:00 PM - 1:50 PM";
                        break;
                    case 7:
                        timeSlotString = mwf + "2:00 PM - 2:50 PM";
                        break;
                    case 8:
                        timeSlotString = mwf + "3:00 PM - 3:50 PM";
                        break;
                    case 9:
                        timeSlotString = mwf + "4:00 PM - 4:50 PM";
                        break;
                    case 10:
                        timeSlotString = mwf + "5:00 PM - 5:50 PM";
                        break;
                    case 11:
                        timeSlotString = tth + "8:00 AM - 9:15 AM";
                        break;
                    case 12:
                        timeSlotString = tth + "9:30 AM - 10:45 AM";
                        break;
                    case 13:
                        timeSlotString = tth + "11:00 AM - 12:15 PM";
                        break;
                    case 14:
                        timeSlotString = tth + "12:30 PM - 1:45 PM";
                        break;
                    case 15:
                        timeSlotString = tth + "2:00 PM - 3:15 PM";
                        break;
                    case 16:
                        timeSlotString = tth + "3:30 PM - 4:45 PM";
                        break;
                    case 17:
                        timeSlotString = tth + "5:00 PM - 6:15 PM";
                        break;
                    case 18:
                        timeSlotString = sat + "8:00 AM - 10:50 AM";
                        break;
                    case 19:
                        timeSlotString = sat + "11:00 AM - 1:50 PM";
                        break;
                    case 20:
                        timeSlotString = sat + "2:00 PM - 4:50 PM";
                        break;
                    default:
                }
                timeSlot.setText(timeSlotString);

                TextView dateRange = findViewById(R.id.dateRange);
                dateRange.setText(section.getString("startDate") + " to " + section.getString("endDate"));

            } catch (JSONException e) {
                Log.d("JsonException", e.toString());
            }
        }
    }
}
