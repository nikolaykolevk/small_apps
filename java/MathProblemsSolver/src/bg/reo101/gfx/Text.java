package bg.reo101.gfx;

import java.awt.*;

/**
 * Class used for drawing strings onto the canvas.
 */
public abstract class Text {

    /**
     * Method used for drawing a String onto the canvas.
     * @param g Graphics object that is passed everywhere throughout the program.
     * @param text The text that needs to be shown.
     * @param xPos X coordinate.
     * @param yPos Y coordinate.
     * @param center Whether the text should be drawn with its center on the given coordinates.
     * @param c Color of the text.
     * @param font Font of the text.
     */
    public static void drawString(Graphics g, String text, int xPos, int yPos, boolean center, Color c, Font font) {
        g.setColor(c);
        g.setFont(font);
        int x = xPos;
        int y = yPos;
        if (center) {
            FontMetrics fm = g.getFontMetrics(font);
            x = xPos - fm.stringWidth(text) / 2;
            y = (yPos - fm.getHeight() / 2) + fm.getAscent();
        }
        g.drawString(text, x, y);
    }

}
