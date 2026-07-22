package com.michitai.multiplayer.players;

import com.fasterxml.jackson.annotation.JsonProperty;
import com.michitai.multiplayer.ApiResponse;

/**
 * Response containing the ban details.
 */
public class PlayerBanResponse extends ApiResponse {
    @JsonProperty("message")
    private String message;

    @JsonProperty("player_id")
    private int playerId;

    @JsonProperty("ban_duration")
    private String banDuration;

    @JsonProperty("ban_reason")
    private String banReason;

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public int getPlayerId() {
        return playerId;
    }

    public void setPlayerId(int playerId) {
        this.playerId = playerId;
    }

    public String getBanDuration() {
        return banDuration;
    }

    public void setBanDuration(String banDuration) {
        this.banDuration = banDuration;
    }

    public String getBanReason() {
        return banReason;
    }

    public void setBanReason(String banReason) {
        this.banReason = banReason;
    }
}
