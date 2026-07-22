package com.michitai.multiplayer.rooms;

import com.fasterxml.jackson.annotation.JsonProperty;
import com.michitai.multiplayer.ApiResponse;

import java.util.List;

/**
 * Response containing comprehensive information about the current game room.
 * Includes room details, players, and pending actions/updates.
 *
 * @param <T> The type to deserialize room rules into.
 */
public class CurrentRoomResponse<T> extends ApiResponse {
    @JsonProperty("in_room")
    private boolean inRoom;

    @JsonProperty("room")
    private CurrentRoomInfo<T> room;

    @JsonProperty("pending_actions_json")
    private List<String> pendingActionsJson;

    @JsonProperty("pending_updates_json")
    private List<String> pendingUpdatesJson;

    public boolean isInRoom() {
        return inRoom;
    }

    public void setInRoom(boolean inRoom) {
        this.inRoom = inRoom;
    }

    public CurrentRoomInfo<T> getRoom() {
        return room;
    }

    public void setRoom(CurrentRoomInfo<T> room) {
        this.room = room;
    }

    public List<String> getPendingActionsJson() {
        return pendingActionsJson;
    }

    public void setPendingActionsJson(List<String> pendingActionsJson) {
        this.pendingActionsJson = pendingActionsJson;
    }

    public List<String> getPendingUpdatesJson() {
        return pendingUpdatesJson;
    }

    public void setPendingUpdatesJson(List<String> pendingUpdatesJson) {
        this.pendingUpdatesJson = pendingUpdatesJson;
    }
}
