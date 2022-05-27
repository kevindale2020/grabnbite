package com.example.grabnbiterider;

public class Product {

    private int id;
    private String image;
    private String name;
    private String desc;
    private String addons;
    private double price;
    private double total;
    private int qty;

    public Product() {
        this.id = 0;
        this.image = null;
        this.name = null;
        this.desc = null;
        this.addons = null;
        this.price = 0;
        this.total = 0;
        this.qty = 0;
    }

    public Product(int id, String image, String name, String desc, String addons, double price, double total, int qty) {
        this.id = id;
        this.image = image;
        this.name = name;
        this.desc = desc;
        this.addons = addons;
        this.price = price;
        this.total = total;
        this.qty = qty;
    }

    public Product(int id, String name, double price, int qty) {
        this.id = id;
        this.name = name;
        this.price = price;
        this.qty = qty;
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

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getDesc() {
        return desc;
    }

    public void setDesc(String desc) {
        this.desc = desc;
    }

    public double getPrice() {
        return price;
    }

    public void setPrice(double price) {
        this.price = price;
    }

    public int getQty() {
        return qty;
    }

    public void setQty(int qty) {
        this.qty = qty;
    }

    public String getAddons() {
        return addons;
    }

    public void setAddons(String addons) {
        this.addons = addons;
    }

    public double getTotal() {
        return total;
    }

    public void setTotal(double total) {
        this.total = total;
    }
}
