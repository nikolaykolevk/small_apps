package com.company;

import org.json.*;

import java.io.File;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.text.ParseException;
import java.util.*;
import java.util.jar.JarEntry;

public class NoteMenu {

    List<Note> notes;
    NotesMeta meta;
    private String metaInf;

    public void listNotes() {

        for (Note note : notes) {
            System.out.println(note.getName());
        }

        System.out.println();

    }

    public void writeToNote(String name) {

        String text;
        Scanner sc = new Scanner(System.in);


        for (Note note : notes) {
            if (note.name.equals(name)) {

                System.out.print(note.read());
                text = sc.nextLine();

                note.write(text);
                System.out.println();

                return;
            }
        }

        System.out.println(name + " not found");


    }

    public void readNote(String name) {

        for (Note note : notes) {
            if (note.name.equals(name)) {

                System.out.println(note.read());
                System.out.println();
                return;
            }
        }

        System.out.println(name + " not found");

    }


    public void deleteNote(String name) {

        int i = 0;

        for (Note note : notes) {
            if (note.name.equals(name)) {
                notes.remove(i);

                try {
                    File f = new File(name + ".txt");           //file to be delete
                    if (f.delete())                      //returns Boolean value
                    {
                        System.out.println(name + " deleted");   //getting and printing the file name
                    }
                } catch (Exception e) {
                    System.out.println("There was an error");
                }
                initToFile();
                return;
            }
            i++;
        }

        System.out.println(name + " not found");




    }

    public void addNote(Note note) {

        if (note.getName().equals("")) {
            System.out.println("You cannot use that name!");
            return;
        }

        for (Note nt : notes) {
            if (nt.name.equals(note.getName())) {

                System.out.println(note.getName() + " arleady exists");
                System.out.println();
                return;
            }
        }


        this.notes.add(note);
        JSONArray JSON = new JSONArray(this.notes);

        meta.write(JSON.toString());
        System.out.println("Write your text: ");
    }

    public void addNote(String name) {

        if (name.equals("")) {
            System.out.println("You cannot use that name!");
            return;
        }

        for (Note nt : notes) {
            if (nt.name.equals(name)) {

                System.out.println(name + " arleady exists");
                System.out.println();
                return;
            }
        }

        Note note = new Note(name);
        this.notes.add(note);
        JSONArray JSON = new JSONArray(this.notes);

        meta.write(JSON.toString());
    }

    private void initToFile() {
        JSONArray JArray = new JSONArray(notes);
        meta.write(JArray.toString());
    }

    private void intFromFile() throws ParseException {
        JSONArray JArray = new JSONArray(meta.read());
        JSONObject JObject;
        Note note = new Note("");
        Date date;

        for (int i = 0; i < JArray.length(); i++) {
            JObject = JArray.getJSONObject(i);

            note.setDate(JObject.getLong("date"));
            note.setName(JObject.getString("name"));
            note.setFileName(JObject.getString("fileName"));
            addNote(note);
            note = new Note("");
        }


    }

    NoteMenu() {
        this.notes = new ArrayList<Note>();
        this.meta = new NotesMeta();

        if (meta.exist()) {
            try {
                intFromFile();
            } catch (ParseException e) {
                System.out.println("There was an error " + e);
            }
        }


    }


}
