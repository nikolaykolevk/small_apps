package com.company;

import java.io.*;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

public class Dnevnik {
    protected String klas;
    protected List<String> predmeti;
    protected List<String> uchenici;
    protected ArrayList<String>[] ocenki;

    public List<String> getPredmeti() {
        return predmeti;
    }

    protected void setPredmeti(List<String> predmeti) {
        this.predmeti = predmeti;
    }

    protected List<String> getUchenici() {
        return uchenici;
    }

    protected void setUchenici(List<String> uchenici) {
        this.uchenici = uchenici;
    }

    protected ArrayList<String>[] getOcenki() {
        return ocenki;
    }

    protected void setOcenki(ArrayList<String>[] ocenki) {
        this.ocenki = ocenki;
    }

    protected Dnevnik () {
        this.predmeti = new ArrayList<>();
        this.uchenici = new ArrayList<>();
        this.ocenki = new ArrayList[0];
    }

    public Dnevnik(List<String> predmeti, List<String> uchenici, String klas) {
        this.predmeti = predmeti;
        this.uchenici = uchenici;
        this.ocenki = new ArrayList[this.uchenici.size()];
        for (int u = 0; u < uchenici.size(); u++) {
            ocenki[u] = new ArrayList<String>();
        }
        this.klas = klas;
        zapazi();
    }

    public Dnevnik(List<String> uchenici, String klas) {
        this.predmeti = new ArrayList<>();
        this.uchenici = uchenici;
        this.ocenki = new ArrayList[this.uchenici.size()];
        for (int u = 0; u < uchenici.size(); u++) {
            ocenki[u] = new ArrayList<String>();
        }
        this.klas = klas;
        zapazi();
    }

    public Dnevnik(String klas) {
        this.klas = klas;
        predmeti = new ArrayList<>();
        uchenici = new ArrayList<>();
        zaredi();


    }

    public void tmp() {
        for (String predmet : predmeti) {
            System.out.println("asd" + predmet);
        }
    }

    protected void zaredi() {

        String fileName = klas + ".txt";
        int nomer = 0, ch = -2;
        FileReader fr = null;
        boolean readName = false, ocenka = false;
        String predmet = "", red = "";

        try {

            fr = new FileReader(fileName);


            while (ch != -1) {
                red = "";
                while ((ch = fr.read()) != 10 && ch != -1) {
                    red += (char) ch;
                }

//                if (red.length() > 0)
//                    red = red.substring(0, red.length() - 1);

                if (red.equals("endImena")) {
                    readName = true;
                    ocenki = new ArrayList[uchenici.size()];
                    for (int u = 0; u < uchenici.size(); u++) {
                        ocenki[u] = new ArrayList<String>();
                    }
                    continue;
                }

                if (!readName) {
                    uchenici.add(red);
                    continue;
                }

                if (!ocenka && red.length() > 1) {
                    nomer = 0;
                    predmet = red;
                    predmeti.add(predmet);
                    ocenka = true;
                } else {
                    if (ch == -1)
                        break;

                    if (red.equals("end" + predmet)) {
                        ocenka = false;
                        System.out.println();
                        continue;
                    }
                    ocenki[nomer].add(red);
                    nomer++;
                }
            }


            fr.close();


        } catch (IOException e) {
            System.out.println("greshka");
        }

    }


    protected void zapazi() {


        try {
            FileWriter fw = new FileWriter(klas + ".txt");


            for (String name : uchenici) {
                fw.write(name + "\n");
            }
            fw.write("endImena\n");


            for (String predmet : predmeti) {
                fw.write(predmet + "\n");
                for (int i = 0; i < uchenici.size(); i++) {
                    fw.write(ocenki[i].get(predmeti.indexOf(predmet)) + "\n");
                }
                fw.write("end" + predmet + "\n");
            }


            System.out.println("Saving successful");
            //close the file
            fw.close();
        } catch (IOException e) {
            System.out.println("greshka");
        }

    }

    public void dobaviOcenka(int nomer, int ocenka, String predmet) {
        if (predmeti.indexOf(predmet) >= 0) {
            ocenki[nomer - 1].set(predmeti.indexOf(predmet), ocenki[nomer - 1].get(predmeti.indexOf(predmet)) + ocenka);
            zapazi();

        } else {
            System.out.println("Greshen predmet");
        }
    }

    public void dobaviPredmet(String predmet) {
        if (!predmeti.contains(predmet)) {
            predmeti.add(predmet);

            for (int i = 0; i < uchenici.size(); i++)
                ocenki[i].add("");

            zapazi();
        } else {
            System.out.println("predmetut sushtestvuva");
        }
    }

    public void pokajiPredmeti() {
        for (String predmet : predmeti)
            System.out.println(predmet);
    }


    public void pokajiOcenki() {
        for (String predmet : predmeti) {
            System.out.println(predmet);
            for (int i = 0; i < uchenici.size(); i++) {
                System.out.print("Nomer " + (i + 1) + ": ");
                for (int u = 0; u < ocenki[i].get(predmeti.indexOf(predmet)).length(); u++) {
                    System.out.print(ocenki[i].get(predmeti.indexOf(predmet)).charAt(u) + " ");
                }
                System.out.println();
            }
            System.out.println();
        }
    }


    public double sredenUspehPoPredmet(String predmet) {
        double suma1=0, suma2=0, chislo = 1;

        for (int i=0; i<uchenici.size(); i++) {
            for (int u = 0; u < ocenki[i].get(predmeti.indexOf(predmet)).length(); u++) {
                suma1+= Character.getNumericValue(ocenki[i].get(predmeti.indexOf(predmet)).charAt(u));
            }
            chislo = suma1/(ocenki[i].get(predmeti.indexOf(predmet)).length());
            suma2+=chislo;
            suma1=0;
        }
        chislo = suma2/(uchenici.size());

        return chislo;
    }


}
