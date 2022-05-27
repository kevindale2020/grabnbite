package com.example.grabnbiterider;

public class Order {

    private int id;
    private String name;
    private String date;
    private double total;
    private double fee;
    private String status;

    public Order() {
    }

    public Order(int id, String name, String date, double total, double fee, String status) {
        this.id = id;
        this.name = name;
        this.date = date;
        this.total = total;
        this.fee = fee;
        this.status = status;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public double getTotal() {
        return total;
    }

    public void setTotal(double total) {
        this.total = total;
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    public double getFee() {
        return fee;
    }

    public void setFee(double fee) {
        this.fee = fee;
    }
}