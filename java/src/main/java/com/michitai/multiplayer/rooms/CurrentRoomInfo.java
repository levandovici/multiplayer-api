package com.michitai.multiplayer.rooms;

import com.fasterxml.jackson.annotation.JsonProperty;
import com.fasterxml.jackson.databind.JsonNode;

/**
 * Comprehensive information about a game room.
 *
 * @param <T> The type to deserialize room rules into.
 */
public class CurrentRoomInfo<T> {
    @JsonProperty("room_id")
    private String roomId;

    @JsonProperty("room_name")
    private String roomName;

    @JsonProperty("max_players")
    private int maxPlayers;

    @JsonProperty("current_players")
    private int currentPlayers;

    @JsonProperty("has_password")
    private boolean hasPassword;

    @JsonProperty("realtime")
    private boolean realtime;

    @JsonProperty("host_switch")
    private boolean hostSwitch;

    @JsonProperty("can_leave_room")
    private boolean canLeaveRoom;

    @JsonProperty("rules_json")
    private String rulesJson;

    @JsonProperty("rules")
    private JsonNode rules;

    @JsonProperty("created_at")
    private String createdAt;

    public String getRoomId() {
        return roomId;
    }

    public void setRoomId(String roomId) {
        this.roomId = roomId;
    }

    public String getRoomName() {
        return roomName;
    }

    public void setRoomName(String roomName) {
        this.roomName = roomName;
    }

    public int getMaxPlayers() {
        return maxPlayers;
    }

    public void setMaxPlayers(int maxPlayers) {
        this.maxPlayers = maxPlayers;
    }

    public int getCurrentPlayers() {
        return currentPlayers;
    }

    public void setCurrentPlayers(int currentPlayers) {
        this.currentPlayers = currentPlayers;
    }

    public boolean isHasPassword() {
        return hasPassword;
    }

    public void setHasPassword(boolean hasPassword) {
        this.hasPassword = hasPassword;
    }

    public boolean isRealtime() {
        return realtime;
    }

    public void setRealtime(boolean realtime) {
        this.realtime = realtime;
    }

    public boolean isHostSwitch() {
        return hostSwitch;
    }

    public void setHostSwitch(boolean hostSwitch) {
        this.hostSwitch = hostSwitch;
    }

    public boolean isCanLeaveRoom() {
        return canLeaveRoom;
    }

    public void setCanLeaveRoom(boolean canLeaveRoom) {
        this.canLeaveRoom = canLeaveRoom;
    }

    public String getRulesJson() {
        return rulesJson;
    }

    public void setRulesJson(String rulesJson) {
        this.rulesJson = rulesJson;
    }

    public JsonNode getRules() {
        return rules;
    }

    public void setRules(JsonNode rules) {
        this.rules = rules;
    }

    public String getCreatedAt() {
        return createdAt;
    }

    public void setCreatedAt(String createdAt) {
        this.createdAt = createdAt;
    }
}
