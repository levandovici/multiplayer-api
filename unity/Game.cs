using UnityEngine;
using System.Collections;
using System.Collections.Generic;
using System;
using MultiplayerAPI;

/// <summary>
/// Unity Multiplayer API Demo Game Controller
/// Comprehensive demonstration of all SDK features including player management,
/// matchmaking, game rooms, real-time actions, and leaderboards.
/// </summary>
/// <remarks>
/// This class provides a complete demonstration of the Multiplayer API SDK functionality.
/// It showcases player registration, authentication, matchmaking lobby creation,
/// join requests, game room management, real-time actions, updates, and leaderboards.
/// 
/// The demo follows a logical progression:
/// 1. SDK initialization and player registration
/// 2. Player authentication and data management
/// 3. Traditional room management (for comparison)
/// 4. Matchmaking lobby system with approval workflow
/// 5. Game start from matchmaking to room transition
/// 6. Real-time game room functionality
/// 7. Leaderboard system with sorting options
/// 
/// Use this demo as a reference for implementing multiplayer features in your Unity game.
/// The coroutines demonstrate proper async handling and token management.
/// </remarks>
/// <example>
/// <code>
/// // Attach this script to a GameObject in your Unity scene
/// // Configure API tokens in the Inspector
/// // Set autoStartDemo to true to run the demo automatically
/// // Or call RunGameDemo() manually from your code
/// </code>
/// </example>
public class Game : MonoBehaviour
{
    /// <summary>
    /// Multiplayer SDK instance for API communication
    /// Handles all multiplayer operations and HTTP requests
    /// </summary>
    private MultiplayerSDK sdk;
    
    /// <summary>
    /// Dictionary of registered players with their authentication tokens
    /// Used for managing multiple player sessions in the demo
    /// </summary>
    private Dictionary<string, PlayerInfo> players = new Dictionary<string, PlayerInfo>();

    [Header("Game Configuration")]
    /// <summary>
    /// API authentication token for server communication
    /// Required for all API calls - obtain from multiplayer service provider
    /// </summary>
    public string apiToken = "YOUR_API_TOKEN";
    
    /// <summary>
    /// Private API token for enhanced security (if required)
    /// Used for privileged operations like admin functions
    /// </summary>
    public string apiPrivateToken = "YOUR_API_PRIVATE_TOKEN";
    
    /// <summary>
    /// Automatically start the demo when the game begins
    /// Set to false to manually trigger the demo from other scripts
    /// </summary>
    public bool autoStartDemo = true;

    /// <summary>
    /// Unity Start method - initializes the demo
    /// Called when the GameObject is activated
    /// </summary>
    /// <remarks>
    /// This method initializes the demo by setting up the SDK and
    /// optionally starting the automated demo sequence.
    /// The SDK is added as a component to ensure proper lifecycle management.
    /// </remarks>
    void Start()
    {
        if (autoStartDemo)
        {
            StartCoroutine(RunGameDemo());
        }
    }

    /// <summary>
    /// Comprehensive multiplayer API demonstration
    /// Showcases all SDK features in a logical workflow
    /// </summary>
    /// <remarks>
    /// This coroutine demonstrates the complete multiplayer API functionality:
    /// 
    /// <b>Phase 1: Player Management</b>
    /// - Register multiple players with different roles and data
    /// - Authenticate all registered players
    /// - Manage player-specific and global game data
    /// 
    /// <b>Phase 2: Traditional Rooms</b>
    /// - Create and manage traditional game rooms
    /// - Demonstrate room listing and player management
    /// 
    /// <b>Phase 3: Matchmaking System</b>
    /// - Create matchmaking lobbies with custom settings
    /// - Handle join requests and approval workflow
    /// - Demonstrate direct join and lobby management
    /// - Start games from matchmaking lobbies
    /// 
    /// <b>Phase 4: Real-time Gameplay</b>
    /// - Submit and process player actions
    /// - Send real-time updates to players
    /// - Poll for game state changes
    /// 
    /// <b>Phase 5: Competitive Features</b>
    /// - Retrieve and display leaderboards
    /// - Test multiple sorting options
    /// 
    /// Each phase includes comprehensive logging and error handling
    /// to demonstrate production-ready implementation patterns.
    /// </remarks>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    /// <example>
    /// <code>
    /// // Start the demo manually
    /// StartCoroutine(RunGameDemo());
    /// 
    /// // Or disable autoStartDemo and call from UI button
    /// public void StartDemoFromUI() {
    ///     StartCoroutine(RunGameDemo());
    /// }
    /// </code>
    /// </example>
    public IEnumerator RunGameDemo()
    {
        Debug.Log("=== MICHITAI Unity Game SDK Demo ===\n");

        // 1️⃣ Initialize SDK
        sdk = gameObject.AddComponent<MultiplayerSDK>();
        sdk.SetApiToken(apiToken);
        sdk.SetGamePlayerToken(""); // Will be set per player
        Debug.Log("[INIT] SDK initialized\n");

        yield return new WaitForSeconds(1f);

        // 2️⃣ Register Multiple Players for Matchmaking Demo
        Debug.Log("[PLAYERS] Registering multiple players for matchmaking demo...");
        
        // Register Host Player
        yield return StartCoroutine(RegisterPlayerCoroutine("GameHost", "{\"level\":10,\"rank\":\"gold\",\"role\":\"host\"}", (response) => {
            if (response.success) {
                players["host"] = new PlayerInfo { 
                    Id = response.player_id, 
                    Token = response.private_key, 
                    Name = "GameHost" 
                };
                Debug.Log($"[HOST] Registered: ID={response.player_id}, Token={response.private_key.Substring(0, 8)}...\n");
            }
        }));

        yield return new WaitForSeconds(0.5f);

        // Register Multiple Players
        yield return StartCoroutine(RegisterPlayerCoroutine("Player1", "{\"level\":8,\"rank\":\"silver\",\"role\":\"player\"}", (response) => {
            if (response.success) {
                players["player1"] = new PlayerInfo { 
                    Id = response.player_id, 
                    Token = response.private_key, 
                    Name = "Player1" 
                };
            }
        }));

        yield return StartCoroutine(RegisterPlayerCoroutine("Player2", "{\"level\":12,\"rank\":\"gold\",\"role\":\"player\"}", (response) => {
            if (response.success) {
                players["player2"] = new PlayerInfo { 
                    Id = response.player_id, 
                    Token = response.private_key, 
                    Name = "Player2" 
                };
            }
        }));

        yield return StartCoroutine(RegisterPlayerCoroutine("Player3", "{\"level\":6,\"rank\":\"bronze\",\"role\":\"player\"}", (response) => {
            if (response.success) {
                players["player3"] = new PlayerInfo { 
                    Id = response.player_id, 
                    Token = response.private_key, 
                    Name = "Player3" 
                };
            }
        }));

        yield return new WaitForSeconds(1f);
        Debug.Log($"[PLAYERS] Total registered: {players.Count} players\n");

        // 3️⃣ Authenticate All Players
        Debug.Log("[AUTH] Authenticating all players...");
        foreach (var kvp in players)
        {
            yield return StartCoroutine(AuthenticatePlayerCoroutine(kvp.Value.Token, (response) => {
                if (response.success) {
                    Debug.Log($"[AUTH] {kvp.Value.Name} authenticated successfully");
                }
            }));
        }
        yield return new WaitForSeconds(1f);

        // 4️⃣ List all players (admin view)
        Debug.Log("[ADMIN] Fetching all players...");
        yield return StartCoroutine(ListPlayersCoroutine((response) => {
            if (response.success) {
                Debug.Log($"[ADMIN] Total players in database: {response.count}");
                foreach (var p in response.players) {
                    Debug.Log($" - ID={p.id}, Name={p.player_name}, Active={p.is_active}");
                }
            }
        }));
        yield return new WaitForSeconds(1f);

        // 5️⃣ Get global game data
        Debug.Log("[GAME] Loading game data...");
        yield return StartCoroutine(GetGameDataCoroutine((response) => {
            if (response.success) {
                Debug.Log($"[GAME] Game ID={response.game_id}, Data loaded");
            }
        }));
        yield return new WaitForSeconds(1f);

        // 6️⃣ Update global game data
        Debug.Log("[GAME] Updating game settings...");
        string gameUpdateJson = "{\"game_settings\":{\"difficulty\":\"hard\",\"max_players\":10,\"matchmaking_enabled\":true},\"last_updated\":\"" + DateTime.UtcNow.ToString("o") + "\"}";
        yield return StartCoroutine(UpdateGameDataCoroutine(gameUpdateJson, (response) => {
            if (response.success) {
                Debug.Log($"[GAME] {response.message} at {response.updated_at}\n");
            }
        }));

        // 7️⃣ Get player-specific data for host
        Debug.Log("[HOST] Loading host player data...");
        yield return StartCoroutine(GetPlayerDataCoroutine(players["host"].Token, (response) => {
            if (response.success) {
                var playerData = JsonUtility.FromJson<PlayerData>(response.data_json);
                Debug.Log($"[HOST] {response.player_name} - Level: {playerData.level}, Rank: {playerData.rank}\n");
            }
        }));

        // 8️⃣ Update host player data
        Debug.Log("[HOST] Updating host progress...");
        string hostUpdateJson = "{\"level\":15,\"rank\":\"platinum\",\"last_played\":\"" + DateTime.UtcNow.ToString("o") + "\",\"matchmaking_status\":\"ready\"}";
        yield return StartCoroutine(UpdatePlayerDataCoroutine(players["host"].Token, hostUpdateJson, (response) => {
            if (response.success) {
                Debug.Log($"[HOST] {response.message} at {response.updated_at}\n");
            }
        }));

        // 9️⃣ Get server time
        Debug.Log("[SERVER] Getting server time...");
        yield return StartCoroutine(GetServerTimeCoroutine(0, (response) => {
            if (response.success) {
                Debug.Log($"[SERVER] Server time (UTC): {response.utc}");
                Debug.Log($"[SERVER] Timestamp: {response.timestamp}");
                Debug.Log($"[SERVER] Readable: {response.readable}\n");
            } else {
                Debug.Log("[SERVER] Failed to get server time\n");
            }
        }));

        // 9️⃣1️⃣ Get server time with +1 hour offset
        Debug.Log("[SERVER] Getting server time with +1 hour offset...");
        yield return StartCoroutine(GetServerTimeCoroutine(1, (response) => {
            if (response.success) {
                Debug.Log($"[SERVER] Server time (+1h): {response.utc}");
                Debug.Log($"[SERVER] Timestamp: {response.timestamp}");
                Debug.Log($"[SERVER] Readable: {response.readable}");
                if (response.offset != null) {
                    Debug.Log($"[SERVER] Offset: {response.offset.offset_hours}h ({response.offset.offset_string})");
                    Debug.Log($"[SERVER] Original UTC: {response.offset.original_utc}\n");
                }
            } else {
                Debug.Log("[SERVER] Failed to get server time with +1 offset\n");
            }
        }));

        // 9️⃣2️⃣ Get server time with -2 hours offset
        Debug.Log("[SERVER] Getting server time with -2 hours offset...");
        yield return StartCoroutine(GetServerTimeCoroutine(-2, (response) => {
            if (response.success) {
                Debug.Log($"[SERVER] Server time (-2h): {response.utc}");
                Debug.Log($"[SERVER] Timestamp: {response.timestamp}");
                Debug.Log($"[SERVER] Readable: {response.readable}");
                if (response.offset != null) {
                    Debug.Log($"[SERVER] Offset: {response.offset.offset_hours}h ({response.offset.offset_string})");
                    Debug.Log($"[SERVER] Original UTC: {response.offset.original_utc}\n");
                }
            } else {
                Debug.Log("[SERVER] Failed to get server time with -2 offset\n");
            }
        }));

        // 🔟 Create a traditional room (for comparison)
        Debug.Log("[ROOM] Creating a traditional game room...");
        yield return StartCoroutine(CreateRoomCoroutine("Traditional Room", "test123", 4, (response) => {
            if (response.success) {
                string roomId = response.room_id;
                Debug.Log($"[ROOM] Created room: ID={roomId}, Name={response.room_name}, Is Host={response.is_host}\n");
            }
        }));

        yield return new WaitForSeconds(1f);

        // 1️⃣1️⃣ List all available rooms
        Debug.Log("[ROOM] Fetching available rooms...");
        yield return StartCoroutine(ListRoomsCoroutine((response) => {
            if (response.success) {
                Debug.Log($"[ROOM] Found {response.rooms.Length} room(s):");
                foreach (var room in response.rooms) {
                    Debug.Log($" - ID: {room.room_id}, Name: {room.room_name}, Players: {room.current_players}/{room.max_players}");
                }
            }
        }));
        yield return new WaitForSeconds(1f);

        // 1️⃣2️⃣ Leave traditional room (cleanup)
        Debug.Log("[ROOM] Leaving traditional game room...");
        yield return StartCoroutine(LeaveRoomCoroutine((response) => {
            if (response.success) {
                Debug.Log($"[ROOM] {response.error ?? "Success"}");
            }
        }));

        yield return StartCoroutine(LogoutPlayerCoroutine(players["host"].Token, (response) => {
            if (response.success) {
                Debug.Log($"[ROOM] {response.message}");
            }
        }));

        yield return new WaitForSeconds(1f);

        // ==================== MATCHMAKING DEMO ====================

        // 2️⃣4️⃣ List Matchmaking Lobbies
        Debug.Log("[MATCHMAKING] Listing available matchmaking lobbies...");
        yield return StartCoroutine(ListMatchmakingCoroutine((response) => {
            if (response.success) {
                Debug.Log($"[MATCHMAKING] Found {response.lobbies.Length} lobby(ies):");
                foreach (var lobby in response.lobbies) {
                    Debug.Log($" - ID: {lobby.matchmaking_id}, Host: {lobby.host_name}, Players: {lobby.current_players}/{lobby.max_players}, Strict: {lobby.strict_full}");
                }
            }
        }));
        yield return new WaitForSeconds(1f);

        // 2️⃣5️⃣ Create Matchmaking Lobby
        Debug.Log("[MATCHMAKING] Host creating new matchmaking lobby...");
        string matchmakingExtraJson = "{\"minLevel\":5,\"rank\":\"silver\",\"gameMode\":\"competitive\"}";
        string matchmakingId = ""; // Declare outside callback to fix scope
        yield return StartCoroutine(CreateMatchmakingCoroutine(4, true, false, matchmakingExtraJson, (response) => {
            if (response.success) {
                matchmakingId = response.matchmaking_id; // Assign to outer variable
                Debug.Log($"[MATCHMAKING] Lobby created: ID={matchmakingId}, Max Players={response.max_players}, Host={response.is_host}");
                Debug.Log($"[MATCHMAKING] Settings: Strict Full={response.strict_full}, Join by Requests={response.join_by_requests}\n");
            }
        }));
        yield return new WaitForSeconds(1f);

        // 2️⃣6️⃣ Request to Join Matchmaking (Player 1)
        Debug.Log("[MATCHMAKING] Player1 requesting to join matchmaking...");
        yield return StartCoroutine(RequestJoinMatchmakingCoroutine("player1", matchmakingId, (response) => {
            if (response.success) {
                Debug.Log($"[MATCHMAKING] Join request sent: ID={response.request_id}, Message={response.message}\n");
            }
        }));
        yield return new WaitForSeconds(1f);

        // 2️⃣7️⃣ Respond to Join Request (Host approves)
        Debug.Log("[MATCHMAKING] Host responding to join request...");
        yield return StartCoroutine(RespondToRequestCoroutine(matchmakingId, "approve", (response) => {
            if (response.success) {
                Debug.Log($"[MATCHMAKING] Response: {response.message}, Action={response.action}, Request ID={response.request_id}\n");
            }
        }));
        yield return new WaitForSeconds(1f);

        // 2️⃣8️⃣ Check Join Request Status (Player 1)
        Debug.Log("[MATCHMAKING] Player1 checking join request status...");
        yield return StartCoroutine(CheckRequestStatusCoroutine("player1", matchmakingId, (response) => {
            if (response.success) {
                var req = response.request;
                Debug.Log($"[MATCHMAKING] Request Status: {req.status}, Responded by: {req.responder_name} at {req.responded_at}");
            }
        }));
        yield return new WaitForSeconds(1f);

        // 2️⃣9️⃣ Get Current Matchmaking Status (Host)
        Debug.Log("[MATCHMAKING] Host checking current matchmaking status...");
        yield return StartCoroutine(GetCurrentMatchmakingStatusCoroutine((response) => {
            if (response.success && response.in_matchmaking) {
                var mm = response.matchmaking;
                Debug.Log($"[MATCHMAKING] Host Status: In Lobby={response.in_matchmaking}, Is Host={mm.is_host}");
                Debug.Log($"[MATCHMAKING] Lobby: {mm.matchmaking_id}, Players: {mm.current_players}/{mm.max_players}");
                Debug.Log($"[MATCHMAKING] Settings: Strict={mm.strict_full}, Approval={mm.join_by_requests}, Started={mm.is_started}");
            }
        }));
        yield return new WaitForSeconds(1f);

        // 3️⃣0️⃣ Join Matchmaking Directly (Player 2 - lobby allows direct join)
        Debug.Log("[MATCHMAKING] Player2 joining directly (no approval required for this demo)...");
        yield return StartCoroutine(JoinMatchmakingCoroutine("player2", matchmakingId, (response) => {
            if (response.success) {
                Debug.Log($"[MATCHMAKING] {response.message}, Lobby ID={response.matchmaking_id}\n");
            }
        }));
        yield return new WaitForSeconds(1f);

        // 3️⃣1️⃣ Leave Matchmaking (Player 1 leaves to rejoin)
        Debug.Log("[MATCHMAKING] Player1 leaving matchmaking to test rejoin...");
        yield return StartCoroutine(LeaveMatchmakingCoroutine("player1", (response) => {
            if (response.success) {
                Debug.Log($"[MATCHMAKING] {response.message}\n");
            } else {
                Debug.Log($"[MATCHMAKING] Note: Player1 was not in matchmaking lobby\n");
            }
        }));
        yield return new WaitForSeconds(1f);

        // 3️⃣2️⃣ List Matchmaking Players (Host view)
        Debug.Log("[MATCHMAKING] Host listing players in matchmaking lobby...");
        yield return StartCoroutine(GetMatchmakingPlayersCoroutine((response) => {
            if (response.success) {
                Debug.Log($"[MATCHMAKING] Players in lobby ({response.players.Length}):");
                foreach (var player in response.players) {
                    Debug.Log($" - {player.player_name} (ID: {player.player_id}, Host: {player.is_host}, Status: {player.status}, Heartbeat: {player.seconds_since_heartbeat}s ago)");
                }
            }
        }));
        yield return new WaitForSeconds(1f);

        // 3️⃣3️⃣ Send Matchmaking Heartbeat (All players)
        Debug.Log("[MATCHMAKING] All players sending heartbeats...");
        foreach (var kvp in players)
        {
            if (kvp.Key != "player1") // Player1 left
            {
                yield return StartCoroutine(SendMatchmakingHeartbeatCoroutine(kvp.Value.Token, (response) => {
                    if (response.success) {
                        Debug.Log($"[MATCHMAKING] {kvp.Value.Name} heartbeat: ok");
                    } else {
                        Debug.Log($"[MATCHMAKING] {kvp.Value.Name} heartbeat error: {response.error}");
                    }
                }));
            }
        }
        yield return new WaitForSeconds(1f);

        // Player1 rejoins
        Debug.Log("[MATCHMAKING] Player1 rejoining matchmaking...");
        yield return StartCoroutine(JoinMatchmakingCoroutine("player1", matchmakingId, (response) => {
            if (response.success) {
                Debug.Log($"[MATCHMAKING] {response.message}\n");
            }
        }));
        yield return new WaitForSeconds(1f);

        // 3️⃣4️⃣ Remove Matchmaking Lobby (Host removes - cleanup)
        Debug.Log("[MATCHMAKING] Host removing matchmaking lobby (cleanup test)...");
        yield return StartCoroutine(RemoveMatchmakingCoroutine((response) => {
            if (response.success) {
                Debug.Log($"[MATCHMAKING] Success\n");
            } else {
                Debug.Log($"[MATCHMAKING] Error: {response.error}\n");
            }
        }));
        yield return new WaitForSeconds(1f);

        // Create new lobby for game start demo
        Debug.Log("[MATCHMAKING] Creating new lobby for game start demo...");
        string newMatchmakingId = ""; // Declare outside callback to fix scope
        yield return StartCoroutine(CreateMatchmakingCoroutine(3, false, false, "{}", (response) => {
            if (response.success) {
                newMatchmakingId = response.matchmaking_id; // Assign to outer variable
                Debug.Log($"[MATCHMAKING] New lobby created: {newMatchmakingId}\n");
            }
        }));
        yield return new WaitForSeconds(1f);

        // All players join new lobby
        Debug.Log("[MATCHMAKING] All players joining new lobby...");
        foreach (var kvp in players)
        {
            if (kvp.Key != "host") // Host is already in
            {
                yield return StartCoroutine(JoinMatchmakingCoroutine(kvp.Key, newMatchmakingId, (response) => {
                    if (response.success) {
                        Debug.Log($"[MATCHMAKING] {kvp.Value.Name}: {response.message}");
                    } else {
                        Debug.Log($"[MATCHMAKING] {kvp.Value.Name}: Join error - {response.error}");
                    }
                }));
            }
        }
        yield return new WaitForSeconds(2f);

        // 3️⃣5️⃣ Start Game from Matchmaking (Host starts game)
        Debug.Log("[MATCHMAKING] Host starting game from matchmaking lobby...");
        yield return StartCoroutine(StartMatchmakingCoroutine((response) => {
            if (response.success) {
                Debug.Log($"[MATCHMAKING] {response.message}");
                Debug.Log($"[MATCHMAKING] Game Room Created: ID={response.room_id}, Name={response.room_name}");
                Debug.Log($"[MATCHMAKING] Players Transferred: {response.players_transferred}\n");
            }
        }));
        yield return new WaitForSeconds(1f);

        // ==================== POST-MATCHMAKING GAME ROOM DEMO ====================

        // Now the host is also a room host - demonstrate room functionality
        Debug.Log("[POST-MATCHMAKING] Demonstrating room functionality with matchmaking host...");

        // 1️⃣2️⃣ List all available rooms (should include the new game room)
        Debug.Log("[ROOM] Fetching available rooms after matchmaking...");
        yield return StartCoroutine(ListRoomsCoroutine((response) => {
            if (response.success) {
                Debug.Log($"[ROOM] Found {response.rooms.Length} room(s):");
                foreach (var room in response.rooms) {
                    Debug.Log($" - ID: {room.room_id}, Name: {room.room_name}, Players: {room.current_players}/{room.max_players}");
                }
            }
        }));
        yield return new WaitForSeconds(1f);

        // 1️⃣3️⃣ List players in the new game room
        Debug.Log("[ROOM] Fetching players in the new game room...");
        yield return StartCoroutine(ListRoomPlayersCoroutine((response) => {
            if (response.success) {
                Debug.Log($"[ROOM] Players in new room ({response.players.Length}):");
                foreach (var player in response.players) {
                    Debug.Log($" - {player.player_name} (ID: {player.player_id}, Host: {player.is_host}, Online: {player.is_online})");
                }
            }
        }));
        yield return new WaitForSeconds(1f);

        // 1️⃣5️⃣ Submit actions from different players
        Debug.Log("[ACTION] Players submitting actions in the new game room...");
        foreach (var kvp in players)
        {
            string actionJson = "{\"player_id\":\"" + kvp.Value.Id + "\",\"ready\":true,\"timestamp\":\"" + DateTime.UtcNow.ToString("o") + "\"}";
            yield return StartCoroutine(SubmitActionCoroutine(kvp.Value.Token, "player_ready", actionJson, (response) => {
                if (response.success) {
                    Debug.Log($"[ACTION] {kvp.Value.Name} submitted ready action: {response.action_id}");
                } else {
                    Debug.Log($"[ACTION] {kvp.Value.Name}: Action submit error - {response.error}");
                }
            }));
        }
        yield return new WaitForSeconds(1f);

        // 1️⃣6️⃣ Check pending actions
        Debug.Log("[ACTION] Checking for pending actions...");
        yield return StartCoroutine(GetPendingActionsCoroutine((response) => {
            if (response.success && response.actions.Length > 0) {
                Debug.Log($"[ACTION] Found {response.actions.Length} pending actions:");
                foreach (var pendingAction in response.actions) {
                    Debug.Log($"[ACTION] - {pendingAction.player_name}: {pendingAction.action_type} at {pendingAction.created_at}");
                }
            }
        }));
        yield return new WaitForSeconds(1f);

        // 2️⃣0️⃣ Send game start update to all players
        Debug.Log("[UPDATE] Host sending game start update to all players...");
        string updateJson = "{\"game_mode\":\"competitive\",\"start_time\":\"" + DateTime.UtcNow.ToString("o") + "\"}";
        yield return StartCoroutine(SendUpdateCoroutine("all", "game_start", updateJson, (response) => {
            if (response.success) {
                Debug.Log($"[UPDATE] Game start update sent to {response.updates_sent} players\n");
            }
        }));
        yield return new WaitForSeconds(1f);

        // 2️⃣2️⃣ Poll for game updates
        Debug.Log("[UPDATE] Players polling for game updates...");
        foreach (var kvp in players)
        {
            yield return StartCoroutine(PollUpdatesCoroutine(kvp.Value.Token, "", (response) => {
                if (response.success && response.updates.Length > 0) {
                    Debug.Log($"[UPDATE] {kvp.Value.Name} received {response.updates.Length} update(s):");
                    foreach (var update in response.updates) {
                        Debug.Log($"[UPDATE]   - {update.type} from {update.from_player_id} at {update.created_at}");
                    }
                } else {
                    Debug.Log($"[UPDATE] {kvp.Value.Name}: No new updates");
                }
            }));
        }
        yield return new WaitForSeconds(1f);

        // 2️⃣3️⃣ Get current room information for all players
        Debug.Log("[ROOM] All players checking current room status...");
        foreach (var kvp in players)
        {
            yield return StartCoroutine(GetCurrentRoomStatusCoroutine(kvp.Value.Token, (response) => {
                if (response.success && response.in_room && response.room != null) {
                    var room = response.room;
                    Debug.Log($"[ROOM] {kvp.Value.Name}: In '{room.room_name}' (Players: {room.current_players}/{room.max_players}, Host: {room.is_host})");
                } else {
                    Debug.Log($"[ROOM] {kvp.Value.Name}: Not in any room");
                }
            }));
        }
        yield return new WaitForSeconds(1f);

        // 2️⃣4️⃣ Leave room (all players)
        Debug.Log("[ROOM] All players leaving the game room...");
        foreach (var kvp in players)
        {
            // Send heartbeat before leaving room
            yield return StartCoroutine(SendRoomHeartbeatCoroutine(kvp.Value.Token, (response) => {
                if (response.success) {
                    Debug.Log($"[ROOM] {kvp.Value.Name}: Heartbeat sent: ok");
                }
            }));

            yield return StartCoroutine(LeaveRoomCoroutine((response) => {
                if (response.success) {
                    Debug.Log($"[ROOM] {kvp.Value.Name}: {response.error ?? "Success"}");
                }
            }));

            // Logout after leaving room
            yield return StartCoroutine(LogoutPlayerCoroutine(kvp.Value.Token, (response) => {
                if (response.success) {
                    Debug.Log($"[ROOM] {kvp.Value.Name}: {response.message}");
                }
            }));
        }
        yield return new WaitForSeconds(1f);

        // 2️⃣5️⃣ Leaderboards
        Debug.Log("[LEADERBOARD] Testing leaderboard functionality...");
        
        // Sort by level only
        Debug.Log("[LEADERBOARD] Getting leaderboard sorted by level...");
        yield return StartCoroutine(GetLeaderboardCoroutine(new string[] { "level" }, 10, (response) => {
            if (response.success) {
                Debug.Log($"[LEADERBOARD] Success! Total players: {response.total}");
                Debug.Log($"[LEADERBOARD] Sorted by: {string.Join(", ", response.sort_by)}");
                Debug.Log("[LEADERBOARD] Top players:");
                for (int i = 0; i < Mathf.Min(5, response.leaderboard.Length); i++) {
                    var player = response.leaderboard[i];
                    Debug.Log($"[LEADERBOARD]   Rank {player.rank}: {player.player_name} (ID: {player.player_id})");
                    var playerData = JsonUtility.FromJson<PlayerData>(player.player_data_json);
                    Debug.Log($"[LEADERBOARD]     Level: {playerData.level}");
                    Debug.Log($"[LEADERBOARD]     Rank: {playerData.rank}");
                }
            } else {
                Debug.Log("[LEADERBOARD] Failed to get leaderboard by level");
            }
        }));
        yield return new WaitForSeconds(1f);

        // Sort by level then score
        Debug.Log("[LEADERBOARD] Getting leaderboard sorted by level, then score...");
        yield return StartCoroutine(GetLeaderboardCoroutine(new string[] { "level", "score" }, 10, (response) => {
            if (response.success) {
                Debug.Log($"[LEADERBOARD] Success! Total players: {response.total}");
                Debug.Log($"[LEADERBOARD] Sorted by: {string.Join(", ", response.sort_by)}");
                Debug.Log("[LEADERBOARD] Top players:");
                for (int i = 0; i < Mathf.Min(5, response.leaderboard.Length); i++) {
                    var player = response.leaderboard[i];
                    Debug.Log($"[LEADERBOARD]   Rank {player.rank}: {player.player_name} (ID: {player.player_id})");
                    var playerData = JsonUtility.FromJson<PlayerData>(player.player_data_json);
                    Debug.Log($"[LEADERBOARD]     Level: {playerData.level}");
                    Debug.Log($"[LEADERBOARD]     Score: {playerData.score}");
                    if (!string.IsNullOrEmpty(playerData.inventory)) {
                        Debug.Log($"[LEADERBOARD]     Inventory: {playerData.inventory}");
                    }
                }
            } else {
                Debug.Log("[LEADERBOARD] Failed to get leaderboard by level and score");
            }
        }));
        yield return new WaitForSeconds(1f);

        Debug.Log("=== Complete Matchmaking & Game Demo ===");
        Debug.Log("✅ Multiple players registered and authenticated");
        Debug.Log("✅ Matchmaking lobby created with custom settings");
        Debug.Log("✅ Join requests and approval system demonstrated");
        Debug.Log("✅ Direct join functionality tested");
        Debug.Log("✅ Player management and heartbeats working");
        Debug.Log("✅ Game started from matchmaking lobby");
        Debug.Log("✅ Host transitioned from matchmaking to room host");
        Debug.Log("✅ Full game room functionality demonstrated");
        Debug.Log("✅ Leaderboard system with multiple sorting options tested");
    }

    #region Coroutines for SDK Calls
    
    /// <summary>
    /// Coroutine wrapper for player registration
    /// Handles async SDK calls with proper token management
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the RegisterPlayer SDK call for use in Unity coroutines.
    /// Clears the player token before registration and waits for completion.
    /// Used throughout the demo for consistent async handling patterns.
    /// </remarks>
    /// <param name="name">Player display name</param>
    /// <param name="playerDataJson">Initial player data as JSON string</param>
    /// <param name="callback">Response callback with registration result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator RegisterPlayerCoroutine(string name, string playerDataJson, System.Action<RegisterPlayerResponse> callback)
    {
        sdk.SetGamePlayerToken(""); // Clear token for registration
        bool completed = false;
        sdk.RegisterPlayer(name, playerDataJson, (response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for player authentication
    /// Handles login with proper token management
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the LoginPlayer SDK call for use in Unity coroutines.
    /// Sets the player token before authentication and waits for completion.
    /// Demonstrates proper authentication workflow in multiplayer games.
    /// </remarks>
    /// <param name="playerToken">Player authentication token</param>
    /// <param name="callback">Response callback with login result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator AuthenticatePlayerCoroutine(string playerToken, System.Action<LoginResponse> callback)
    {
        sdk.SetGamePlayerToken(playerToken);
        bool completed = false;
        sdk.LoginPlayer((response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for listing all players
    /// Administrative function for player management
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the ListPlayers SDK call for administrative purposes.
    /// Useful for server browsers, admin dashboards, or player discovery systems.
    /// Returns comprehensive player information including status and activity.
    /// </remarks>
    /// <param name="callback">Response callback with player list result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator ListPlayersCoroutine(System.Action<ListPlayersResponse> callback)
    {
        bool completed = false;
        sdk.ListPlayers((response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for retrieving global game data
    /// Accesses server-wide game settings and configuration
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the GetGameData SDK call for accessing global game data.
    /// Useful for loading game settings, configuration, or server-wide variables.
    /// The data is returned as JSON for flexible parsing and usage.
    /// </remarks>
    /// <param name="callback">Response callback with game data result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator GetGameDataCoroutine(System.Action<GameDataResponse> callback)
    {
        bool completed = false;
        sdk.GetGameData((response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for updating global game data
    /// Modifies server-wide game settings and configuration
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the UpdateGameData SDK call for modifying global game data.
    /// Useful for updating game settings, server configuration, or global variables.
    /// The update is applied immediately and affects all connected players.
    /// </remarks>
    /// <param name="dataJson">Game data as JSON string to update</param>
    /// <param name="callback">Response callback with update result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator UpdateGameDataCoroutine(string dataJson, System.Action<UpdateDataResponse> callback)
    {
        bool completed = false;
        sdk.UpdateGameData(dataJson, (response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for retrieving player-specific data
    /// Accesses individual player's stored information
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the GetPlayerData SDK call for accessing player data.
    /// Useful for loading player progress, inventory, stats, or preferences.
    /// Requires proper player authentication token for data access.
    /// </remarks>
    /// <param name="playerToken">Player authentication token</param>
    /// <param name="callback">Response callback with player data result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator GetPlayerDataCoroutine(string playerToken, System.Action<PlayerDataResponse> callback)
    {
        sdk.SetGamePlayerToken(playerToken);
        bool completed = false;
        sdk.GetPlayerData((response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for updating player-specific data
    /// Modifies individual player's stored information
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the UpdatePlayerData SDK call for modifying player data.
    /// Useful for saving player progress, updating inventory, or modifying stats.
    /// Requires proper player authentication token for data modification.
    /// </remarks>
    /// <param name="playerToken">Player authentication token</param>
    /// <param name="dataJson">Player data as JSON string to update</param>
    /// <param name="callback">Response callback with update result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator UpdatePlayerDataCoroutine(string playerToken, string dataJson, System.Action<UpdateDataResponse> callback)
    {
        sdk.SetGamePlayerToken(playerToken);
        bool completed = false;
        sdk.UpdatePlayerData(dataJson, (response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for server time retrieval
    /// Synchronizes client time with server timestamps
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the GetServerTime SDK call for time synchronization.
    /// Useful for event timing, anti-cheat measures, or coordinated game events.
    /// Supports UTC offset for timezone-specific time calculations.
    /// </remarks>
    /// <param name="utcOffset">UTC offset in hours for timezone adjustment</param>
    /// <param name="callback">Response callback with time information</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator GetServerTimeCoroutine(int utcOffset, System.Action<TimeResponse> callback)
    {
        bool completed = false;
        sdk.GetServerTime((response) => {
            callback?.Invoke(response);
            completed = true;
        }, utcOffset);
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for traditional room creation
    /// Creates persistent game rooms for multiplayer sessions
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the CreateRoom SDK call for traditional room management.
    /// Useful for creating custom game rooms, private matches, or persistent sessions.
    /// The creating player becomes the room host with management privileges.
    /// </remarks>
    /// <param name="roomName">Display name for the room</param>
    /// <param name="password">Room password (empty for public rooms)</param>
    /// <param name="maxPlayers">Maximum player capacity</param>
    /// <param name="callback">Response callback with room creation result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator CreateRoomCoroutine(string roomName, string password, int maxPlayers, System.Action<CreateRoomResponse> callback)
    {
        bool completed = false;
        sdk.CreateRoom(roomName, password, maxPlayers, (response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for room listing
    /// Retrieves all available game rooms
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the ListRooms SDK call for room discovery.
    /// Useful for server browsers, lobby systems, or room discovery interfaces.
    /// Returns room details including player count and password protection status.
    /// </remarks>
    /// <param name="callback">Response callback with room list result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator ListRoomsCoroutine(System.Action<ListRoomsResponse> callback)
    {
        bool completed = false;
        sdk.ListRooms((response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for leaving rooms
    /// Exits current room and updates player status
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the LeaveRoom SDK call for room exit.
    /// Useful for cleanup, disconnecting, or transitioning between rooms.
    /// Properly updates player status and notifies other room members.
    /// </remarks>
    /// <param name="callback">Response callback with leave result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator LeaveRoomCoroutine(System.Action<BaseResponse> callback)
    {
        bool completed = false;
        sdk.LeaveRoom((response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for player logout
    /// Terminates player session and invalidates tokens
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the LogoutPlayer SDK call for session termination.
    /// Useful for cleanup, disconnecting, or security measures.
    /// Invalidates player tokens and updates last logout timestamp.
    /// </remarks>
    /// <param name="playerToken">Player authentication token</param>
    /// <param name="callback">Response callback with logout result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator LogoutPlayerCoroutine(string playerToken, System.Action<LogoutResponse> callback)
    {
        sdk.SetGamePlayerToken(playerToken);
        bool completed = false;
        sdk.LogoutPlayer((response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for room player listing
    /// Retrieves all players in current room
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the ListRoomPlayers SDK call for room player management.
    /// Useful for displaying player lists, checking room occupancy, or managing game state.
    /// Returns player details including host status and online status.
    /// </remarks>
    /// <param name="callback">Response callback with player list result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator ListRoomPlayersCoroutine(System.Action<ListRoomPlayersResponse> callback)
    {
        bool completed = false;
        sdk.ListRoomPlayers((response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for room heartbeat
    /// Maintains connection and prevents timeout
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the SendRoomHeartbeat SDK call for connection maintenance.
    /// Should be called periodically (every 30-60 seconds) while in a room.
    /// Prevents automatic timeout and maintains player's room connection.
    /// </remarks>
    /// <param name="playerToken">Player authentication token</param>
    /// <param name="callback">Response callback with heartbeat result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator SendRoomHeartbeatCoroutine(string playerToken, System.Action<BaseResponse> callback)
    {
        sdk.SetGamePlayerToken(playerToken);
        bool completed = false;
        sdk.SendRoomHeartbeat((response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for current room status
    /// Retrieves comprehensive room state information
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the GetCurrentRoomStatus SDK call for room state management.
    /// Useful for checking room state, player status, and pending actions.
    /// Returns complete room information including player lists and activity.
    /// </remarks>
    /// <param name="playerToken">Player authentication token</param>
    /// <param name="callback">Response callback with room status result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator GetCurrentRoomStatusCoroutine(string playerToken, System.Action<CurrentRoomStatusResponse> callback)
    {
        sdk.SetGamePlayerToken(playerToken);
        bool completed = false;
        sdk.GetCurrentRoomStatus((response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for action submission
    /// Submits player actions for processing by other players
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the SubmitAction SDK call for action management.
    /// Useful for game events, player moves, or multiplayer interactions.
    /// Actions are processed asynchronously and can be tracked by ID.
    /// </remarks>
    /// <param name="playerToken">Player authentication token</param>
    /// <param name="actionType">Type of action being submitted</param>
    /// <param name="requestDataJson">Action data as JSON string</param>
    /// <param name="callback">Response callback with action submission result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator SubmitActionCoroutine(string playerToken, string actionType, string requestDataJson, System.Action<SubmitActionResponse> callback)
    {
        sdk.SetGamePlayerToken(playerToken);
        bool completed = false;
        sdk.SubmitAction(actionType, requestDataJson, (response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for pending actions retrieval
    /// Gets actions awaiting processing by current player
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the GetPendingActions SDK call for action processing.
    /// Useful for processing actions submitted by other players.
    /// Call periodically to check for new actions that need completion.
    /// </remarks>
    /// <param name="callback">Response callback with pending actions result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator GetPendingActionsCoroutine(System.Action<GetPendingActionsResponse> callback)
    {
        bool completed = false;
        sdk.GetPendingActions((response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for update sending
    /// Sends real-time updates to room players
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the SendUpdate SDK call for real-time synchronization.
    /// Useful for game state updates, notifications, or events.
    /// Supports targeting specific players or broadcasting to all players.
    /// </remarks>
    /// <param name="targetPlayerIds">Target players ("all" or JSON array)</param>
    /// <param name="type">Update type identifier</param>
    /// <param name="dataJson">Update data as JSON string</param>
    /// <param name="callback">Response callback with update result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator SendUpdateCoroutine(string targetPlayerIds, string type, string dataJson, System.Action<SendUpdateResponse> callback)
    {
        bool completed = false;
        sdk.SendUpdate(targetPlayerIds, type, dataJson, (response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for update polling
    /// Retrieves updates sent by other players
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the PollUpdates SDK call for update retrieval.
    /// Useful for receiving real-time updates from other players.
    /// Supports incremental polling with last update ID tracking.
    /// </remarks>
    /// <param name="playerToken">Player authentication token</param>
    /// <param name="lastUpdateId">Last update ID for incremental polling</param>
    /// <param name="callback">Response callback with updates result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator PollUpdatesCoroutine(string playerToken, string lastUpdateId, System.Action<PollUpdatesResponse> callback)
    {
        sdk.SetGamePlayerToken(playerToken);
        bool completed = false;
        sdk.PollUpdates(lastUpdateId, (response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for matchmaking lobby listing
    /// Retrieves all available matchmaking lobbies
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the ListMatchmaking SDK call for lobby discovery.
    /// Useful for lobby browsers, server discovery, or matchmaking interfaces.
    /// Returns lobby details including player count and settings.
    /// </remarks>
    /// <param name="callback">Response callback with lobby list result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator ListMatchmakingCoroutine(System.Action<ListMatchmakingResponse> callback)
    {
        bool completed = false;
        sdk.ListMatchmaking((response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for matchmaking lobby creation
    /// Creates new matchmaking lobby with custom settings
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the CreateMatchmaking SDK call for lobby creation.
    /// Useful for creating custom lobbies with specific requirements.
    /// The creating player becomes the lobby host with management privileges.
    /// </remarks>
    /// <param name="maxPlayers">Maximum player capacity</param>
    /// <param name="strictFull">Whether lobby closes when full</param>
    /// <param name="joinByRequests">Whether join requires approval</param>
    /// <param name="extraJsonString">Additional lobby settings as JSON</param>
    /// <param name="callback">Response callback with lobby creation result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator CreateMatchmakingCoroutine(int maxPlayers, bool strictFull, bool joinByRequests, string extraJsonString, System.Action<CreateMatchmakingResponse> callback)
    {
        bool completed = false;
        sdk.CreateMatchmaking(maxPlayers, strictFull, joinByRequests, extraJsonString, (response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for matchmaking join request
    /// Requests to join lobby with approval workflow
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the RequestJoinMatchmaking SDK call for join requests.
    /// Useful for lobbies that require host approval for joining.
    /// The host must approve or reject the request before entry.
    /// </remarks>
    /// <param name="playerKey">Player key from players dictionary</param>
    /// <param name="matchmakingId">Lobby identifier to join</param>
    /// <param name="callback">Response callback with join request result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator RequestJoinMatchmakingCoroutine(string playerKey, string matchmakingId, System.Action<JoinRequestResponse> callback)
    {
        sdk.SetGamePlayerToken(players[playerKey].Token);
        bool completed = false;
        sdk.RequestJoinMatchmaking(matchmakingId, (response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for join request response
    /// Host responds to player join requests
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the RespondToRequest SDK call for request management.
    /// Only the lobby host can respond to join requests.
    /// Used to approve or reject player entry to the lobby.
    /// </remarks>
    /// <param name="matchmakingId">Lobby identifier</param>
    /// <param name="action">Response action ("approve" or "reject")</param>
    /// <param name="callback">Response callback with response result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator RespondToRequestCoroutine(string matchmakingId, string action, System.Action<RespondToRequestResponse> callback)
    {
        bool completed = false;
        sdk.RespondToRequest(matchmakingId, action, (response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for join request status check
    /// Tracks the status of submitted join requests
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the CheckRequestStatus SDK call for request tracking.
    /// Useful for monitoring whether join requests were approved or rejected.
    /// Returns detailed request information and processing status.
    /// </remarks>
    /// <param name="playerKey">Player key from players dictionary</param>
    /// <param name="matchmakingId">Lobby identifier</param>
    /// <param name="callback">Response callback with status result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator CheckRequestStatusCoroutine(string playerKey, string matchmakingId, System.Action<CheckRequestStatusResponse> callback)
    {
        sdk.SetGamePlayerToken(players[playerKey].Token);
        bool completed = false;
        sdk.CheckRequestStatus(matchmakingId, (response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for matchmaking player listing
    /// Retrieves all players in current matchmaking lobby
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the GetMatchmakingPlayers SDK call for lobby player management.
    /// Useful for displaying player lists, checking lobby occupancy, or managing lobby state.
    /// Returns player details including host status and activity information.
    /// </remarks>
    /// <param name="callback">Response callback with player list result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator GetMatchmakingPlayersCoroutine(System.Action<GetMatchmakingPlayersResponse> callback)
    {
        bool completed = false;
        sdk.GetMatchmakingPlayers((response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for matchmaking heartbeat
    /// Maintains connection and prevents timeout in lobbies
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the SendMatchmakingHeartbeat SDK call for connection maintenance.
    /// Should be called periodically (every 30-60 seconds) while in a matchmaking lobby.
    /// Prevents automatic timeout and maintains player's lobby connection.
    /// </remarks>
    /// <param name="playerToken">Player authentication token</param>
    /// <param name="callback">Response callback with heartbeat result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator SendMatchmakingHeartbeatCoroutine(string playerToken, System.Action<BaseResponse> callback)
    {
        sdk.SetGamePlayerToken(playerToken);
        bool completed = false;
        sdk.SendMatchmakingHeartbeat((response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for direct matchmaking join
    /// Joins lobby without approval requirement
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the JoinMatchmaking SDK call for direct lobby entry.
    /// Only works for lobbies with joinByRequests set to false.
    /// Useful for public lobbies or instant join functionality.
    /// </remarks>
    /// <param name="playerKey">Player key from players dictionary</param>
    /// <param name="matchmakingId">Lobby identifier to join</param>
    /// <param name="callback">Response callback with join result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator JoinMatchmakingCoroutine(string playerKey, string matchmakingId, System.Action<JoinMatchmakingResponse> callback)
    {
        sdk.SetGamePlayerToken(players[playerKey].Token);
        bool completed = false;
        sdk.JoinMatchmaking(matchmakingId, (response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for matchmaking lobby exit
    /// Leaves current matchmaking lobby
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the LeaveMatchmaking SDK call for lobby exit.
    /// Useful for leaving lobbies, canceling matchmaking, or switching lobbies.
    /// Properly updates player status and notifies lobby members.
    /// </remarks>
    /// <param name="playerKey">Player key from players dictionary</param>
    /// <param name="callback">Response callback with leave result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator LeaveMatchmakingCoroutine(string playerKey, System.Action<LeaveMatchmakingResponse> callback)
    {
        sdk.SetGamePlayerToken(players[playerKey].Token);
        bool completed = false;
        sdk.LeaveMatchmaking((response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for matchmaking lobby removal
    /// Deletes lobby and removes all players (host only)
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the RemoveMatchmaking SDK call for lobby deletion.
    /// Only the lobby host can remove a matchmaking lobby.
    /// Useful for cleanup, canceling matchmaking, or managing lobby lifecycle.
    /// </remarks>
    /// <param name="callback">Response callback with removal result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator RemoveMatchmakingCoroutine(System.Action<BaseResponse> callback)
    {
        bool completed = false;
        sdk.RemoveMatchmaking((response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for game start from matchmaking
    /// Transfers lobby to game room and starts game
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the StartMatchmaking SDK call for game initiation.
    /// Only the lobby host can start a game from matchmaking.
    /// Transfers all lobby players to a new game room for gameplay.
    /// </remarks>
    /// <param name="callback">Response callback with game start result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator StartMatchmakingCoroutine(System.Action<StartMatchmakingResponse> callback)
    {
        bool completed = false;
        sdk.StartMatchmaking((response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for current matchmaking status
    /// Retrieves comprehensive lobby state information
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the GetCurrentMatchmakingStatus SDK call for lobby state.
    /// Useful for checking lobby state, player status, and pending requests.
    /// Returns complete lobby information including settings and activity.
    /// </remarks>
    /// <param name="callback">Response callback with status result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator GetCurrentMatchmakingStatusCoroutine(System.Action<CurrentMatchmakingStatusResponse> callback)
    {
        bool completed = false;
        sdk.GetCurrentMatchmakingStatus((response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    /// <summary>
    /// Coroutine wrapper for leaderboard retrieval
    /// Gets ranked players with configurable sorting
    /// </summary>
    /// <remarks>
    /// This coroutine wraps the GetLeaderboard SDK call for competitive rankings.
    /// Useful for displaying leaderboards, rankings, or competitive stats.
    /// Supports multiple sorting criteria and result limiting.
    /// </remarks>
    /// <param name="sortBy">Array of fields to sort by</param>
    /// <param name="limit">Maximum number of results to return</param>
    /// <param name="callback">Response callback with leaderboard result</param>
    /// <returns>IEnumerator for Unity coroutine execution</returns>
    private IEnumerator GetLeaderboardCoroutine(string[] sortBy, int limit, System.Action<LeaderboardResponse> callback)
    {
        bool completed = false;
        sdk.GetLeaderboard(sortBy, limit, (response) => {
            callback?.Invoke(response);
            completed = true;
        });
        yield return new WaitUntil(() => completed);
    }

    #endregion

    /// <summary>
    /// Player information container for demo management
    /// Stores authentication tokens and player metadata
    /// </summary>
    /// <remarks>
    /// This class stores player information for the demo.
    /// Used to manage multiple player sessions and authentication tokens.
    /// Includes player ID, authentication token, and display name.
    /// </remarks>
    [System.Serializable]
    public class PlayerInfo
    {
        /// <summary>Unique player identifier</summary>
        public string Id;
        
        /// <summary>Player authentication token</summary>
        public string Token;
        
        /// <summary>Player display name</summary>
        public string Name;
    }

    /// <summary>
    /// Player data container for demo purposes
    /// Represents player statistics and progress
    /// </summary>
    /// <remarks>
    /// This class represents player data used in the demo.
    /// Includes level, rank, score, and inventory information.
    /// Used for demonstrating player data management and leaderboards.
    /// </remarks>
    [System.Serializable]
    public class PlayerData
    {
        /// <summary>Player level or experience</summary>
        public int level;
        
        /// <summary>Player rank or tier</summary>
        public string rank;
        
        /// <summary>Player score or points</summary>
        public int score;
        
        /// <summary>Player inventory or items</summary>
        public string inventory;
    }
}
