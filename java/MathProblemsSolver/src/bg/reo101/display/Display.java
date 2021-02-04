package bg.reo101.display;

import javax.swing.*;
import java.awt.*;

/**
 * Display class used to make and maintain a physical window.
 */
public class Display {

    /**
     * A JFrame object for the window.
     */
    private JFrame frame;
    /**
     * A Canvas object for the JFrame.
     */
    private Canvas canvas;

    /**
     * A String to store the window's name.
     */
    private String title;

    /**
     * Two ints to store the physical dimensions of the window.
     */
    private int width, height;

    /**
     * Constructor for Display class.
     *
     * @param title  Title of the window.
     * @param width  Width of thw window.
     * @param height Height of the window.
     */
    public Display(String title, int width, int height) {
        this.title = title;
        this.width = width;
        this.height = height;

        createDisplay();
    }

    /**
     * Does the initial setups for a new window.
     */
    private void createDisplay() {
        frame = new JFrame(title);
        frame.setSize(width, height);
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frame.setResizable(false);
        frame.setLocationRelativeTo(null);
        frame.setVisible(true);

        canvas = new Canvas();
        canvas.setPreferredSize(new Dimension(width, height));
        canvas.setMaximumSize(new Dimension(width, height));
        canvas.setMinimumSize(new Dimension(width, height));
        canvas.setFocusable(false);

        frame.add(canvas);
        frame.pack();
    }

    /**
     * Canvas getter.
     *
     * @return Returns the canvas.
     */
    public Canvas getCanvas() {
        return canvas;
    }

    /**
     * Frame getter.
     *
     * @return Returns the frame.
     */
    public JFrame getFrame() {
        return frame;
    }

}
