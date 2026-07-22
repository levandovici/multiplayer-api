package com.michitai.multiplayer.rooms;

import com.fasterxml.jackson.databind.ObjectMapper;
import com.michitai.multiplayer.ApiResponse;
import com.michitai.multiplayer.Client;
import com.michitai.multiplayer.SuccessResponse;

import java.io.IOException;

/**
 * Provides methods for game room management including creation, joining,
 * player management, actions, updates, and administrative operations.
 */
public class Rooms {
    private static final ObjectMapper objectMapper = Client.JSON_MAPPER;

    /**
     * Creates a new game room with specified configuration.
     *
     * @param client The API client instance.
     * @param playerToken The host's player token.
     * @param roomName The name of the game room.
     * @param maxPlayers Maximum number of players allowed.
     * @param password Optional password for the room.
     * @param hostSwitch Whether host can switch.
     * @param canLeaveRoom Whether players can leave.
     * @param realtime Whether room supports realtime.
     * @param playerData Optional player data for the host.
     * @param rules Optional game rules for the room.
     * @return Response containing the created room ID.
     * @throws IOException if the request fails.
     */
    public static RoomCreateResponse createRoom(Client client, String playerToken, String roomName, int maxPlayers,
            String password, boolean hostSwitch, boolean canLeaveRoom, boolean realtime, Object playerData, Object rules) throws IOException {
        String playerDataJson = playerData != null ? objectMapper.writeValueAsString(playerData) : null;
        String rulesJson = rules != null ? objectMapper.writeValueAsString(rules) : null;
        
        RoomCreateRequest request = new RoomCreateRequest(roomName, password, maxPlayers, hostSwitch, realtime, playerDataJson, rulesJson);
        return client.post(client.url(Endpoints.GAME_ROOM_CREATE, "&player_token=" + playerToken), request, RoomCreateResponse.class);
    }

    /**
     * Retrieves a list of available game rooms.
     *
     * @param client The API client instance.
     * @param search Optional search term to filter rooms.
     * @param limit Optional limit on the number of results.
     * @return Response containing the list of game rooms.
     * @throws IOException if the request fails.
     */
    public static RoomListResponse<?> getRooms(Client client, String search, Integer limit) throws IOException {
        RoomListRequest request = new RoomListRequest(search, limit);
        return client.post(client.url(Endpoints.GAME_ROOM_LIST), request, RoomListResponse.class);
    }

    /**
     * Joins an existing game room.
     *
     * @param client The API client instance.
     * @param playerToken The player's authentication token.
     * @param roomId The ID of the room to join.
     * @param password Optional password if the room is password-protected.
     * @param playerData Optional player data to include when joining.
     * @return Response confirming the player joined the room.
     * @throws IOException if the request fails.
     */
    public static RoomJoinResponse joinRoom(Client client, String playerToken, String roomId, String password, Object playerData) throws IOException {
        String playerDataJson = playerData != null ? objectMapper.writeValueAsString(playerData) : null;
        String endpoint = String.format(Endpoints.GAME_ROOM_JOIN, roomId);
        
        RoomJoinRequest request = (password != null || playerDataJson != null) ? 
            new RoomJoinRequest(password, playerDataJson) : null;
        
        return client.post(client.url(endpoint, "&player_token=" + playerToken), request, RoomJoinResponse.class);
    }

    /**
     * Leaves the current game room.
     *
     * @param client The API client instance.
     * @param playerToken The player's authentication token.
     * @return Response confirming the player left the room.
     * @throws IOException if the request fails.
     */
    public static RoomLeaveResponse leaveRoom(Client client, String playerToken) throws IOException {
        return client.post(client.url(Endpoints.GAME_ROOM_LEAVE, "&player_token=" + playerToken), null, RoomLeaveResponse.class);
    }

    /**
     * Gets the list of players in the current game room.
     *
     * @param client The API client instance.
     * @param playerToken The player's authentication token.
     * @return Response containing the list of players in the room.
     * @throws IOException if the request fails.
     */
    public static RoomPlayersResponse<?> getRoomPlayers(Client client, String playerToken) throws IOException {
        return client.get(client.url(Endpoints.GAME_ROOM_PLAYERS, "&player_token=" + playerToken), RoomPlayersResponse.class);
    }

    /**
     * Sends a heartbeat to maintain the player's presence in the game room.
     *
     * @param client The API client instance.
     * @param playerToken The player's authentication token.
     * @return Response confirming the heartbeat was received.
     * @throws IOException if the request fails.
     */
    public static HeartbeatResponse sendRoomHeartbeat(Client client, String playerToken) throws IOException {
        return client.post(client.url(Endpoints.GAME_ROOM_HEARTBEAT, "&player_token=" + playerToken), null, HeartbeatResponse.class);
    }

    /**
     * Gets comprehensive information about the current game room including players and pending actions.
     *
     * @param client The API client instance.
     * @param playerToken The player's authentication token.
     * @return Response containing detailed room information.
     * @throws IOException if the request fails.
     */
    public static CurrentRoomResponse<?> getCurrentRoom(Client client, String playerToken) throws IOException {
        return client.get(client.url(Endpoints.GAME_ROOM_CURRENT, "&player_token=" + playerToken), CurrentRoomResponse.class);
    }

    /**
     * Stops the current game room and removes all associated data (host only).
     *
     * @param client The API client instance.
     * @param playerToken The host's authentication token.
     * @return Success response confirming the room was stopped.
     * @throws IOException if the request fails.
     */
    public static SuccessResponse stopRoom(Client client, String playerToken) throws IOException {
        return client.post(client.url(Endpoints.GAME_ROOM_STOP, "&player_token=" + playerToken), null, SuccessResponse.class);
    }

    /**
     * Kicks a player from the game room (host only).
     * Cannot kick yourself.
     *
     * @param client The API client instance.
     * @param playerToken The host's authentication token.
     * @param playerId The ID of the player to kick.
     * @return Response confirming the player was kicked.
     * @throws IOException if the request fails.
     */
    public static RoomKickResponse kickPlayer(Client client, String playerToken, int playerId) throws IOException {
        RoomKickRequest request = new RoomKickRequest(playerId);
        return client.post(client.url(Endpoints.GAME_ROOM_KICK, "&player_token=" + playerToken), request, RoomKickResponse.class);
    }

    /**
     * Updates the password for the game room (host only).
     * Use empty string to remove the password.
     *
     * @param client The API client instance.
     * @param playerToken The host's authentication token.
     * @param password New password, or null to remove the password.
     * @return Success response confirming the password was updated.
     * @throws IOException if the request fails.
     */
    public static SuccessResponse updateRoomPassword(Client client, String playerToken, String password) throws IOException {
        RoomPasswordUpdateRequest request = new RoomPasswordUpdateRequest(password);
        return client.post(client.url(Endpoints.GAME_ROOM_PASSWORD, "&player_token=" + playerToken), request, SuccessResponse.class);
    }
}
