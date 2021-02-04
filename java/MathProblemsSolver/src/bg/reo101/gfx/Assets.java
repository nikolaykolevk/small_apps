package bg.reo101.gfx;

import java.awt.*;
import java.awt.image.BufferedImage;

/**
 * Assets class used for storing all assets(images and fonts) as static objects.
 */
public abstract class Assets {

    /**
     * Constants used for cropping from a spritesheet.
     */
    private static final int WIDTH = 16, HEIGHT = 16, OFFSET = 1;
    /**
     * Font objects used for writing with various fonts throughout the program.
     */
    public static Font font28, font18, font18b, font50;
    /**
     * BufferedImage array to store all digits' images.
     */
    public static BufferedImage[] numbers;
    /**
     * BufferedImage array to store all arithmetical operations' images (+, -, *, /, =).
     */
    public static BufferedImage[] operations;
    /**
     * BufferedImage array tp store all brackets'images ('(', ')').
     */
    public static BufferedImage[] brackets;
    /**
     * BufferedImage for Dot button.
     */
    public static BufferedImage dot;
    /**
     * BufferedImage for Clear button.
     */
    public static BufferedImage clear;
    /**
     * BufferedImage for Backspace button.
     */
    public static BufferedImage backspace;
    /**
     * BufferedImage for Next button.
     */
    public static BufferedImage next;
    /**
     * BufferedImage for Arrange button.
     */
    public static BufferedImage arrange;
    /**
     * BufferedImage for Unknown ('x') button.
     */
    public static BufferedImage unknown;
    /**
     * BufferedImage for Power ('^') button.
     */
    public static BufferedImage power;
    /**
     * BufferedImage for FindRoots button.
     */
    public static BufferedImage findRoots;
    /**
     * BufferedImage for FindDerivative button.
     */
    public static BufferedImage findDerivative;
    /**
     * BufferedImage arrays for storing the various states' images.
     */
    public static BufferedImage[] normal, functions, graphs, progressions, statistics;
    /**
     * BufferedImage array for storing the Exit button images.
     */
    public static BufferedImage[] exit;

    /**
     * Method for calculating the exact pixel width based upon the WIDTH and OFFSET constants.
     * @param value The numbers of "squares" needed to be skipped.
     * @return Returns the exact pixel position by width.
     */
    private static int getWidthPos(int value) {
        return value * (WIDTH + OFFSET);
    }

    /**
     * Method for calculating the exact pixel height based upon the HEIGHT and OFFSET constants.
     * @param value The numbers of "squares" needed to be skipped.
     * @return Returns the exact pixel position by height.
     */
    private static int getHeightPos(int value) {
        return value * (HEIGHT + OFFSET);
    }

    /**
     * Init method to initialize values to all BufferedImage objects.
     */
    public static void init() {

        font28 = FontLoader.loadFont("res/fonts/slkscr.ttf", 28);
        font18 = FontLoader.loadFont("res/fonts/museosans.otf", 18);
        font18b = FontLoader.loadFont("res/fonts/museosans5.otf", 18);
        font50 = FontLoader.loadFont("res/fonts/museosans5.otf", 50);

        SpriteSheet sheet = new SpriteSheet(ImageLoader.loadImage("/textures/sheet.png"));

        try {
            normal = new BufferedImage[2];
            functions = new BufferedImage[2];
            graphs = new BufferedImage[2];
            progressions = new BufferedImage[2];
            statistics = new BufferedImage[2];

            normal[0] = sheet.crop(getWidthPos(0), getHeightPos(0), WIDTH, HEIGHT);
            normal[1] = sheet.crop(getWidthPos(0), getHeightPos(0), WIDTH, HEIGHT);
            functions[0] = sheet.crop(getWidthPos(1), getHeightPos(0), WIDTH, HEIGHT);
            functions[1] = sheet.crop(getWidthPos(1), getHeightPos(0), WIDTH, HEIGHT);
            graphs[0] = sheet.crop(getWidthPos(2), getHeightPos(0), WIDTH, HEIGHT);
            graphs[1] = sheet.crop(getWidthPos(2), getHeightPos(0), WIDTH, HEIGHT);
            progressions[0] = sheet.crop(getWidthPos(3), getHeightPos(0), WIDTH, HEIGHT);
            progressions[1] = sheet.crop(getWidthPos(3), getHeightPos(0), WIDTH, HEIGHT);
            statistics[0] = sheet.crop(getWidthPos(4), getHeightPos(0), WIDTH, HEIGHT);
            statistics[1] = sheet.crop(getWidthPos(4), getHeightPos(0), WIDTH, HEIGHT);

            exit = new BufferedImage[2];

            exit[0] = sheet.crop(getWidthPos(0), getHeightPos(2), WIDTH * 2 + OFFSET, HEIGHT);
            exit[1] = sheet.crop(getWidthPos(0), getHeightPos(2), WIDTH * 2 + OFFSET, HEIGHT);

            numbers = new BufferedImage[10];

            numbers[1] = sheet.crop(getWidthPos(0), getHeightPos(3), WIDTH, HEIGHT);
            numbers[2] = sheet.crop(getWidthPos(1), getHeightPos(3), WIDTH, HEIGHT);
            numbers[3] = sheet.crop(getWidthPos(2), getHeightPos(3), WIDTH, HEIGHT);
            numbers[4] = sheet.crop(getWidthPos(3), getHeightPos(3), WIDTH, HEIGHT);
            numbers[5] = sheet.crop(getWidthPos(4), getHeightPos(3), WIDTH, HEIGHT);
            numbers[6] = sheet.crop(getWidthPos(5), getHeightPos(3), WIDTH, HEIGHT);
            numbers[7] = sheet.crop(getWidthPos(6), getHeightPos(3), WIDTH, HEIGHT);
            numbers[8] = sheet.crop(getWidthPos(7), getHeightPos(3), WIDTH, HEIGHT);
            numbers[9] = sheet.crop(getWidthPos(8), getHeightPos(3), WIDTH, HEIGHT);
            numbers[0] = sheet.crop(getWidthPos(9), getHeightPos(3), WIDTH, HEIGHT);

            operations = new BufferedImage[5];

            operations[0] = sheet.crop(getWidthPos(2), getHeightPos(2), WIDTH, HEIGHT);
            operations[1] = sheet.crop(getWidthPos(3), getHeightPos(2), WIDTH, HEIGHT);
            operations[2] = sheet.crop(getWidthPos(4), getHeightPos(2), WIDTH, HEIGHT);
            operations[3] = sheet.crop(getWidthPos(5), getHeightPos(2), WIDTH, HEIGHT);
            operations[4] = sheet.crop(getWidthPos(6), getHeightPos(2), WIDTH, HEIGHT);

            brackets = new BufferedImage[2];

            brackets[0] = sheet.crop(getWidthPos(7), getHeightPos(2), WIDTH, HEIGHT);
            brackets[1] = sheet.crop(getWidthPos(8), getHeightPos(2), WIDTH, HEIGHT);

            dot = sheet.crop(getWidthPos(9), getHeightPos(2), WIDTH, HEIGHT);

            clear = sheet.crop(getWidthPos(0), getHeightPos(1), WIDTH, HEIGHT);
            backspace = sheet.crop(getWidthPos(1), getHeightPos(1), WIDTH, HEIGHT);

            next = sheet.crop(getWidthPos(2), getHeightPos(1), WIDTH * 2 + OFFSET, HEIGHT);

            arrange = sheet.crop(getWidthPos(4), getHeightPos(1), WIDTH * 3 + OFFSET * 2, HEIGHT);

            unknown = sheet.crop(getWidthPos(7), getHeightPos(1), WIDTH, HEIGHT);

            power = sheet.crop(getWidthPos(8), getHeightPos(1), WIDTH, HEIGHT);

            findRoots = sheet.crop(getWidthPos(0), getHeightPos(4), WIDTH * 3 + OFFSET * 2, HEIGHT * 2 + OFFSET);

            findDerivative = sheet.crop(getWidthPos(3), getHeightPos(4), WIDTH * 4 + OFFSET * 3, HEIGHT * 2 + OFFSET);

        } catch (NullPointerException e) {
            e.printStackTrace();
        }
    }

}
