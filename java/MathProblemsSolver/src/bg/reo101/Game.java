package bg.reo101;

import bg.reo101.display.Display;
import bg.reo101.gfx.Assets;
//import bg.reo101.gfx.GameCamera;
import bg.reo101.input.KeyManager;
import bg.reo101.input.MouseManager;
//import bg.reo101.sfx.Sounds;
import bg.reo101.states.*;

import java.awt.*;
import java.awt.image.BufferStrategy;

public class Game implements Runnable {

    private Display display;
    public static int width, height;
    public String title;

    private boolean running = false;
    private Thread thread;

    private BufferStrategy bs;
    private Graphics g;

    //Sound
    static boolean sound = true;

    //FPS
    static int fps = 60;
    static double timePerTick = 1000000000 / fps;

    //States
    public State menuState;
    public State normalState;
    public State graphsState;
    public State functionsState;
    public State statisticsState;
    public State progressionsState;
    public State combinatoricsState;

    //Input
    private KeyManager keyManager;
    private MouseManager mouseManager;

    //Cursor
//    private Cursor cursor = Toolkit
//            .getDefaultToolkit()
//            .createCustomCursor(
//            Assets.cursor,
//                    new Point(0, 0),
//                    "Cursor");

//
//    //Camera
//    private GameCamera gameCamera;

    //Handler
    private Handler handler;

    public Game(String title, int width, int height) {
        this.title = title;
        Game.width = width;
        Game.height = height;
        keyManager = new KeyManager();
        mouseManager = new MouseManager();
    }

    private void init() { // initialization
        display = new Display(title, width, height);
        display.getFrame().addKeyListener(keyManager);
        display.getFrame().addMouseListener(mouseManager);
        display.getFrame().addMouseMotionListener(mouseManager);
        display.getCanvas().addMouseListener(mouseManager);
        display.getCanvas().addMouseMotionListener(mouseManager);
//        display.getFrame().setCursor(cursor);
        Assets.init();
//        Sounds.init();
        //Test code
//        Sounds.walking.start();

        handler = new Handler(this);
//        gameCamera = new GameCamera(handler, 0, 0);

        menuState = new MenuState(handler);
        normalState = new NormalState(handler);
//        graphsState = new NormalState(handler);
        functionsState = new FunctionsState(handler);
        statisticsState = new StatisticsState(handler);

        menuState.setSpecificUiManager();
        State.setState(menuState);
    }

    private void tick() { // update
        keyManager.tick();

        if (State.getState() != null) {
            State.getState().tick();
        }
    }

    private void render() { // draw
        bs = display.getCanvas().getBufferStrategy();
        if (bs == null) {
            display.getCanvas().createBufferStrategy(3);
            return;
        }
        g = bs.getDrawGraphics();
        //Clear Screen
        g.setFont(Assets.font50);
        g.clearRect(0, 0, width, height);
        //Draw Here!

        if (State.getState() != null) {
            State.getState().render(g);
        }

        //End Drawing!
        bs.show();
        g.dispose();
    }

    public void run() {

        init();

        double delta = 0;
        long now;
        long lastTime = System.nanoTime();
        long timer = 0;
        int ticks = 0;

        while (running) {
            now = System.nanoTime();
            delta += (now - lastTime) / timePerTick;
            timer += now - lastTime;
            lastTime = now;

            if (delta >= 1) {
                tick();
                render();
                ticks++;
                delta--;
            }

//            if (timer >= 1000000000) {
//                System.out.println("Ticks and Frames: " + ticks);
//                ticks = 0;
//                timer = 0;
//            }
        }

        stop();

    }

    public KeyManager getKeyManager() {
        return keyManager;
    }

    public MouseManager getMouseManager() {
        return mouseManager;
    }

    public static void setFps(int fps) {
        Game.fps = fps;
        timePerTick = 1000000000 / fps;
    }

    public static void toggleSound() {
        sound = ! sound;
    }

    public static boolean getSound() {
        return sound;
    }

//    public GameCamera getGameCamera() {
//        return gameCamera;
//    }

    public int getWidth() {
        return width;
    }

    public int getHeight() {
        return height;
    }

    public synchronized void start() {
        if (running) {
            return;
        }
        running = true;
        thread = new Thread(this);
        thread.start();
    }

    public synchronized void stop() {
        if (! running)
            return;
        running = false;
        try {
            thread.join();
        } catch (InterruptedException e) {
            e.printStackTrace();
        }
    }

}
