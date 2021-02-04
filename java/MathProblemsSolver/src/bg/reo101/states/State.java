package bg.reo101.states;

import bg.reo101.Handler;

import java.awt.*;

/**
 * Abstract class containing base logic for all states.
 */
public abstract class State {

    /**
     * Handler object.
     */
    protected static Handler handler;
    /**
     * Stores the current state.
     */
    private static State currentState = null;

    /**
     * State constructor.
     *
     * @param handler Handler object that is passed everywhere throughout the program.
     */
    public State(Handler handler) {
        State.handler = handler;
    }

    //CLASS

    /**
     * State getter.
     *
     * @return The current state.
     */
    public static State getState() {
        return currentState;
    }

    /**
     * State setter.
     *
     * @param state State to be set.
     */
    public static void setState(State state) {
        currentState = state;
    }

    /**
     * Method used for setting the handlers'/global uiManager to the local one.
     */
    public abstract void setSpecificUiManager();

    /**
     * Method for ticking/updating changes on the page.
     */
    public abstract void tick();

    /**
     * Method for rendering everything on the page.
     *
     * @param g Graphics object passed everywhere throughout the program.
     */
    public abstract void render(Graphics g);

}
