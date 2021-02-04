package bg.reo101.states;

import bg.reo101.Handler;
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
import java.util.Collections;
import java.util.DoubleSummaryStatistics;
import java.util.List;
import java.util.Map;
import java.util.function.Function;
import java.util.stream.Collectors;

/**
 * Class used to store all the logic for the Functions page.
 */
public class StatisticsState extends State {

    /**
     * NumberFormat object used to format all numbers properly.
     */
    private final NumberFormat nf = new DecimalFormat("##.###");
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
     * a boolean for checking whether the current number is positive or not.
     */
    private boolean isPositive = true;
    /**
     * UILabel objects used for showing the pre- and post-calculation data on the canvas.
     */
    private UILabel label = new UILabel(100, 550, ""),
            labelOriginal = new UILabel(100, 600, ""),
            labelArranged = new UILabel(100, 650, ""),
            labelData = new UILabel(100, 700, "");
    /**
     * A double ArrayList containing all the inputted numbers sorted.
     */
    private ArrayList<Double> numsArranged = new ArrayList<>() {
        @Override
        public String toString() {
            StringBuilder res = new StringBuilder();
            for (Double i : this) {
                res.append(i).append(", ");
            }
            res.delete(res.length() - 2, res.length());
            return res.toString();
        }
    };
    /**
     * A double ArrayList containing all the inputted numbers unsorted.
     */
    private ArrayList<Double> nums = new ArrayList<>() {
        @Override
        public String toString() {
            StringBuilder res = new StringBuilder();
            for (Double i : this) {
                res.append(i).append(", ");
            }
            res.delete(res.length() - 2, res.length());
            return res.toString();
        }
    };

    /**
     * Main constructor.
     *
     * @param handler Handler object that is passed everywhere throughout the program.
     */
    public StatisticsState(Handler handler) {
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

        buttons.add(new UIImageButton(500, 300, 64, 64, Assets.operations[1], () -> {
            minus();
        }));

        buttons.add(new UIImageButton(200, 400, 64, 64, Assets.dot, () -> {
            this.append('.');
        }));

        buttons.add(new UIImageButton(500, 100, 64, 64, Assets.clear, () -> {
            expression = new StringBuilder();
            expressionStr = "";
            nums.clear();
            numsArranged.clear();
            label.setValue("");
            labelOriginal.setValue("");
            labelArranged.setValue("");
            labelData.setValue("");
        }));

        buttons.add(new UIImageButton(400, 100, 64, 64, Assets.backspace, () -> {
            backspace();
        }));


        buttons.add(new UIImageButton(500, 400, 128, 64, Assets.next, () -> {
            isPositive = true;
            if (expression.length() == 0) {
                return;
            }
            numsArranged.add(Double.parseDouble(expression.toString()));
            nums.add(Double.parseDouble(expression.toString()));
            expression.delete(0, expression.length());
            label.setValue("");
            labelOriginal.setValue(nums.toString());
        }));

        buttons.add(new UIImageButton(500, 500, 128, 64, Assets.arrange, () -> {
            enter();
        }));

        for (UIObject o : buttons) {
            uiManager.addObject(o);
        }


        uiManager.addObject(label);
        uiManager.addObject(labelData);
        uiManager.addObject(labelArranged);
        uiManager.addObject(labelOriginal);

        uiManager.addObject(new UIImageButton(600, 600, 128, 64, Assets.exit, () -> {
            expression = new StringBuilder();
            expressionStr = "";
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
    }

    /**
     * Method used for setting the handlers'/global uiManager to the local one.
     */
    public void setSpecificUiManager() {
//        System.out.println("in normal state rn");
        handler.getMouseManager().setUiManager(uiManager);
    }

    /**
     * Method for returning back to the Menu state.
     */
    private void back() {
        handler.getMouseManager().setUiManager(null);
        handler.getGame().menuState.setSpecificUiManager();
        State.setState(handler.getGame().menuState);
    }

    /**
     * Method for initiating the finding of roots ot the expression.
     */
    private void enter() {
        if (expression.length() > 0) {
            numsArranged.add(Double.parseDouble(expression.toString()));
            nums.add(Double.parseDouble(expression.toString()));
        }
        expression.delete(0, expression.length());
        expressionStr = "";
        Collections.sort(numsArranged);
        labelArranged.setValue(String.valueOf(format(numsArranged)));
        labelOriginal.setValue(String.valueOf(format(nums)));
        isPositive = true;
        calcResults();
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
     * Method for changing the positivity of the current number.
     */
    private void minus() {
        if (isPositive) {
            expression.insert(0, "-");
            label.setValue(expression.toString());
        } else {
            expression.deleteCharAt(0);
            label.setValue(expression.toString());
        }
        isPositive = ! isPositive;
    }

    /**
     * Method for calculating the various facts about the statistic row and showing the results on the canvas.
     */
    private void calcResults() {
        double median, average;
        List<Double> fashion = new ArrayList<>() {
            @Override
            public String toString() {
                StringBuilder sb = new StringBuilder();
                for (Double d : this) {
                    sb.append(d);
                }
                return sb.toString();
            }
        };

        DoubleSummaryStatistics stats = numsArranged
                .stream()
                .collect(Collectors.summarizingDouble(num -> num));

        median = (numsArranged.size() % 2 != 0 ? numsArranged.get(numsArranged.size() / 2) : (numsArranged.get(numsArranged.size() / 2 - 1) + numsArranged.get(numsArranged.size() / 2)) / 2);

        average = stats.getAverage();

        fashion = numsArranged.stream().collect(Collectors.groupingBy(Function.identity(), Collectors.counting()))
                .entrySet().stream()
                .collect(Collectors.groupingBy(Map.Entry::getValue, Collectors.mapping(Map.Entry::getKey, Collectors.toList())))
                .entrySet().stream().max((o1, o2) -> o1.getKey().compareTo(o2.getKey())).map(Map.Entry::getValue)
                .orElse(Collections.emptyList());

        labelData.setValue("median: " + format(median) +
                " average: " + format(average) +
                " fashion: " + format(fashion));

    }

    /**
     * A method for automatic formatting of a number.
     *
     * @param d Number to be formatted.
     * @return Returns the formatted number.
     */
    private String format(double d) {
        return String.valueOf(nf.format(d));
    }

    /**
     * A method for automatic formatting of a array of numbers.
     *
     * @param d Array of numbers to be formatted.
     * @return String containing all numbers formatted and spaced out.
     */
    private String format(List<Double> d) {
        StringBuilder sb = new StringBuilder();
        for (Double num : d) {
            sb.append(nf.format(num)).append(", ");
        }
        sb.delete(sb.length() - 2, sb.length());
        return sb.toString();
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
            minus();
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
