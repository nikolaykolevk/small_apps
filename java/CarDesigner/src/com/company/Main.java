package com.company;

import java.io.*;
import java.util.Scanner;

public class Main {

    enum Make {audi, mercedes};
    public static void main(String[] args) {

        String m;
        Scanner sc = new Scanner(System.in);

            int ch;

            File cars = new File("cars.txt");
            if (cars.exists()) {
                System.out.println("There are previous configurations: \n");
                try {
                    FileReader fr = new FileReader("cars.txt");

                    while ((ch = fr.read()) != -1)
                        System.out.print((char) ch);

                    // close the file
                    fr.close();
                } catch (IOException e) {
                    System.out.println("There was an error" + e);
                }
            }

            Vehicle car;

        System.out.println("Chose a make ");
        for (Make make : Make.values()) {
            System.out.println(make);
        }
        m = sc.next();
        m.toLowerCase();
        System.out.println();

        if (Make.valueOf(m)==Make.audi) {
            car = new Audi();
        } else {
            car = new Mercedes();
        }

        String str = car.describe(1);

    try {
        FileWriter fw = new FileWriter("cars.txt", true);

        // read character wise from string and write
        // into FileWriter
        for (int i = 0; i < str.length(); i++)
            fw.write(str.charAt(i));
        //close the file
        fw.close();
        System.out.println("Build saved");
    } catch (IOException e) {
        System.out.println("There was an error" + e);
    }
    }

}
