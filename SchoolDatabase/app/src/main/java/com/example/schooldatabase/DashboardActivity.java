package com.example.schooldatabase;

import android.content.Context;
import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

public class DashboardActivity extends AppCompatActivity {
    private SessionManager session;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_dashboard);

        session = new SessionManager(getApplicationContext());

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

        //get user info
        //get whether user is parent, moderator, mentor, mentee
        //show buttons based on that
        User user = session.getUserDetails();

        TextView textView = (TextView) findViewById(R.id.dashboard_welcome);
        textView.append(" " + user.getName());

        // Show grade level at top of page, if applicable
        textView = (TextView) findViewById(R.id.dashboard_grade_level);
        if(!user.isParent()) {
            int grade = user.getGrade();
            switch (grade) {
                case 1:
                    textView.append(" " + getResources().getText(R.string.freshman));
                    break;
                case 2:
                    textView.append(" " + getResources().getText(R.string.sophomore));
                    break;
                case 3:
                    textView.append(" " + getResources().getText(R.string.junior));
                    break;
                case 4:
                    textView.append(" " + getResources().getText(R.string.senior));
                    break;
                default:
                    textView.append(" None");
            }
        } else {
            textView.setVisibility(View.GONE);
        }

        if(user.isParent()) {
            Button button = (Button) findViewById(R.id.dashboard_change_child_profile);
            button.setVisibility(View.VISIBLE);
            button = findViewById(R.id.dashboard_view_schedule);
            button.setVisibility(View.GONE);
        }

        if(user.isMentor()) {
            Button button = (Button) findViewById(R.id.dashboard_view_mentor);
            button.setVisibility(View.VISIBLE);
        }

        if(user.isMentee()) {
            Button button = (Button) findViewById(R.id.dashboard_view_mentee);
            button.setVisibility(View.VISIBLE);
        }

        if(user.isModerator()) {
            Button button = (Button) findViewById(R.id.dashboard_view_moderator);
            button.setVisibility(View.VISIBLE);
        }
    }

    @Override
    public void onResume() {
        super.onResume();
        User user = session.getUserDetails();
        TextView welcome = (TextView) findViewById(R.id.dashboard_welcome);
        welcome.setText(R.string.welcome);
        welcome.append(" " + user.getName());
    }

    @Override
    public void onBackPressed() {
        // prevent user from returning to main activity if logged in
        moveTaskToBack(true);
    }

    public void logoutButton(View view) {
        session.logoutUser();
        Context context = getApplicationContext();
        CharSequence message = "You have been successfully logged out.";
        int duration = Toast.LENGTH_SHORT;
        Toast toast = Toast.makeText(context, message, duration);

        toast.show();

        Intent intent = new Intent(this, MainActivity.class);
        startActivity(intent);
        finish();
    }

    public void gotoChangeProfile(View view) {
        Intent intent = new Intent(this, ChangeProfileActivity.class);
        startActivity(intent);
    }

    public void gotoSelectChildProfile(View view) {
        Intent intent = new Intent(this, SelectChildProfileActivity.class);
        startActivity(intent);
    }

    public void gotoViewSections(View view) {
        Intent intent = new Intent(this, ViewSectionsActivity.class);
        intent.putExtra("url","/code/project/api/all_classes.php");
        intent.putExtra("type",0);
        intent.putExtra("class",0);
        intent.putExtra("sec",0);
        startActivity(intent);
    }

    public void gotoViewSchedule(View view) {
        Intent intent = new Intent(this, ViewScheduleActivity.class);
        startActivity(intent);
    }

    public void gotoMentorStatus(View view) {
        Intent intent = new Intent(this, ClassStatusActivity.class);
        intent.putExtra("url", "/code/project/api/mentor.php");
        intent.putExtra("type", "mentor");
        startActivity(intent);
    }

    public void gotoMenteeStatus(View view) {
        Intent intent = new Intent(this, ClassStatusActivity.class);
        intent.putExtra("url", "/code/project/api/mentee.php");
        intent.putExtra("type", "mentee");
        startActivity(intent);
    }

    public void gotoModeratorStatus(View view) {
        Intent intent = new Intent(this, ClassStatusActivity.class);
        intent.putExtra("url", "/code/project/api/moderator.php");
        intent.putExtra("type", "moderator");
        startActivity(intent);
    }
}
