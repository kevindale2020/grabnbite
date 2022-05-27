package com.example.grabnbiterider;

public class Rating {

    private int id;
    private String image;
    private String fname;
    private String lname;
    private String feedback;
    private String date;
    private float rate;

    public Rating() {
    }

    public Rating(int id, String image, String fname, String lname, String feedback, String date, float rate) {
        this.id = id;
        this.image = image;
        this.fname = fname;
        this.lname = lname;
        this.feedback = feedback;
        this.date = date;
        this.rate = rate;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getImage() {
        return image;
    }

    public void setImage(String image) {
        this.image = image;
    }

    public String getFname() {
        return fname;
    }

    public void setFname(String fname) {
        this.fname = fname;
    }

    public String getLname() {
        return lname;
    }

    public void setLname(String lname) {
        this.lname = lname;
    }

    public String getFeedback() {
        return feedback;
    }

    public void setFeedback(String feedback) {
        this.feedback = feedback;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public float getRate() {
        return rate;
    }

    public void setRate(float rate) {
        this.rate = rate;
    }
}
