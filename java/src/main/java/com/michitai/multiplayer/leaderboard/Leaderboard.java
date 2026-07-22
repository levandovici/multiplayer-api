package com.michitai.multiplayer.leaderboard;

import com.michitai.multiplayer.Client;

import java.io.IOException;

/**
 * Provides methods for querying and retrieving leaderboard rankings.
 */
public class Leaderboard {
    /**
     * Retrieves the leaderboard with specified sorting and limit.
     *
     * @param client The API client instance.
     * @param sortBy Array of field names to sort by (e.g., ["level", "wins"]).
     * @param limit Maximum number of results to return (1-100).
     * @return Response containing the leaderboard entries with rankings.
     * @throws IOException if the request fails.
     */
    public static LeaderboardResponse<?> getLeaderboard(Client client, String[] sortBy, int limit) throws IOException {
        LeaderboardRequest request = new LeaderboardRequest(sortBy, limit);
        return client.post(client.url(Endpoints.LEADERBOARD), request, LeaderboardResponse.class);
    }
}
