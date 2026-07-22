package com.michitai.multiplayer.matchmaking;

import com.fasterxml.jackson.annotation.JsonProperty;
import com.fasterxml.jackson.databind.JsonNode;

/**
 * Short matchmaking lobby information for lobby listings.
 */
public class MatchmakingLobby {
    @JsonProperty("matchmaking_id")
    private String matchmakingId;

    @JsonProperty("matchmaking_name")
    private String matchmakingName;

    @JsonProperty("current_players")
    private int currentPlayers;

    @JsonProperty("max_players")
    private int maxPlayers;

    @JsonProperty("has_password")
    private boolean hasPassword;

    @JsonProperty("join_by_requests")
    private boolean joinByRequests;

    @JsonProperty("rules_json")
    private String rulesJson;

    @JsonProperty("rules")
    private JsonNode rules;

    public String getMatchmakingId() {
        return matchmakingId;
    }

    public void setMatchmakingId(String matchmakingId) {
        this.matchmakingId = matchmakingId;
    }

    public String getMatchmakingName() {
        return matchmakingName;
    }

    public void setMatchmakingName(String matchmakingName) {
        this.matchmakingName = matchmakingName;
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

    public boolean isJoinByRequests() {
        return joinByRequests;
    }

    public void setJoinByRequests(boolean joinByRequests) {
        this.joinByRequests = joinByRequests;
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
