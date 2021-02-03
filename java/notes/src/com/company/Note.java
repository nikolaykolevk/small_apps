package com.company;

import java.io.*;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;

public class Note {

    private DateFormat dateFormat;
    private Date date;
    protected String fileName, name;


    Note (String name, Date d) {
        this.dateFormat =  new SimpleDateFormat("yyyy/MM/dd HH:mm:ss");
        this.date = d;
        this.name = name;
        this.fileName = name + ".txt";

        try {
            File yourFile = new File(this.fileName);
            yourFile.createNewFile();
        } catch (IOException e) {
            System.out.println("There was a problem when creating the note " + name);
        }

    }

    protected Note(String name, String ext) {
        this.name = name;
        this.fileName = name + "." + ext;
    }


    Note (String name) {
        this.dateFormat =  new SimpleDateFormat("yyyy/MM/dd HH:mm:ss");
        this.date = new Date();
        this.name = name;
        this.fileName = name + ".txt";

        try {
            File yourFile = new File(this.fileName);
            yourFile.createNewFile();
        } catch (IOException e) {
            System.out.println("There was a problem when creating the note " + name);
        }
    }
    public String getFileName() {
        return fileName;
    }

    public void setFileName(String fileName) {
        this.fileName = fileName;
    }

    public long getDate() {
        return date.getTime();
    }

    public void setDate(long date) {
        this.date = new Date(date);
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public DateFormat getDateFormat() {
        return this.dateFormat;
    }




    public String creationDate() {
        return "The note was created on " + this.dateFormat.format(this.date);
    }

    public String read() {
        String text = "";
        int ch;

        FileReader fr=null;
        try
        {
            fr = new FileReader(this.fileName);
        } catch (FileNotFoundException e) {
            System.out.println("File not found " + e);
        }

        try {
            while ((ch = fr.read()) != -1)
                text += (char) ch;

            // close the file
            fr.close();

        } catch (IOException e) {
            System.out.println("There was a problem when reading the note ");
        }

        return text;

    }

    public void write (String text) {
    text += "\n";

        try {
            FileWriter fw = new FileWriter(this.fileName, true);

            for (int i = 0; i < text.length(); i++)
                fw.write(text.charAt(i));

            System.out.println("Writing successful");
            fw.close();
        } catch (IOException e) {
            System.out.println("There was a problem when writing to the note ");
        }
    }

}
