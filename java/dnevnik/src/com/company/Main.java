package com.company;

import java.util.ArrayList;
import java.util.List;

public class Main {

    public static void main(String[] args) {
	Dnevnik b12 = new Dnevnik("12b");
		Dnevnik v12 = new Dnevnik("12v");

	b12.sredenUspehPoPredmet("Angliiski");

	List <String> uchenici = new ArrayList<>();
	uchenici.add("Krido asd");
	uchenici.add("Pavel");
	uchenici.add("Karachomakov");

	Dnevnik a11 = new Dnevnik(uchenici, "11a");
	a11.dobaviPredmet("Angliiski");

	List <Dnevnik> dnevnici = new ArrayList<>();
	dnevnici.add(b12);
		dnevnici.add(v12);

    }
}
