package com.company;

public class Engine {
    private int horsepowers;
    private double volume, weight, efficiency, price;

    public enum Fuel {Gas, Diesel, LPG};
    private Fuel fuel;

    public enum Valves {three, four, six, eight, twelve};
    private Valves valves;

    public int getHorsepowers() {
        return horsepowers;
    }

    public double getVolume() {
        return volume;
    }

    public void setVolume(double volume) {
        this.volume = volume;
        calcProperties();
    }

    public double getWeight() {
        return weight;
    }

    public double getEfficiency() {
        return efficiency;
    }

    public double getPrice() {
        return price;
    }

    public void setFuel (Fuel fuel) {
        this.fuel = fuel;
        calcProperties();
    }

    public Fuel getFuel () {
        return fuel;
    }

    public void setValves (Valves valves) {
        this.valves = valves;
        calcProperties();
    }

    public Valves getValves () {
        return valves;
    }

    private int calcHp () {
        double total = 100;
        switch (fuel) {
            case Gas: total *= 1.7;
            break;
            case Diesel: total *= 1.5;
            break;
            case LPG: total *= 1.2;
            break;
        }

        switch (valves) {
            case twelve: total *= 2.3;
                break;
            case eight: total *= 2;
                break;
            case six: total *= 1.7;
                break;
            case four: total *= 1.5;
                break;
            case three: total *= 1;
                break;

        }
        if (volume < 1.6) {
            total = total;
        } else if (volume > 1.6 && volume < 2) {
            total *= 1.15;
        } else if (volume >= 2 && volume <= 2.4) {
            total *= 1.25;
        } else if (volume >= 2.4 && volume <= 3) {
            total *= 1.45;
        } else if (volume >= 3 && volume <= 6) {
            total *= 1.75;
        } else if (volume > 6) {
            total *= 2;
        }

        return (int) total;
    }

    private double calcWeight () {
        double total = 150;
        switch (fuel) {
            case Gas: total *= 1.1;
                break;
            case Diesel: total *= 1.3;
                break;
            case LPG: total *= 1.2;
                break;
        }

        switch (valves) {
            case twelve: total *= 2.3;
                break;
            case eight: total *= 2;
                break;
            case six: total *= 1.7;
                break;
            case four: total *= 1.5;
                break;
            case three: total *= 1;
                break;

        }
        if (volume < 1.6) {
            total *= 0.85;
        } else if (volume > 1.6 && volume < 2) {
            total *= 1;
        } else if (volume >= 2 && volume <= 2.4) {
            total *= 1.15;
        } else if (volume >= 2.4 && volume <= 3) {
            total *= 1.25;
        } else if (volume >= 3 && volume <= 6) {
            total *= 1.45;
        } else if (volume > 6) {
            total *= 1.6;
        }

        return total;
    }

    private double calcEfficiency () {
        double total = 1;
        switch (fuel) {
            case Gas: total *= 3;
                break;
            case Diesel: total *= 2;
                break;
            case LPG: total *= 2.5;
                break;
        }

        switch (valves) {
            case twelve: total *= 5;
                break;
            case eight: total *= 3;
                break;
            case six: total *= 2.4;
                break;
            case four: total *= 1.6;
                break;
            case three: total *= 1.1;
                break;

        }
        if (volume < 1.6) {
            total = total;
        } else if (volume > 1.6 && volume < 2) {
            total *= 1.2;
        } else if (volume >= 2 && volume <= 2.4) {
            total *= 1.35;
        } else if (volume >= 2.4 && volume <= 3) {
            total *= 1.45;
        } else if (volume >= 3 && volume <= 6) {
            total *= 1.55;
        } else if (volume > 6) {
            total *= 1.7;
        }

        return total;
    }

    private double calcPrice () {
        double total = 4000;
        switch (fuel) {
            case Gas: total *= 2.6;
                break;
            case Diesel: total *= 2.5;
                break;
            case LPG: total *= 1.8;
                break;
        }

        switch (valves) {
            case twelve: total *= 4;
                break;
            case eight: total *= 3;
                break;
            case six: total *= 2.4;
                break;
            case four: total *= 1.6;
                break;
            case three: total *= 1.3;
                break;
        }

        if (volume < 1.6) {
            total = total;
        } else if (volume > 1.6 && volume < 2) {
            total *= 1.10;
        } else if (volume >= 2 && volume <= 2.4) {
            total *= 1.45;
        } else if (volume >= 2.4 && volume <= 3) {
            total *= 1.75;
        } else if (volume >= 3 && volume <= 6) {
            total *= 2;
        } else if (volume >= 6) {
            total *= 2.8;
        }

        return total;
    }

    private void calcProperties () {
        horsepowers = calcHp();
        weight = calcWeight();
        efficiency = calcEfficiency();
        price = calcPrice();
    }

    public Engine (Fuel fuel, Valves valves, double volume) {

        this.fuel = fuel;
        this.valves = valves;
        this.volume = volume;
        calcProperties();
        System.out.println(horsepowers);

    }
}
