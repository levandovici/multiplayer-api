package com.michitai.multiplayer.rooms;

import com.fasterxml.jackson.annotation.JsonProperty;
import com.fasterxml.jackson.databind.JsonNode;

/**
 * Short room information for room listings.
 */
public class RoomShort {
    @JsonProperty("room_id")
    private String roomId;

    @JsonProperty("room_name")
    private String roomName;

    @JsonProperty("current_players")
    private int currentPlayers;

    @JsonProperty("max_players")
    private int maxPlayers;

    @JsonProperty("has_password")
    private boolean hasPassword;

    @JsonProperty("realtime")
    private boolean realtime;

    @JsonProperty("rules_json")
    private String rulesJson;

    @JsonProperty("rules")
    private JsonNode rules;

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

    public int getCurrentPlayers() {
        return currentPlayers;
    }

    public void setCurrentPlayers(int currentPlayers) {
        this.currentPlayers = currentPlayers;
    }

    public int getMaxPlayers() {
        return maxPlayers;
    }

    public void setMaxPlayers(int maxPlayers) {
        this.maxPlayers = maxPlayers;
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
}
