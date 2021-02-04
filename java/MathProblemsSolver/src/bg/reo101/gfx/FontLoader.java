package bg.reo101.gfx;

import java.awt.*;
import java.io.File;
import java.io.IOException;

/**
 * Class used to load Fonts.
 */
public abstract class FontLoader {

    /**
     * Main method used for loading fonts into Font objects.
     * @param path Path to the ttf file.
     * @param size Size of the font.
     * @return Either returns the Font object or null if failed loading.
     */
    public static Font loadFont(String path, float size) {
        try {
            return Font.createFont(Font.TRUETYPE_FONT, new File(path)).deriveFont(Font.PLAIN, size);
        } catch (FontFormatException | IOException e) {
            e.printStackTrace();
            System.exit(1);
        }
        return null;
    }
}
