package com.company;

import java.util.Scanner;

public class Audi extends Vehicle{

    Audi () {
        super();
        make = "Audi";

        createEngine(Engine.Fuel.Gas, Engine.Valves.twelve, 6.3);
        createEngine(Engine.Fuel.Gas, Engine.Valves.eight, 5.8);
        createEngine(Engine.Fuel.Diesel, Engine.Valves.six, 3.0);
        createBody(Body.Seats.seven, false, Body.Level.high);
        createBody(Body.Seats.five, true, Body.Level.high);
        createBody(Body.Seats.five, true, Body.Level.medium_low);
        createBody(Body.Seats.five, false, Body.Level.medium_low);
        createBody(Body.Seats.four, false, Body.Level.low);

        System.out.println("You chose AUDI!");

        int a;
        String b;
        Scanner sc = new Scanner(System.in);

        System.out.println("Bodies : \n");
        listBodies();
        a = sc.nextInt();
        body = bodies.get(a-1);
        System.out.println();

        System.out.println("Engines : \n");
        listEngines();
        a = sc.nextInt();
        engine = engines.get(a-1);
        System.out.println();

        System.out.println("Pick a paint");
        for (Paint paint : Paint.values()) {
            System.out.println(paint);
        }
        b = sc.next();
        b.toLowerCase();
        paint = Paint.valueOf(b);
        System.out.println();

        System.out.println("Would you like to tune the car ? (Y/N)");
        b = sc.next();
        b.toLowerCase();
        if (b.equals("y")) tune();
        System.out.println();

        hp = engine.getHorsepowers();
        weight = body.getWeight() + engine.getWeight() + 300;
        price = body.getPrice() + engine.getPrice() + 3000;
        efficiency = engine.getEfficiency();

        System.out.println();
        describe();

        System.out.println();
        horn();



    }
}
