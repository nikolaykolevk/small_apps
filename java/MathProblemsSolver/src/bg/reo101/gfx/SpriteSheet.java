package bg.reo101.gfx;

import java.awt.image.BufferedImage;

/**
 * Class used for containing and cropping spritesheets.
 */
public class SpriteSheet {

    /**
     * BufferedImage containing the spritesheet.
     */
    private BufferedImage sheet;

    /**
     * public constructor for the class.
     * @param sheet The initial image that's going to be used as a spritesheet.
     */
    public SpriteSheet(BufferedImage sheet) {
        this.sheet = sheet;
    }

    /**
     * Method for cropping out a piece of the whole spritesheet.
     * @param x X position of the upper-left corner of the crop.
     * @param y Y position of the upper-left corner of the crop.
     * @param width Width of the crop.
     * @param height Height of the crop.
     * @return Returns a cropped image with the desired dimensions.
     */
    public BufferedImage crop(int x, int y, int width, int height) {
        return sheet.getSubimage(x, y, width, height);
    }

}
