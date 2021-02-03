package com.company;


import java.util.Date;
import java.util.Scanner;

public class Main {

    public static void main(String[] args) {

        NoteMenu nm = new NoteMenu();
        int opt;
        String text;
        Scanner sc = new Scanner(System.in);


        do {
            System.out.println("Chose an option \n");
            System.out.println("1.List all notes");
            System.out.println("2.Add a note");
            System.out.println("3.Delete a note");
            System.out.println("4.Add to note");
            System.out.println("5.Read a note");
            System.out.println("6.Quit");

            opt = sc.nextInt();
            switch (opt) {
                case 1:
                    nm.listNotes();
                    break;
                case 2:
                    System.out.println("Enter the name of the note");
                    text = sc.next();
                    nm.addNote(text);
                    break;
                case 3:
                    System.out.println("Enter the name of the note");
                    text = sc.next();
                    nm.deleteNote(text);
                    break;
                case 4:
                    System.out.println("Enter the name of the note");
                    text = sc.next();
                    nm.writeToNote(text);
                    break;
                case 5:
                    System.out.println("Enter the name of the note");
                    text = sc.next();
                    nm.readNote(text);
                    break;
                default:
                    opt = 0;
            }
        } while (opt != 0);

    }
}
