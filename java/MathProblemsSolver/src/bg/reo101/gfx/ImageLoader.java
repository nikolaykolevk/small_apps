package bg.reo101.gfx;

import javax.imageio.ImageIO;
import java.awt.image.BufferedImage;
import java.io.IOException;

/**
 * Class used for loading images.
 */
public class ImageLoader {

    /**
     * Main method used for loading images into BufferedImage objects.
     * @param path Path to the image file.
     * @return Either returns the BufferedImage object or null if failed loading.
     */
    public static BufferedImage loadImage(String path){
        try {
            return ImageIO.read(ImageLoader.class.getResource(path));
        } catch (IOException e) {
            e.printStackTrace();
            System.exit(1);
        }
        return null;
    }

}
