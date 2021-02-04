package bg.reo101.states;

import bg.reo101.Handler;
import bg.reo101.Utilities.Polynomial;
import bg.reo101.Utilities.PolynomialRoots;
import bg.reo101.gfx.Assets;
import bg.reo101.ui.UIImageButton;
import bg.reo101.ui.UILabel;
import bg.reo101.ui.UIManager;
import bg.reo101.ui.UIObject;

import java.awt.*;
import java.awt.event.KeyEvent;
import java.text.DecimalFormat;
import java.text.NumberFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;
import java.util.Comparator;
import java.util.List;

/**
 * Class used to store all the logic for the Functions page.
 */
public class FunctionsState extends State {

    /**
     * Local UIManager object.
     */
    private UIManager uiManager;
    /**
     * Local array for storing all buttons logic before adding them to the uiManager.
     */
    private ArrayList<UIImageButton> buttons;
    /**
     * StringBuilder object to dynamically store the current expression.
     */
    private StringBuilder expression;
    /**
     * Polynomial object to store the current as a polynomial for easy interaction later on.
     */
    private Polynomial polynomial;
    /**
     * String for storing the StringBuilder data as a String.
     */
    private String expressionStr;
    /**
     * A boolean for checking whether the user is currently writing digits in a power.
     */
    private boolean isAPower = false;
    /**
     * A boolean for checking whether the last inputted character is an operation one (+, -, *, /).
     */
    private boolean isLastAnOperation = false;
    /**
     * UILabel objects used for showing the pre- and post-calculation data on the canvas.
     */
    private UILabel label = new UILabel(100, 600, ""),
            labelDerivative = new UILabel(100, 650, ""),
            labelRoots = new UILabel(100, 700, "");

    /**
     * Main constructor.
     *
     * @param handler Handler object that is passed everywhere throughout the program.
     */
    public FunctionsState(Handler handler) {
        super(handler);

        uiManager = new UIManager(handler);
        expression = new StringBuilder();

        buttons = new ArrayList<>();

        buttons.add(new UIImageButton(100, 400, 64, 64, Assets.numbers[0], () -> {
            this.append('0');
        }));

        buttons.add(new UIImageButton(100, 300, 64, 64, Assets.numbers[1], () -> {
            this.append('1');
        }));

        buttons.add(new UIImageButton(200, 300, 64, 64, Assets.numbers[2], () -> {
            this.append('2');
        }));

        buttons.add(new UIImageButton(300, 300, 64, 64, Assets.numbers[3], () -> {
            this.append('3');
        }));

        buttons.add(new UIImageButton(100, 200, 64, 64, Assets.numbers[4], () -> {
            this.append('4');
        }));

        buttons.add(new UIImageButton(200, 200, 64, 64, Assets.numbers[5], () -> {
            this.append('5');
        }));

        buttons.add(new UIImageButton(300, 200, 64, 64, Assets.numbers[6], () -> {
            this.append('6');
        }));

        buttons.add(new UIImageButton(100, 100, 64, 64, Assets.numbers[7], () -> {
            this.append('7');
        }));

        buttons.add(new UIImageButton(200, 100, 64, 64, Assets.numbers[8], () -> {
            this.append('8');
        }));

        buttons.add(new UIImageButton(300, 100, 64, 64, Assets.numbers[9], () -> {
            this.append('9');
        }));

        buttons.add(new UIImageButton(400, 300, 64, 64, Assets.operations[0], () -> {
            normalize();
            this.appendOperation('+');
            isAPower = false;
        }));

        buttons.add(new UIImageButton(500, 300, 64, 64, Assets.operations[1], () -> {
            normalize();
            this.appendOperation('-');
            isAPower = false;
        }));

        buttons.add(new UIImageButton(300, 400, 64, 64, Assets.brackets[0], () -> {
            this.append('(');
        }));

        buttons.add(new UIImageButton(400, 400, 64, 64, Assets.brackets[1], () -> {
            this.append(')');
        }));

        buttons.add(new UIImageButton(200, 400, 64, 64, Assets.dot, () -> {
            this.append('.');
        }));

        buttons.add(new UIImageButton(500, 100, 64, 64, Assets.clear, () -> {
            normalize();
            expression = new StringBuilder();
            expressionStr = "";
            label.setValue("");
            isAPower = false;
        }));

        buttons.add(new UIImageButton(400, 100, 64, 64, Assets.backspace, () -> {
            if (expression.charAt(expression.length() - 1) == '^') isAPower = false;
            backspace();
        }));

        buttons.add(new UIImageButton(500, 400, 64, 64, Assets.unknown, () -> {
            this.append('x');
        }));

        buttons.add(new UIImageButton(500, 500, 64, 64, Assets.power, () -> {
            isAPower = true;
            this.append('^');
        }));

        buttons.add(new UIImageButton(400, 500, 64, 64, Assets.findDerivative, () -> {
            isAPower = false;
            normalize();
            findDerivative();
        }));

        buttons.add(new UIImageButton(300, 500, 64, 64, Assets.findRoots, () -> {
            isAPower = false;
            normalize();
            findRoots();
        }));


        for (UIObject o : buttons) {
            uiManager.addObject(o);
        }

        uiManager.addObject(label);
        uiManager.addObject(labelRoots);
        uiManager.addObject(labelDerivative);

        uiManager.addObject(new UIImageButton(600, 600, 128, 64, Assets.exit, () -> {
            back();
        }, true, false));

    }

    /**
     * Method for checking whether an Integer list contains a certain value;
     *
     * @param array Array for checking.
     * @param v     Value for checking.
     * @return Index of value in the array or -1 if the value is not found.
     */
    private int contains(final List<Integer> array, final int v) {

        for (int i = 0; i < array.size(); i++) {
            if (array.get(i) == v) {
                return i;
            }
        }

        return - 1;
    }

    /**
     * Method for appending various characters to the expression.
     *
     * @param ch Character needed to be appended.
     */
    private void append(char ch) {
        expression.append(ch);
        label.setValue(expression.toString());
        isLastAnOperation = false;
    }

    /**
     * Method for deleting the last character from the expression.
     */
    private void backspace() {
        if (expression.length() == 0) {
            return;
        }
        expression.deleteCharAt(expression.length() - 1);
        label.setValue(expression.toString());
    }

    /**
     * Method for initiating the finding of roots ot the expression.
     */
    private void enter() {
        normalize();
        findRoots();
    }

    /**
     * Method for returning back to the Menu state.
     */
    private void back() {
        expression = new StringBuilder();
        expressionStr = "";
        handler.getMouseManager().setUiManager(null);
        handler.getGame().menuState.setSpecificUiManager();
        State.setState(handler.getGame().menuState);
    }

    /**
     * Method for preparing the expression for parsing and solving.
     */
    private void normalize() {
        if (expression.charAt(expression.length() - 1) == 'x') {
            expression.append("^1");
            isAPower = true;
        } else if (! isAPower && Character.isDigit(expression.charAt(expression.length() - 1))) {
            expression.append("x^0");
            isAPower = true;
        }
        expressionStr = expression.toString();
        label.setValue(expressionStr);
    }

    /**
     * Method for finding and displaying the roots (if any) of the current expression.
     */
    private void findRoots() {
        if (expression.length() == 0) {
            return;
        }

        String[] numbers = expression.toString().replace("^", "").split("((?=\\+)|(?=\\-)|x)");

        System.out.println(Arrays.toString(numbers));

        List<Integer> coeff = new ArrayList<>();
        List<Integer> expo = new ArrayList<>();

        for (int i = 0; i < numbers.length; i += 2) {
            if (numbers[i].equals("-") || numbers[i].equals("+")) {
                numbers[i] += "1";
            }
            System.out.println(numbers[i] + " " + i);
            coeff.add(Integer.parseInt(numbers[i]));
            expo.add(Integer.parseInt(numbers[i + 1]));
        }

        for (int i = 0; i < expo.size() - 1; i++) {
            for (int j = i + 1; j < expo.size(); j++) {
                if (expo.get(i) == expo.get(j)) {
                    expo.remove(j);
                    coeff.set(i, coeff.get(i) + coeff.get(j));
                    coeff.remove(j);
                }
            }
        }
        System.out.println(coeff);
        System.out.println(expo);

        List<Double> coefficients = new ArrayList<>();
        int length = coeff.size();


        for (int i = 0; i < length; i++) {
            coefficients.add((contains(expo, i) == - 1 ? 0 : Double.valueOf(coeff.get(contains(expo, i)))));
        }

        Collections.reverse(coefficients);

        NumberFormat nf = new DecimalFormat("##.###");
        List<Double> roots = new ArrayList<>();
        for (double root : PolynomialRoots.getRoots(coefficients)) {
            roots.add(Double.valueOf(nf.format(root)));

        }
        labelRoots.setValue(String.valueOf(roots));
    }

    /**
     * Method for finding the derivative of the current expression.
     */
    private void findDerivative() {
        if (expression.length() == 0) {
            return;
        }
        String[] numbers = expression.toString().replace("^", "").split("((?=\\+)|(?=\\-)|x)");

        List<Integer> coeff = new ArrayList<>();
        List<Integer> expo = new ArrayList<>();

        for (int i = 0; i < numbers.length; i += 2) {
            coeff.add(Integer.parseInt(numbers[i]));
            expo.add(Integer.parseInt(numbers[i + 1]));
        }

        double[] coefficients = new double[expo.stream().max(Comparator.comparing(i -> i)).get() + 1];
        int length = coefficients.length;

        for (int i = 0; i < length; i++) {
            coefficients[i] = (contains(expo, i) == - 1 ? 0 : coeff.get(contains(expo, i)));
        }


        polynomial = new Polynomial(coefficients);

        expressionStr = expression.toString();
        expression = new StringBuilder(polynomial.derivative().toString());
        labelDerivative.setValue(expression.toString());
    }

    /**
     * Method for appending a certain operation sign in the expression.
     *
     * @param ch Character needed to be appended.
     */
    private void appendOperation(char ch) {
        if (isLastAnOperation) {
            expression.deleteCharAt(expression.length() - 1);
        } else {
            isLastAnOperation = true;
        }
        append(ch);
    }

    /**
     * Method used for setting the handlers'/global uiManager to the local one.
     */
    public void setSpecificUiManager() {
//        System.out.println("in normal state rn");
        handler.getMouseManager().setUiManager(uiManager);
    }

    /**
     * Method for ticking/updating changes on the page.
     */
    @Override
    public void tick() {
        uiManager.tick();
        if (handler.getKeyManager().keyJustPressed(KeyEvent.VK_ESCAPE)) {
            back();
        } else if (handler.getKeyManager().keyJustPressed(KeyEvent.VK_MINUS)) {
            normalize();
            this.appendOperation('-');
            isAPower = false;
        } else if (handler.getKeyManager().keyCurrentlyPressed(KeyEvent.VK_SHIFT) && handler.getKeyManager().keyJustPressed(KeyEvent.VK_EQUALS)) {
            normalize();
            this.appendOperation('+');
            isAPower = false;
        } else if (handler.getKeyManager().keyCurrentlyPressed(KeyEvent.VK_SHIFT) && handler.getKeyManager().keyJustPressed(KeyEvent.VK_9)) {
            this.append('(');
        } else if (handler.getKeyManager().keyCurrentlyPressed(KeyEvent.VK_SHIFT) && handler.getKeyManager().keyJustPressed(KeyEvent.VK_0)) {
            this.append(')');
        } else if (handler.getKeyManager().keyJustPressed(KeyEvent.VK_0)) {
            this.append('0');
        } else if (handler.getKeyManager().keyCurrentlyPressed(KeyEvent.VK_SHIFT) && handler.getKeyManager().keyJustPressed(KeyEvent.VK_6)) {
            this.append('^');
            isAPower = true;
        } else if (handler.getKeyManager().keyJustPressed(KeyEvent.VK_1)) {
            this.append('1');
        } else if (handler.getKeyManager().keyJustPressed(KeyEvent.VK_2)) {
            this.append('2');
        } else if (handler.getKeyManager().keyJustPressed(KeyEvent.VK_3)) {
            this.append('3');
        } else if (handler.getKeyManager().keyJustPressed(KeyEvent.VK_4)) {
            this.append('4');
        } else if (handler.getKeyManager().keyJustPressed(KeyEvent.VK_5)) {
            this.append('5');
        } else if (handler.getKeyManager().keyJustPressed(KeyEvent.VK_6)) {
            this.append('6');
        } else if (handler.getKeyManager().keyJustPressed(KeyEvent.VK_7)) {
            this.append('7');
        } else if (handler.getKeyManager().keyJustPressed(KeyEvent.VK_8)) {
            this.append('8');
        } else if (handler.getKeyManager().keyJustPressed(KeyEvent.VK_9)) {
            this.append('9');
        } else if (handler.getKeyManager().keyJustPressed(KeyEvent.VK_BACK_SPACE)) {
            if (expression.charAt(expression.length() - 1) == '^') isAPower = false;
            backspace();
        } else if (handler.getKeyManager().keyJustPressed(KeyEvent.VK_ENTER)) {
            enter();
        } else if (handler.getKeyManager().keyJustPressed(KeyEvent.VK_PERIOD)) {
            this.append('.');
        } else if (handler.getKeyManager().keyJustPressed(KeyEvent.VK_X)) {
            this.append('x');
        }
    }

    /**
     * Method for rendering everything on the page.
     *
     * @param g Graphics object passed everywhere throughout the program.
     */
    @Override
    public void render(Graphics g) {
        uiManager.render(g);
    }
}
