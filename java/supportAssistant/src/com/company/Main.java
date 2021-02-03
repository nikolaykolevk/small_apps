package com.company;

import java.io.FileReader;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;



public class Main {

    public static void main(String[] args) {

        Scanner sc = new Scanner(System.in);
        String a;
        System.out.println("Do you want to read a form (y/n) : ");
        a = sc.next();
        List <String> list = new ArrayList<>();
        if(a.toLowerCase().equals("y")) {
            a = "";

            System.out.println("Chose application to read: ");

            int ch;

            FileReader fr;
            try {
                fr = new FileReader("forms.txt");


                while ((ch = fr.read()) != -1) {
                    if ((char) ch == '\n') {
                        list.add(a);
                        a = "";
                    }
                    a += (char) ch;

                }


                fr.close();
            } catch(IOException e)
            {
                System.out.println("File not found");
            }

            for (String form : list) {
                System.out.println(form);
            }


            System.out.println();

            a = sc.next();

            System.out.println();


            try {
                fr = new FileReader(a+".txt");


                while ((ch = fr.read()) != -1)
                    System.out.print((char) ch);


                fr.close();
            } catch(IOException e)
            {
                System.out.println("Form not found");
            }

            System.out.println();


        }


//


        GeneralForm form;
        String name, company;
        int phone;


        System.out.println("Do you want to create a new form (y/n) ?");
        a = sc.next();
        if (a.toLowerCase().equals("y")) {

            System.out.println("Enter your name: ");
            a = sc.next();
            name = a;

            System.out.println("Enter your company: ");
            a = sc.next();
            company = a;

            System.out.println("Enter your phone: ");
            a = sc.next();
            phone = Integer.parseInt(a);

            System.out.println("Chose an option: ");
            System.out.println("1. reinstall pc");
            System.out.println("2. Install app");
            System.out.println();

            a = sc.next();

            if (Integer.parseInt(a) == 1) {
                form = new ReinstallPCForm(name, company, phone);
            } else if (Integer.parseInt(a) == 2) {
                form = new InstallAppForm(name, company, phone);
            } else {
                System.out.println("Invalid option");

            }

            System.out.println();






        }

    }
}
