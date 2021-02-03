package com.company;

import java.io.File;
import java.io.FileWriter;
import java.io.IOException;

public class NotesMeta extends Note {
    private Boolean wasCreated;

    NotesMeta() {
        super("notesMeta", "json");
        wasCreated = false;

        File f = new File(this.fileName);

        if(f.exists() && !f.isDirectory()) {
            wasCreated = true;
        }

    }

    @Override
    public void write(String text)  {
        try {
            FileWriter fw = new FileWriter(this.fileName);

            for (int i = 0; i < text.length(); i++)
                fw.write(text.charAt(i));

            fw.close();
        } catch (IOException e) {
            System.out.println(e);
        }
    }

    public Boolean exist() {
        return wasCreated;
    }

}
