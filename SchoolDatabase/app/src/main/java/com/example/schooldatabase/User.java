package com.example.schooldatabase;

import java.util.Date;

public class User {
    String email;
    String ID;
    String name;
    Date expirationDate;
    int grade;
    boolean parent;
    boolean mentor;
    boolean mentee;
    boolean moderator;

    public void setEmail(String email) {
        this.email = email;
    }

    public void setID(String ID) {
        this.ID = ID;
    }

    public void setName(String name) {
        this.name = name;
    }

    public void setExpirationDate(Date expirationDate) {
        this.expirationDate = expirationDate;
    }

    public void setGrade(int grade) { this.grade = grade;}

    public void setParent(boolean parent) { this.parent = parent;}

    public void setMentor(boolean mentor) { this.mentor = mentor;}

    public void setMentee(boolean mentee) { this.mentee = mentee;}

    public void setModerator(boolean moderator) { this.moderator = moderator;}

    public String getEmail() {
        return email;
    }

    public String getID() {
        return ID;
    }

    public String getName() {
        return name;
    }

    public Date getExpDate() {
        return expirationDate;
    }

    public int getGrade() {
        return grade;
    }

    public boolean isParent() {
        return parent;
    }

    public boolean isMentee() {
        return mentee;
    }

    public boolean isMentor() {
        return mentor;
    }

    public boolean isModerator() {
        return moderator;
    }
}
