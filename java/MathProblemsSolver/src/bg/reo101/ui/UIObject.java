package bg.reo101.ui;

import java.awt.*;
import java.awt.event.MouseEvent;

public abstract class UIObject {

    protected float x, y;
    protected int width, height;
    protected Rectangle bounds;
    protected boolean hovering = false;
    protected boolean clicked = false;

    public UIObject(float x, float y, int width, int height) {
        this.x = x;
        this.y = y;
        this.width = width;
        this.height = height;
        bounds = new Rectangle((int) x, (int) y, width, height);
    }

    public UIObject(float x, float y) {
        this.x = x;
        this.y = y;
        this.width = 0;
        this.height = 0;
        bounds = new Rectangle((int) x, (int) y, width, height);
    }

    public abstract void tick();

    public abstract void render(Graphics g);

    public abstract void onClick();
//    {
//        System.out.println("From UIObject.java.onClick(): " + clicked);
//        clicked = ! clicked;
//    }

    public void onMouseMove(MouseEvent e) {
        if (bounds.contains(e.getX(), e.getY())) {
            hovering = true;
//            System.out.println("u sure?" + bounds.getX() + " " + bounds.getY() + " " + bounds.getWidth() + " " + bounds.getHeight() + " ");
        } else {
            hovering = false;
        }
    }

    public void onMouseRelease(MouseEvent e) {
        if (hovering) {
            onClick();
        }
    }


    // GETTERS AND SETTERS

    public float getX() {
        return x;
    }

    public void setX(float x) {
        this.x = x;
    }

    public float getY() {
        return y;
    }

    public void setY(float y) {
        this.y = y;
    }

    public int getWidth() {
        return width;
    }

    public void setWidth(int width) {
        this.width = width;
    }

    public int getHeight() {
        return height;
    }

    public void setHeight(int height) {
        this.height = height;
    }

    public boolean isHovering() {
        return hovering;
    }

    public void setHovering(boolean hovering) {
        this.hovering = hovering;
    }
}
