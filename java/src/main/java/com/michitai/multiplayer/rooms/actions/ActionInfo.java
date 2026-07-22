package com.michitai.multiplayer.rooms.actions;

import com.fasterxml.jackson.annotation.JsonProperty;
import com.fasterxml.jackson.databind.JsonNode;

/**
 * Information about a completed action.
 */
public class ActionInfo {
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

    @JsonProperty("response_data_json")
    private String responseDataJson;

    @JsonProperty("response_data")
    private JsonNode responseData;

    @JsonProperty("status")
    private String status;

    @JsonProperty("created_at")
    private String createdAt;

    @JsonProperty("completed_at")
    private String completedAt;

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

    public String getResponseDataJson() {
        return responseDataJson;
    }

    public void setResponseDataJson(String responseDataJson) {
        this.responseDataJson = responseDataJson;
    }

    public JsonNode getResponseData() {
        return responseData;
    }

    public void setResponseData(JsonNode responseData) {
        this.responseData = responseData;
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    public String getCreatedAt() {
        return createdAt;
    }

    public void setCreatedAt(String createdAt) {
        this.createdAt = createdAt;
    }

    public String getCompletedAt() {
        return completedAt;
    }

    public void setCompletedAt(String completedAt) {
        this.completedAt = completedAt;
    }
}
