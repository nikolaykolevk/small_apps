package com.company;

import java.util.Scanner;

public class ReinstallPCForm extends GeneralForm {
    private int pcNum, winVersion;

    public ReinstallPCForm (String name, String company, int phone) {

        super(name,company,phone);
        Scanner sc = new Scanner(System.in);
        int num;

        System.out.println("Enter the number of your pc: ");
        num = sc.nextInt();
        pcNum = num;

        System.out.println("Enter the version of windows you want: ");
        num = sc.nextInt();
        winVersion = num;

        String text="Name:" + name + "\n";
        text += "Phone: " + phone + "\n";
        text += "Windows " + winVersion + " must be installed on PC Number " + pcNum + "\n";
        saveFile(text);

    }
}
