package com.michitai.multiplayer.rooms.updates;

import com.fasterxml.jackson.annotation.JsonProperty;
import com.fasterxml.jackson.databind.JsonNode;

/**
 * Information about a player update.
 */
public class PlayerUpdate {
    @JsonProperty("update_id")
    private String updateId;

    @JsonProperty("sender_id")
    private int senderId;

    @JsonProperty("sender_name")
    private String senderName;

    @JsonProperty("type")
    private String type;

    @JsonProperty("data_json")
    private String dataJson;

    @JsonProperty("data")
    private JsonNode data;

    @JsonProperty("created_at")
    private String createdAt;

    public String getUpdateId() {
        return updateId;
    }

    public void setUpdateId(String updateId) {
        this.updateId = updateId;
    }

    public int getSenderId() {
        return senderId;
    }

    public void setSenderId(int senderId) {
        this.senderId = senderId;
    }

    public String getSenderName() {
        return senderName;
    }

    public void setSenderName(String senderName) {
        this.senderName = senderName;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
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
