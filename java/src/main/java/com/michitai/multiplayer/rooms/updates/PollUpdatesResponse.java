package com.michitai.multiplayer.rooms.updates;

import com.fasterxml.jackson.annotation.JsonProperty;
import com.michitai.multiplayer.ApiResponse;

import java.util.List;

/**
 * Response containing player updates that were targeted to the polling player.
 */
public class PollUpdatesResponse extends ApiResponse {
    @JsonProperty("updates")
    private List<PlayerUpdate> updates;

    @JsonProperty("last_update")
    private String lastUpdate;

    public List<PlayerUpdate> getUpdates() {
        return updates;
    }

    public void setUpdates(List<PlayerUpdate> updates) {
        this.updates = updates;
    }

    public String getLastUpdate() {
        return lastUpdate;
    }

    public void setLastUpdate(String lastUpdate) {
        this.lastUpdate = lastUpdate;
    }
}
