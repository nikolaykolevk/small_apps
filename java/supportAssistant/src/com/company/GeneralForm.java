package com.company;

import java.io.File;
import java.io.FileWriter;
import java.io.IOException;

public class GeneralForm {
    private String name, company;
    private int phone;



    public GeneralForm(String name, String company, int phone) {

        this.name = name;
        this.company = company;
        this.phone = phone;


    }

    protected void saveFile (String str) {

        int u = 1;
        String fileName = name+".txt";
        File form = new File(fileName);

        while (form.exists()){
            fileName = name + Integer.toString(u) + ".txt";
            form = new File(fileName);
            u++;
        }

        String str2 = fileName.substring(0,fileName.length()-4) + "\n";

        try {

            FileWriter fw = new FileWriter("forms.txt", true);

            for (int i = 0; i < str2.length(); i++)
                fw.write(str2.charAt(i));
            fw.close();
        } catch (IOException e) {
            System.out.println("Error" + e);
        }


        try {

            FileWriter fw = new FileWriter(fileName);


            for (int i = 0; i < str.length(); i++)
                fw.write(str.charAt(i));

            System.out.println("Saved");

            fw.close();
        } catch (IOException e) {
            System.out.println("Error" + e);
        }
    }

    public String getCompany() {
        return company;
    }

    public void setCompany(String company) {
        this.company = company;
    }

    public int getPhone() {
        return phone;
    }

    public void setPhone(int phone) {
        this.phone = phone;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }
}
