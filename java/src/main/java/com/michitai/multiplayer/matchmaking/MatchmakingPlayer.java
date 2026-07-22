package com.michitai.multiplayer.matchmaking;

import com.fasterxml.jackson.annotation.JsonProperty;
import com.fasterxml.jackson.databind.JsonNode;

/**
 * Player information within a matchmaking lobby.
 *
 * @param <T> The type to deserialize player data into.
 */
public class MatchmakingPlayer<T> {
    @JsonProperty("id")
    private int id;

    @JsonProperty("player_name")
    private String playerName;

    @JsonProperty("is_host")
    private boolean isHost;

    @JsonProperty("data_json")
    private String dataJson;

    @JsonProperty("data")
    private JsonNode data;

    @JsonProperty("joined_at")
    private String joinedAt;

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

    public boolean isHost() {
        return isHost;
    }

    public void setHost(boolean host) {
        isHost = host;
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

    public String getJoinedAt() {
        return joinedAt;
    }

    public void setJoinedAt(String joinedAt) {
        this.joinedAt = joinedAt;
    }
}
