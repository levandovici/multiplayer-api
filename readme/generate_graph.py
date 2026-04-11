#!/usr/bin/env python3
"""
Script to generate lines of code data from git commits
Converts git history into JSON graph data with x->time, y->lines of code format
"""

import subprocess
import json
import re
from datetime import datetime
import sys

def generate_graph_data():
    """Generate lines of code graph data from git commits"""
    
    # Get git log data in reverse chronological order (oldest first)
    try:
        git_log = subprocess.check_output([
            'git', 'log', '--pretty=format:%H|%ad|%s', '--date=iso', '--numstat', '--reverse'
        ], text=True, cwd='.')
    except subprocess.CalledProcessError as e:
        print(f"Error running git command: {e}")
        return None
    
    lines = git_log.strip().split('\n')
    
    graph_data = []
    total_loc = 0
    commit_data = []
    
    # Parse each commit
    current_commit = None
    
    for line in lines:
        line = line.strip()
        if not line:
            continue
        
        # Check if this is a commit header line
        commit_match = re.match(r'^([a-f0-9]+)\|(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} [+-]\d{4})\|(.+)$', line)
        if commit_match:
            commit_hash = commit_match.group(1)
            commit_date = commit_match.group(2)
            commit_message = commit_match.group(3)
            
            # Store current commit info
            current_commit = {
                'hash': commit_hash,
                'date': commit_date,
                'message': commit_message,
                'additions': 0,
                'deletions': 0
            }
            commit_data.append(current_commit)
        
        # Check if this is a file change line (format: additions deletions filename)
        file_match = re.match(r'^(\d+)\s+(\d+)\s+(.+)$', line)
        if file_match:
            additions = int(file_match.group(1))
            deletions = int(file_match.group(2))
            filename = file_match.group(3)
            
            # Skip binary files and certain file types
            if any(ext in filename.lower() for ext in ['.png', '.jpg', '.jpeg', '.gif', '.ico', '.svg']):
                continue
            
            # Add to current commit's totals
            if current_commit:
                current_commit['additions'] += additions
                current_commit['deletions'] += deletions
    
    # Calculate cumulative LOC over time
    cumulative_loc = 0
    data_points = []
    
    for commit in commit_data:
        cumulative_loc += commit['additions'] - commit['deletions']
        
        # Convert date to timestamp for graph
        try:
            dt = datetime.strptime(commit['date'], '%Y-%m-%d %H:%M:%S %z')
            timestamp = int(dt.timestamp() * 1000)  # Convert to milliseconds for JavaScript
        except ValueError:
            # Fallback for different date formats
            try:
                dt = datetime.strptime(commit['date'][:19], '%Y-%m-%d %H:%M:%S')
                timestamp = int(dt.timestamp() * 1000)
            except ValueError:
                timestamp = 0
        
        data_points.append({
            'x': timestamp,
            'y': max(0, cumulative_loc),  # Ensure non-negative
            'date': commit['date'],
            'message': commit['message'],
            'hash': commit['hash'][:7]  # Short hash
        })
    
    # Create the final JSON structure
    graph_data = {
        'metadata': {
            'title': 'Lines of Code Over Time',
            'description': 'Cumulative lines of code in the multiplayer-api project',
            'generated': datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
            'total_commits': len(commit_data),
            'total_loc': max(0, cumulative_loc)
        },
        'data': data_points
    }
    
    return graph_data

def main():
    """Main function to generate and save the data"""
    print("Generating LOC graph data...")
    
    graph_data = generate_graph_data()
    
    if not graph_data:
        print("Failed to generate graph data")
        sys.exit(1)
    
    # Save to JSON file
    output_file = 'graph_data.json'
    with open(output_file, 'w') as f:
        json.dump(graph_data, f, indent=2)
    
    print(f"Generated LOC graph data with {len(graph_data['data'])} data points")
    print(f"Total LOC: {graph_data['metadata']['total_loc']}")
    print(f"Total commits: {graph_data['metadata']['total_commits']}")
    print(f"Data saved to {output_file}")

if __name__ == '__main__':
    main()
