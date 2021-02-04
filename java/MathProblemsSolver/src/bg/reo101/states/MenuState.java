package bg.reo101.states;

import bg.reo101.Handler;
import bg.reo101.gfx.Assets;
import bg.reo101.ui.UIImageButton;
import bg.reo101.ui.UIManager;

import java.awt.*;

/**
 * Class used to store all the logic for the Menu page.
 */
public class MenuState extends State {

    /**
     * Local UIManager object.
     */
    private UIManager uiManager;

    /**
     * Main constructor.
     *
     * @param handler Handler object that is passed everywhere throughout the program.
     */
    public MenuState(Handler handler) {
        super(handler);
        uiManager = new UIManager(handler);

        uiManager.addObject(new UIImageButton(100, 200, 64, 64, Assets.normal, () -> {
            handler.getGame().normalState.setSpecificUiManager();
            State.setState(handler.getGame().normalState);
        }, true, false));

        uiManager.addObject(new UIImageButton(300, 200, 64, 64, Assets.functions, () -> {
            handler.getMouseManager().setUiManager(null);
            handler.getGame().functionsState.setSpecificUiManager();
            State.setState(handler.getGame().functionsState);
        }, true, false));

        uiManager.addObject(new UIImageButton(500, 200, 64, 64, Assets.statistics, () -> {
            handler.getMouseManager().setUiManager(null);
            handler.getGame().statisticsState.setSpecificUiManager();
            State.setState(handler.getGame().statisticsState);
        }, true, false));
    }

    /**
     * Method used for setting the handlers'/global uiManager to the local one.
     */
    @Override
    public void setSpecificUiManager() {
        handler.getMouseManager().setUiManager(uiManager);
    }

    /**
     * Method for ticking/updating changes on the page.
     */
    @Override
    public void tick() {
        uiManager.tick();
    }

    /**
     * Method for rendering everything on the page.
     *
     * @param g Graphics object passed everywhere throughout the program.
     */
    @Override
    public void render(Graphics g) {
        uiManager.render(g);
    }
}
