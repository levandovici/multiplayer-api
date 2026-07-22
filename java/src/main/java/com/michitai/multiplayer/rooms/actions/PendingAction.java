package com.michitai.multiplayer.rooms.actions;

import com.fasterxml.jackson.annotation.JsonProperty;
import com.fasterxml.jackson.databind.JsonNode;

/**
 * Information about a pending action awaiting completion.
 *
 * @param <T> The type to deserialize action data into.
 */
public class PendingAction<T> {
    @JsonProperty("action_id")
    private String actionId;

    @JsonProperty("sender_id")
    private int senderId;

    @JsonProperty("sender_name")
    private String senderName;

    @JsonProperty("action_type")
    private String actionType;

    @JsonProperty("request_data_json")
    private String requestDataJson;

    @JsonProperty("request_data")
    private JsonNode requestData;

    @JsonProperty("created_at")
    private String createdAt;

    public String getActionId() {
        return actionId;
    }

    public void setActionId(String actionId) {
        this.actionId = actionId;
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

    public String getActionType() {
        return actionType;
    }

    public void setActionType(String actionType) {
        this.actionType = actionType;
    }

    public String getRequestDataJson() {
        return requestDataJson;
    }

    public void setRequestDataJson(String requestDataJson) {
        this.requestDataJson = requestDataJson;
    }

    public JsonNode getRequestData() {
        return requestData;
    }

    public void setRequestData(JsonNode requestData) {
        this.requestData = requestData;
    }

    public String getCreatedAt() {
        return createdAt;
    }

    public void setCreatedAt(String createdAt) {
        this.createdAt = createdAt;
    }
}
