package com.company;


public class Body {
    private double weight, price;
    private boolean carbon;

    public boolean isCarbon() {
        return carbon;
    }

    public void setCarbon(boolean carbon) {
        this.carbon = carbon;
    }

    public double getPrice() {
        return price;
    }

    public enum Seats {one, two, four, five, six, seven, eight}
    private Seats seats;

    public void setSeats (Seats seats) {
        seats = seats;
    }

    public Seats getSeats () {
        return seats;
    }

    public enum Level {low, medium_low, medium, medium_high, high}
    private Level trunkSpace;

    public void setTrunkSpace (Level trunkSpace) {
        this.trunkSpace = trunkSpace;
    }

    public Level getTrunkSpace () {
        return trunkSpace;
    }

    public double getWeight () {
        return weight;
    }

    private double calcWeight () {

        double total = 400;
        switch (seats) {
            case one: total *= 1.15;
                break;
            case two: total *= 1.3;
                break;
            case four: total *= 1.45;
                break;
            case five: total *= 1.5;
                break;
            case six: total *= 1.7;
                break;
            case seven: total *= 1.8;
                break;
            case eight: total *= 2;
                break;
        }

        switch (trunkSpace) {
            case high: total *= 1.30;
                break;
            case medium_high: total *= 1.25;
                break;
            case medium: total *= 1.20;
                break;
            case medium_low: total *= 1.15;
                break;
            case low: total *= 1.10;
                break;
        }

        total *= carbon==true ? 0.8 : 1;

        return total;
    }

    private double calcPrice () {
        double total = 10000;
        switch (seats) {
            case one: total *= 1;
                break;
            case two: total *= 1.3;
                break;
            case four: total *= 1.55;
                break;
            case five: total *= 1.7;
                break;
            case six: total *= 1.8;
                break;
            case seven: total *= 1.9;
                break;
            case eight: total *= 2.7;
                break;
        }

        switch (trunkSpace) {
            case high: total *= 1.50;
                break;
            case medium_high: total *= 1.25;
                break;
            case medium: total *= 1.20;
                break;
            case medium_low: total *= 1.15;
                break;
            case low: total *= 1.05;
                break;
        }

        total *= carbon==true ? 1.9 : 1;

        return total;
    }

    private void calcPropeties() {
        weight = calcWeight();
        price = calcPrice();
    }

    public Body (Seats seats, boolean carbon, Level trunkSpace) {
        this.seats = seats;
        this.carbon = carbon;
        this.trunkSpace = trunkSpace;
        calcPropeties();
        System.out.println(weight);

    }


}
