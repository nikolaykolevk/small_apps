package bg.reo101.Utilities;

import java.text.DecimalFormat;
import java.text.NumberFormat;

/* A Polynomial has all its coefficients in an array coeffs */
public class Polynomial {

    // coeffs[i] is the coefficient of the term x^i
    protected double[] coeffs;

    /* This Polynomial has coefficients as specified in the array*/
    public Polynomial(double[] coefficients) {
        coeffs = new double[coefficients.length + 1];
        for (int i = 0; i < coeffs.length - 1; i++)
            coeffs[i] = coefficients[i];
    }

    /* =degree of this Polynomial */
    public int getDegree() {
        int d = coeffs.length - 1;
        while ((coeffs[d] == 0) && (d > 0))
            d--;
        return d;
    }

    /* ={This Polynomial is the same as Polynomial  p}, true or false */
    public boolean equals(Polynomial p) {
        if (getDegree() != p.getDegree())
            return false;
        for (int i = 0; i <= getDegree(); i++)
            if (coeffs[i] != p.coeffs[i])
                return false;
        return true;
    }

    /* = The value of this polynomial evaluated at x */
    public double evaluate(double x) {
        double sum = 0;
        double input = 1;
        for (int k = 0; k < coeffs.length; k++) {
            sum += coeffs[k] * input;
            input *= x;
        }
        return sum;
    }

    /* Add this Polynomial to Polynomial p */
    public Polynomial add(Polynomial p) {
        int degree = getDegree();
        if (p.getDegree() > degree) {
            degree = p.getDegree();
        }
        double[] coefficients = new double[degree];
        for (int i = 0; i < getDegree(); i++)
            coefficients[i] += coeffs[i];
        for (int i = 0; i < p.getDegree(); i++)
            coefficients[i] += p.coeffs[i];
        return new Polynomial(coefficients);
    }

    /* =Polynomial that is the derivative of this Polynomial */
    public Polynomial derivative() {
        double[] coefficients = new double[getDegree()];
        for (int i = 0; i < getDegree(); i++) {
            coefficients[i] = (i + 1) * coeffs[i + 1];
        }
        return new Polynomial(coefficients);
    }

    @Override
    public String toString() {
        StringBuilder result = new StringBuilder();
        int degree = getDegree();
        NumberFormat nf = new DecimalFormat("##.######");
        for (int i = degree; i >= 0; i--) {
            if (coeffs[i] == 0.0) {
                continue;
            }
            if (i < degree && coeffs[i] > 0) {
                result.append('+');
            }
            result.append(nf.format(coeffs[i])).append((coeffs[i]==1.0?"x":"x^"+i));
        }
        return (result.length()==0?"0":result.toString());
    }
}

