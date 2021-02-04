package bg.reo101.states;

import bg.reo101.Handler;
import bg.reo101.gfx.Assets;
import bg.reo101.ui.UIImageButton;
import bg.reo101.ui.UILabel;
import bg.reo101.ui.UIManager;
import bg.reo101.ui.UIObject;
import bsh.EvalError;
import bsh.Interpreter;

import java.awt.*;
import java.awt.event.KeyEvent;
import java.text.DecimalFormat;
import java.text.NumberFormat;
import java.util.ArrayList;

/**
 * Class used to store all the logic for the Normal page.
 */
public class NormalState extends State {

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
     * String for storing the StringBuilder data as a String.
     */
    private String expressionStr;
    /**
     * A boolean for checking whether the last inputted character is an operation one (+, -, *, /).
     */
    private boolean isLastAnOperation = false;
    /**
     * UILabel object used for showing the pre- and post-calculation data on the canvas.
     */
    private UILabel label = new UILabel(100, 550, "");

    /**
     * Main constructor
     *
     * @param handler
     */
    public NormalState(Handler handler) {
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
            this.appendOperation('+');
        }));

        buttons.add(new UIImageButton(500, 300, 64, 64, Assets.operations[1], () -> {
            this.appendOperation('-');
        }));

        buttons.add(new UIImageButton(400, 200, 64, 64, Assets.operations[2], () -> {
            this.appendOperation('*');
        }));

        buttons.add(new UIImageButton(500, 200, 64, 64, Assets.operations[3], () -> {
            this.appendOperation('/');
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
            expression = new StringBuilder();
            expressionStr = "";
            label.setValue("");
        }));

        buttons.add(new UIImageButton(400, 100, 64, 64, Assets.backspace, () -> {
            backspace();
        }));


        buttons.add(new UIImageButton(500, 400, 64, 64, Assets.operations[4], () -> {
            enter();
        }));

        for (UIObject o : buttons) {
            uiManager.addObject(o);
        }

        uiManager.addObject(label);

        uiManager.addObject(new UIImageButton(600, 600, 128, 64, Assets.exit, () -> {
            back();
        }, true, false));

    }

    /**
     * Method for appending various characters to the expression.
     *
     * @param ch Character needed to be appended.
     */
    private void append(char ch) {
//        System.out.println(ch);
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
     * Method for calculating and showing the result of the expression.
     */
    private void enter() {
        Interpreter interpreter = new Interpreter();
        try {
            Object res = interpreter.eval("(double) " + expression.toString());

            NumberFormat nf = new DecimalFormat("##.######");
            expressionStr = String.valueOf(nf.format(Double.valueOf(res.toString())));
            label.setValue(expressionStr);
            expression = new StringBuilder(expressionStr);
            isLastAnOperation = false;
//                expressionStr = "";
        } catch (EvalError evalError) {
            evalError.printStackTrace();
        }
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
            this.appendOperation('-');
        } else if (handler.getKeyManager().keyCurrentlyPressed(KeyEvent.VK_SHIFT) && handler.getKeyManager().keyJustPressed(KeyEvent.VK_EQUALS)) {
            this.appendOperation('+');
        } else if (handler.getKeyManager().keyCurrentlyPressed(KeyEvent.VK_SHIFT) && handler.getKeyManager().keyJustPressed(KeyEvent.VK_8)) {
            this.appendOperation('*');
        } else if (handler.getKeyManager().keyJustPressed(KeyEvent.VK_SLASH)) {
            this.appendOperation('/');
        } else if (handler.getKeyManager().keyCurrentlyPressed(KeyEvent.VK_SHIFT) && handler.getKeyManager().keyJustPressed(KeyEvent.VK_9)) {
            this.append('(');
        } else if (handler.getKeyManager().keyCurrentlyPressed(KeyEvent.VK_SHIFT) && handler.getKeyManager().keyJustPressed(KeyEvent.VK_0)) {
            this.append(')');
        } else if (handler.getKeyManager().keyJustPressed(KeyEvent.VK_0)) {
            this.append('0');
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
            backspace();
        } else if (handler.getKeyManager().keyJustPressed(KeyEvent.VK_ENTER)) {
            enter();
        } else if (handler.getKeyManager().keyJustPressed(KeyEvent.VK_PERIOD)) {
            this.append('.');
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
