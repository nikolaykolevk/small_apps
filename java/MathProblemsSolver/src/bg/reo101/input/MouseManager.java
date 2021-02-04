package bg.reo101.input;

import bg.reo101.ui.UIManager;

import java.awt.*;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;
import java.awt.event.MouseMotionListener;

/**
 * Class used for managing mouse interactions.
 */
public class MouseManager implements MouseListener, MouseMotionListener {

    /**
     * Booleans used ofr fast checking whether any of the main two buttons of the mouse are pressed or not.
     */
    private boolean leftPressed, rightPressed;
    /**
     * Integers for storing the current mouse X and Y coordinates relevant to the canvas.
     */
    private int mouseX, mouseY;
    /**
     * Local UIManager object.
     */
    private UIManager uiManager;

    /**
     * Public constructor
     */
    public MouseManager() {

    }

    /**
     * UIManager setter
     * @param uiManager UIManager object that's going to be used as the local one.
     */
    public void setUiManager(UIManager uiManager) {
        this.uiManager = uiManager;
    }

    //Getters

    /**
     * UIManager getter.
     * @return Local UIManager.
     */
    public UIManager getUiManager() {
        return uiManager;
    }

    /**
     * leftPressedGetter.
     * @return leftPressed.
     */
    public boolean isLeftPressed() {
        return leftPressed;
    }

    /**
     * rightPressedGetter/
     * @return rightPressed.
     */
    public boolean isRightPressed() {
        return rightPressed;
    }

    /**
     * mouseX getter.
     * @return mouseX.
     */
    public int getMouseX() {
        return mouseX;
    }

    /**
     * mouseY getter.
     * @return mouseY.
     */
    public int getMouseY() {
        return mouseY;
    }

    /**
     * mousePoint getter.
     * @return mouseX and mouseY combined into a Point object.
     */
    public Point getMousePoint() {
        return new Point(mouseX, mouseY);
    }

    //Implemented methods

    @Override
    public void mouseClicked(MouseEvent e) {

    }

    @Override
    public void mousePressed(MouseEvent e) {
        if (e.getButton() == MouseEvent.BUTTON1) {
            leftPressed = true;
        } else if (e.getButton() == MouseEvent.BUTTON3) {
            rightPressed = true;
        }
    }

    @Override
    public void mouseReleased(MouseEvent e) {
        if (e.getButton() == MouseEvent.BUTTON1) {
            leftPressed = false;
        } else if (e.getButton() == MouseEvent.BUTTON3) {
            rightPressed = false;
        }

        if (uiManager != null) {
            uiManager.onMouseRelease(e);
        }
    }

    @Override
    public void mouseEntered(MouseEvent e) {
    }

    @Override
    public void mouseExited(MouseEvent e) {
    }

    @Override
    public void mouseDragged(MouseEvent e) {
    }

    @Override
    public void mouseMoved(MouseEvent e) {
        mouseX = e.getX();
        mouseY = e.getY();

        if (uiManager != null) {
            uiManager.onMouseMove(e);
        }
    }
}
