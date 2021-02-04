package bg.reo101.input;

import java.awt.event.KeyEvent;
import java.awt.event.KeyListener;

/**
 * Class used to manage keystrokes.
 */
public class KeyManager implements KeyListener {

    /**
     * Booleans used for fast checking whether any WASD key is pressed or not.
     */
    public boolean up, down, left, right;
    /**
     * Booleans used for fast checking whether any arrow key is pressed or not.
     */
    public boolean aUp, aDown, aLeft, aRight;
    /**
     * Boolean arrays for storing the pressed or not, the justPressed and cantPress state of all keys.
     */
    private boolean[] keys, justPressed, cantPress;

    /**
     * Public constructor that initializes the arrays.
     */
    public KeyManager() {
        keys = new boolean[256];
        justPressed = new boolean[keys.length];
        cantPress = new boolean[keys.length];

    }

    /**
     * Method for ticking/updating the state of all keys.
     */
    public void tick() {
        for (int i = 0; i < keys.length; i++) {
            if (cantPress[i] && ! keys[i]) {
                cantPress[i] = false;
            } else if (justPressed[i]) {
                cantPress[i] = true;
                justPressed[i] = false;
            }
            if (! cantPress[i] && keys[i]) {
                justPressed[i] = true;
            }
        }

        up = keys[KeyEvent.VK_W];
        down = keys[KeyEvent.VK_S];
        left = keys[KeyEvent.VK_A];
        right = keys[KeyEvent.VK_D];

        aUp = keys[KeyEvent.VK_UP];
        aDown = keys[KeyEvent.VK_DOWN];
        aLeft = keys[KeyEvent.VK_LEFT];
        aRight = keys[KeyEvent.VK_RIGHT];
    }

    /**
     * Method for checking if a key has just been pressed.
     *
     * @param keyCode KeyCode of the checked key.
     * @return True or False depending on the state of the key.
     */
    public boolean keyJustPressed(int keyCode) {

        if (keyCode < 0 || keyCode >= keys.length) {
            return false;
        }

        return justPressed[keyCode];
    }

    /**
     * Method for checking if a key is currently pressed down.
     *
     * @param keyCode KeyCode of the checked key.
     * @return True or False depending ont he state of the key.
     */
    public boolean keyCurrentlyPressed(int keyCode) {
        return keys[keyCode];
    }

    /**
     * Overridden method keyPressed that forcibly says that a key is pressed.
     *
     * @param e KeyEvent object of the key.
     */
    @Override
    public void keyPressed(KeyEvent e) {
        if (e.getKeyCode() < 0 || e.getKeyCode() >= keys.length) {
            return;
        }
        keys[e.getKeyCode()] = true;
    }

    /**
     * Overridden method keyReleased that forcibly says that a key is released.
     *
     * @param e KeyEvent object of the key.
     */
    @Override
    public void keyReleased(KeyEvent e) {
        keys[e.getKeyCode()] = false;
    }

    /**
     * Overridden method keyPressed that currently does nothing.
     *
     * @param e KeyEvent object of the key.
     */
    @Override
    public void keyTyped(KeyEvent e) {

    }

}
