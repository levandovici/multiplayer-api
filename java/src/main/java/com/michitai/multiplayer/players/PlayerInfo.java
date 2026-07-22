package com.michitai.multiplayer.players;

import com.fasterxml.jackson.annotation.JsonProperty;
import com.fasterxml.jackson.databind.JsonNode;

/**
 * Player information including ID, name, and custom data.
 *
 * @param <T> The type to deserialize player data into.
 */
public class PlayerInfo<T> {
    @JsonProperty("id")
    private int id;

    @JsonProperty("player_name")
    private String playerName;

    @JsonProperty("data_json")
    private String dataJson;

    @JsonProperty("data")
    private JsonNode data;

    @JsonProperty("last_login")
    private String lastLogin;

    @JsonProperty("created_at")
    private String createdAt;

    @JsonProperty("is_online")
    private boolean isOnline;

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getPlayerName() {
        return playerName;
    }

    public void setPlayerName(String playerName) {
        this.playerName = playerName;
    }

    public String getDataJson() {
        return dataJson;
    }

    public void setDataJson(String dataJson) {
        this.dataJson = dataJson;
    }

    public JsonNode getData() {
        return data;
    }

    public void setData(JsonNode data) {
        this.data = data;
    }

    public String getLastLogin() {
        return lastLogin;
    }

    public void setLastLogin(String lastLogin) {
        this.lastLogin = lastLogin;
    }

    public String getCreatedAt() {
        return createdAt;
    }

    public void setCreatedAt(String createdAt) {
        this.createdAt = createdAt;
    }

    public boolean isOnline() {
        return isOnline;
    }

    public void setOnline(boolean online) {
        isOnline = online;
    }
}
