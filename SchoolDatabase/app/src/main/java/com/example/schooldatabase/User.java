package com.example.schooldatabase;

import java.util.Date;

public class User {
    String email;
    String ID;
    String name;
    Date expirationDate;

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
}
