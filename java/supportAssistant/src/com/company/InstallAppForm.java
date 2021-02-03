package com.company;

import java.util.Scanner;

public class InstallAppForm extends GeneralForm{

    private int pcNum;
    private String app;

    public InstallAppForm (String name, String company, int phone) {

        super(name,company,phone);
        Scanner sc = new Scanner(System.in);
        int num;
        String appName;

        System.out.println("Enter the number of your pc: ");
        num = sc.nextInt();
        pcNum = num;

        System.out.println("Enter the application you want to install: ");
        appName = sc.next();
        app = appName;

        String text="Name:" + name + "\n";
        text += "Phone: " + phone + "\n";
        text += app + " must be installed on PC Number " + pcNum + "\n";
        saveFile(text);



    }

}
