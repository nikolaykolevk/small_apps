package bg.reo101.ui;

import java.awt.*;
import java.awt.image.BufferedImage;

/**
 * A class storing data about one button with an image set to it.
 */
public class UIImageButton extends UIObject {

    /**
     * Array of images used for the button.
     */
    private BufferedImage[] images;
    /**
     * ClickListener object to contain the .onClick() logic.
     */
    private ClickListener clicker;
    /**
     * Booleans to check whether the button has a hovering and/or clicked state.
     */
    private boolean hasHovering, hasClicked;

    /**
     * General constructor.
     *
     * @param x           X coordinate.
     * @param y           Y coordinate.
     * @param width       Width of the button.
     * @param height      Height of the button.
     * @param images      The images array.
     * @param clicker     .onClick() logic.
     * @param hasHovering boolean for hasHovering state..
     * @param hasClicked  boolean for hasClicked state.
     */
    public UIImageButton(float x, float y, int width, int height, BufferedImage[] images, ClickListener clicker, boolean hasHovering, boolean hasClicked) {
        super(x, y, width, height);
        this.images = images;
        this.clicker = clicker;
        this.hasHovering = hasHovering;
        this.hasClicked = hasClicked;
    }

    /**
     * General constructor.
     *
     * @param x       X coordinate.
     * @param y       Y coordinate.
     * @param width   Width of the button.
     * @param height  Height of the button.
     * @param image   The image used for the button.
     * @param clicker .onClick() logic.
     */
    public UIImageButton(float x, float y, int width, int height, BufferedImage image, ClickListener clicker) {
        super(x, y, width, height);
        this.images = new BufferedImage[1];
        this.images[0] = image;
        this.clicker = clicker;
        this.hasHovering = false;
        this.hasClicked = false;
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
        if (hasHovering) {
            if (hovering) {
                g.drawImage(images[1], (int) x, (int) y, width, height, null);
            } else {
                g.drawImage(images[0], (int) x, (int) y, width, height, null);
            }
        } else if (hasClicked) {
//            System.out.println("From UIImageButton.java.render(): " + clicked);
            if (clicked) {
                g.drawImage(images[1], (int) x, (int) y, width, height, null);
            } else {
                g.drawImage(images[0], (int) x, (int) y, width, height, null);
            }
        } else {
            g.drawImage(images[0], (int) x, (int) y, width, height, null);
        }
    }

    /**
     * The method for forcing a .onClick() event.
     */
    @Override
    public void onClick() {
        if (hasClicked) {
            clicked = ! clicked;
        }
        clicker.onClick();
    }
}
