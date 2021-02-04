package bg.reo101.ui;

import java.awt.*;

/**
 * A class
 */
public class UILabel extends UIObject {

    /**
     * The text, that's being written in the label.
     */
    private String value;

    /**
     * Standard constructor.
     *
     * @param x     X coordinate.
     * @param y     Y coordinate.
     * @param value The text of the label.
     */
    public UILabel(float x, float y, String value) {
        super(x, y);
        this.value = value;
    }

    /**
     * Method for ticking/updating changes on the page.
     */
    @Override
    public void tick() {

    }

    /**
     * Method for rendering everything on the page.
     *
     * @param g Graphics object passed everywhere throughout the program.
     */
    @Override
    public void render(Graphics g) {
        if (value == "" || value == null) {
            return;
        }
        g.drawString(value, (int) x, (int) y);
    }

    /**
     * The method for forcing a .onClick() event.
     */
    @Override
    public void onClick() {
    }

    /**
     * Getter for the text.
     *
     * @return Returns the text.
     */
    public String getValue() {
        return value;
    }

    /**
     * Setter for the text.
     *
     * @param value The new text.
     */
    public void setValue(String value) {
        this.value = value;
    }
}
