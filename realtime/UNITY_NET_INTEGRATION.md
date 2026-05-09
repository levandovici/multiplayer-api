# Unity & .NET Realtime Integration

## Namespace Structure

### Unity Namespace
```csharp
namespace Michitai.Multiplayer.Rooms.Realtime
{
    public class RealtimeConnection
    {
        private WebSocket websocket;
        private string token;
        private PlayerInfo playerInfo;
        
        public event Action<string, string, SenderInfo> OnReceive;
        public event Action OnConnected;
        public event Action OnDisconnected;
        
        public struct SenderInfo
        {
            public bool is_host;
            public int game_player_id;
            public string player_name;
        }
        
        public async Task<bool> ConnectAsync(string apiToken, string playerToken)
        {
            // Get realtime token from API
            var tokenResponse = await GetRealtimeToken(apiToken, playerToken);
            if (!tokenResponse.success) return false;
            
            token = tokenResponse.token;
            playerInfo = tokenResponse.player_info;
            
            // Connect to WebSocket
            websocket = new WebSocket($"wss://{tokenResponse.realtime_server.host}:{tokenResponse.realtime_server.port}?token={token}&client=unity");
            
            websocket.OnOpen += () => OnConnected?.Invoke();
            websocket.OnMessage += HandleMessage;
            websocket.OnClose += () => OnDisconnected?.Invoke();
            
            await websocket.Connect();
            return true;
        }
        
        public void Send(string command, string data_json = null, TargetType target = TargetType.All, int[] target_ids = null)
        {
            var message = new {
                type = "send",
                command = command,
                data_json = data_json,
                target = target.ToString()
            };
            
            if (target == TargetType.Specific && target_ids != null)
            {
                message.data_json = data_json;
                // Unity sends target_ids as separate field in data_json
                if (!string.IsNullOrEmpty(data_json))
                {
                    message.data_json = JsonConvert.SerializeObject(new { target_ids });
                }
                else
                {
                    // Parse existing data_json and add target_ids
                    var existingData = JsonConvert.DeserializeObject<Dictionary<string, object>>(data_json) ?? new Dictionary<string, object>();
                    existingData["target_ids"] = target_ids;
                    message.data_json = JsonConvert.SerializeObject(existingData);
                }
            }
            
            websocket.SendText(JsonConvert.SerializeObject(message));
        }
        
        private void HandleMessage(byte[] bytes)
        {
            var message = JsonConvert.DeserializeObject<RealtimeMessage>(Encoding.UTF8.GetString(bytes));
            
            if (message.type == "receive")
            {
                OnReceive?.Invoke(message.command, message.data_json, message.sender);
            }
        }
        
        private async Task<TokenResponse> GetRealtimeToken(string apiToken, string playerToken)
        {
            using var client = new HttpClient();
            var response = await client.GetAsync($"https://api.michitai.com/api/realtime.php/token?api_token={apiToken}&player_token={playerToken}");
            var content = await response.Content.ReadAsStringAsync();
            return JsonConvert.DeserializeObject<TokenResponse>(content);
        }
    }
    
    public enum TargetType
    {
        All,
        Host,
        Others,
        Specific
    }
    
    public class RealtimeMessage
    {
        public string type { get; set; }
        public string command { get; set; }
        public string data_json { get; set; }
        public SenderInfo sender { get; set; }
    }
    
    public class TokenResponse
    {
        public bool success { get; set; }
        public string token { get; set; }
        public PlayerInfo player_info { get; set; }
        public RealtimeServerInfo realtime_server { get; set; }
    }
    
    public class PlayerInfo
    {
        public int player_id { get; set; }
        public string player_name { get; set; }
        public string room_id { get; set; }
        public bool is_host { get; set; }
    }
    
    public class RealtimeServerInfo
    {
        public string host { get; set; }
        public int port { get; set; }
        public string protocol { get; set; }
    }
}
```

### .NET Namespace
```csharp
namespace Michitai.Multiplayer.Rooms.Realtime
{
    public class RealtimeConnection
    {
        private ClientWebSocket websocket;
        private string token;
        private PlayerInfo playerInfo;
        
        public event Func<string, object, SenderInfo, Task> OnReceive;
        public event Func<Task> OnConnected;
        public event Func<Task> OnDisconnected;
        
        public struct SenderInfo
        {
            public bool is_host { get; set; }
            public int game_player_id { get; set; }
            public string player_name { get; set; }
        }
        
        public async Task<bool> ConnectAsync(string apiToken, string playerToken)
        {
            // Get realtime token from API
            var tokenResponse = await GetRealtimeToken(apiToken, playerToken);
            if (!tokenResponse.success) return false;
            
            token = tokenResponse.token;
            playerInfo = tokenResponse.player_info;
            
            // Connect to WebSocket
            websocket = new ClientWebSocket();
            var uri = new Uri($"wss://{tokenResponse.realtime_server.host}:{tokenResponse.realtime_server.port}?token={token}&client=dotnet");
            await websocket.ConnectAsync(uri, CancellationToken.None);
            
            // Start listening for messages
            _ = Task.Run(ListenForMessages);
            
            OnConnected?.Invoke();
            return true;
        }
        
        public async Task SendAsync(string command, object data = null, TargetType target = TargetType.All, int[] targetIds = null)
        {
            var message = new {
                type = "send",
                command = command,
                data = data,
                target = target.ToString()
            };
            
            if (target == TargetType.Specific && targetIds != null)
            {
                message.data = new { data, target_ids = targetIds };
            }
            
            var json = JsonSerializer.Serialize(message);
            var buffer = Encoding.UTF8.GetBytes(json);
            await websocket.SendAsync(new ArraySegment<byte>(buffer), WebSocketMessageType.Text, true, CancellationToken.None);
        }
        
        private async Task ListenForMessages()
        {
            var buffer = new byte[1024 * 4];
            
            while (websocket.State == WebSocketState.Open)
            {
                var result = await websocket.ReceiveAsync(new ArraySegment<byte>(buffer), CancellationToken.None);
                
                if (result.MessageType == WebSocketMessageType.Text)
                {
                    var message = Encoding.UTF8.GetString(buffer, 0, result.Count);
                    var realtimeMessage = JsonSerializer.Deserialize<RealtimeMessage>(message);
                    
                    if (realtimeMessage.type == "receive")
                    {
                        // .NET receives data as object
                        await OnReceive?.Invoke(realtimeMessage.command, realtimeMessage.data, realtimeMessage.sender);
                    }
                }
            }
        }
        
        private async Task<TokenResponse> GetRealtimeToken(string apiToken, string playerToken)
        {
            using var client = new HttpClient();
            var response = await client.GetAsync($"https://api.michitai.com/api/realtime.php/token?api_token={apiToken}&player_token={playerToken}");
            var content = await response.Content.ReadAsStringAsync();
            return JsonSerializer.Deserialize<TokenResponse>(content);
        }
    }
    
    // Same supporting classes as Unity version
    public enum TargetType { All, Host, Others, Specific }
    
    public class RealtimeMessage
    {
        public string type { get; set; }
        public string command { get; set; }
        public object data { get; set; }
        public SenderInfo sender { get; set; }
    }
    
    // ... other classes same as Unity
}
```

## Usage Workflow

### 1. Create Room/Matchmaking with Realtime
```csharp
// Unity
var roomResponse = await CreateRoom(new {
    room_name = "My Game Room",
    max_players = 4,
    realtime = true  // Enable realtime
});

// .NET
var roomResponse = await CreateRoom(new RoomRequest {
    RoomName = "My Game Room",
    MaxPlayers = 4,
    Realtime = true
});
```

### 2. Get Realtime Token
```csharp
// Unity
var realtime = new RealtimeConnection();
var connected = await realtime.ConnectAsync(apiToken, playerToken);

// .NET
var realtime = new RealtimeConnection();
var connected = await realtime.ConnectAsync(apiToken, playerToken);
```

### 3. Handle Events
```csharp
// Unity
realtime.OnReceive += (command, data_json, sender) => {
    if (command == "move_player")
    {
        var position = JsonConvert.DeserializeObject<Vector2>(data_json);
        // Update player position
        Debug.Log($"Player {sender.player_name} moved to {position}");
    }
};

realtime.OnConnected += () => {
    Debug.Log("Connected to realtime server");
};

realtime.OnDisconnected += () => {
    Debug.Log("Disconnected from realtime server");
};
```

### 4. Send Messages
```csharp
// Send to all players
realtime.Send("spawn_effect", "{\"effect_id\": \"explosion\", \"position\": \"100,200\"}");

// Send to host only
realtime.Send("request_host_action", "{\"action\": \"pause_game\"}", TargetType.Host);

// Send to specific players
realtime.Send("private_message", "{\"message\": \"Hello!\"}", TargetType.Specific, new int[] { 123, 456 });

// Send to all others (not self)
realtime.Send("player_joined", "{\"player_name\": \"NewPlayer\"}", TargetType.Others);
```

## Message Protocol

### Send Message Format
```json
{
  "type": "send",
  "command": "move_player",
  "data": {
    "data_json": "{\"x\": 100, \"y\": 200}",
    "target_ids": [123, 456]  // Only for TargetType.Specific
  },
  "target": "all"  // "all", "host", "others", "specific"
}
```

### Receive Message Format
```json
{
  "type": "receive",
  "command": "move_player",
  "data": {
    "data_json": "{\"x\": 100, \"y\": 200}"
  },
  "sender": {
    "is_host": false,
    "game_player_id": 123,
    "player_name": "Player1"
  }
}
```

## API Endpoints

### Get Realtime Token
```
POST /api/realtime.php/token
Query: api_token=<api_token>&player_token=<player_token>

Response:
{
  "success": true,
  "token": "64-char-hex-token",
  "player_info": {
    "player_id": 123,
    "player_name": "Player1",
    "room_id": "room-uuid",
    "is_host": false
  },
  "realtime_server": {
    "host": "realtime.michitai.com",
    "port": 8081,
    "protocol": "ws"
  }
}
```

## Server Configuration

### WebSocket Server
- **Host**: `realtime.michitai.com`
- **Port**: `8081`
- **Protocol**: `ws://` or `wss://` for secure connections

### Message Flow
1. Client connects with token
2. Server validates token and assigns to room
3. Client sends messages with Command, Data, Target
4. Server routes messages to appropriate players in same room
5. No join/leave room needed - players are already assigned to rooms

## Security

- **Token Validation**: Only valid tokens can connect
- **Room Isolation**: Messages only flow within same room
- **One Connection**: Previous connections are dropped automatically
- **Activity Monitoring**: Inactive connections are cleaned up
