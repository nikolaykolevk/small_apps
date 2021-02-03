package com.company;

import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;

public class Vehicle {

    protected enum Paint {red, yellow, green, blue, black, purple, pink, gray};
    protected List<Body> bodies;
    protected List<Engine> engines;

    Body body;
    Engine engine;

    protected int hp;
    protected double weight, price, efficiency;
    protected String make;

    protected boolean tuned;
    protected Paint paint;



    protected void horn () {
        System.out.println("BEEP");
    }

    protected double topSpeed () {
        return 80 * (weight/hp);
    }

    protected double brakeDist () {
        return topSpeed()*topSpeed()/(250*0.8);
    }

    protected void describe () {
        System.out.println("Your car: ");
        System.out.println("Paint: " + paint);
        System.out.println(String.format("Top speed: %.2f km/h", topSpeed()));
        System.out.println(String.format("Braking distance: %.2f m", brakeDist()));
        System.out.println("HP: " + hp);
        System.out.println(String.format("Weight: %.2f kg", weight));
        System.out.println(String.format("Price: %.2f $", price));
        System.out.println(String.format("Efficiency: %.2f l/100km", efficiency));
    }

    public String describe (int a) {
        String text="";
        text+="Your car: " + make +"\n";
        text+="Paint: " + paint +"\n";
        text+=String.format("Top speed: %.2f km/h", topSpeed()) +"\n";
        text+=String.format("Braking distance: %.2f m", brakeDist()) +"\n";
        text+="HP: " + hp +"\n";
        text+=String.format("Weight: %.2f kg", weight) +"\n";
        text+=String.format("Price: %.2f $", price) +"\n";
        text+=String.format("Efficiency: %.2f l/100km", efficiency) +"\n";
        text+="\n\n";


        return text;
    }



    protected void tune () {
        if (!tuned) {
            hp *= 1.4;
            price *= 1.2;
            efficiency *= 1.4;
        } else {
            System.out.println("Arleady tuned");
        }
        tuned = true;
    }

    protected void createBody (Body.Seats seats, boolean carbon, Body.Level trunkspace) {
        bodies.add(new Body(seats, carbon, trunkspace));
    }

    protected void listBodies () {

        int i = 1;

        for (Body body : bodies) {
            System.out.println(i);
            System.out.println("Seats: " + body.getSeats());
            System.out.println("Trunkspace: " + body.getTrunkSpace());
            if (body.isCarbon()) {System.out.println("Carbon Fibre");}
            System.out.println("Weight: " + body.getWeight());
            System.out.println("Price: " + body.getPrice());
            i++;

        }

    }

    protected void createEngine (Engine.Fuel fuel, Engine.Valves valves, double volume) {
        engines.add(new Engine(fuel, valves, volume));
    }

    protected void listEngines () {

        int i = 1;

        for (Engine engine : engines) {
            System.out.println(i);
            System.out.println("Volume: " + engine.getVolume() + "L");
            System.out.println("Efficiency: " + engine.getEfficiency());
            System.out.println("Fuel Type: " + engine.getFuel());
            System.out.println("HP: " + engine.getHorsepowers());
            System.out.println("Weight: " + engine.getWeight());
            System.out.println("Price: " + engine.getPrice());
            i++;
        }

    }


    public Vehicle () {
        engines = new ArrayList<Engine>();
        bodies = new ArrayList<Body>();
        tuned = false;
    }



}
