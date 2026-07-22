package com.michitai.multiplayer.rooms.actions;

import com.fasterxml.jackson.annotation.JsonProperty;
import com.michitai.multiplayer.ApiResponse;

import java.util.List;

/**
 * Response containing completed actions that were targeted to the current player.
 */
public class ActionPollResponse extends ApiResponse {
    @JsonProperty("actions")
    private List<ActionInfo> actions;

    public List<ActionInfo> getActions() {
        return actions;
    }

    public void setActions(List<ActionInfo> actions) {
        this.actions = actions;
    }
}
