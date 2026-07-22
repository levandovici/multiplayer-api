package com.michitai.multiplayer.matchmaking;

import com.fasterxml.jackson.annotation.JsonProperty;
import com.fasterxml.jackson.databind.JsonNode;

/**
 * Base information for a matchmaking join request.
 */
public class MatchmakingRequestBase {
    @JsonProperty("request_id")
    private String requestId;

    @JsonProperty("player_id")
    private int playerId;

    @JsonProperty("player_name")
    private String playerName;

    @JsonProperty("status")
    private String status;

    @JsonProperty("data_json")
    private String dataJson;

    @JsonProperty("data")
    private JsonNode data;

    @JsonProperty("created_at")
    private String createdAt;

    public String getRequestId() {
        return requestId;
    }

    public void setRequestId(String requestId) {
        this.requestId = requestId;
    }

    public int getPlayerId() {
        return playerId;
    }

    public void setPlayerId(int playerId) {
        this.playerId = playerId;
    }

    public String getPlayerName() {
        return playerName;
    }

    public void setPlayerName(String playerName) {
        this.playerName = playerName;
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
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

    public String getCreatedAt() {
        return createdAt;
    }

    public void setCreatedAt(String createdAt) {
        this.createdAt = createdAt;
    }
}
