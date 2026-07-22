package com.michitai.multiplayer.players;

import com.fasterxml.jackson.annotation.JsonProperty;

/**
 * Request for registering a new player.
 */
public class PlayerRegisterRequest {
    @JsonProperty("name")
    private String name;

    @JsonProperty("data")
    private String data;

    public PlayerRegisterRequest(String name, String data) {
        this.name = name;
        this.data = data;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getData() {
        return data;
    }

    public void setData(String data) {
        this.data = data;
    }
}
