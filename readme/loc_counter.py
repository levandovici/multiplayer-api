#!/usr/bin/env python3
"""
Lines of Code Counter for Multiplayer API Project
Counts lines of code for different folders and visualizations as requested.
"""

import os
import glob
from pathlib import Path
from collections import defaultdict

class LOCCounter:
    def __init__(self, base_path):
        self.base_path = Path(base_path)
        self.results = {}
    
    def count_lines_in_file(self, file_path):
        """Count non-empty lines in a file"""
        try:
            with open(file_path, 'r', encoding='utf-8', errors='ignore') as f:
                lines = f.readlines()
                # Count non-empty lines (excluding whitespace-only lines)
                return sum(1 for line in lines if line.strip())
        except Exception as e:
            print(f"Error reading {file_path}: {e}")
            return 0
    
    def count_lines_in_folder(self, folder_path, extensions=None):
        """Count lines in all files within a folder"""
        folder = self.base_path / folder_path
        if not folder.exists():
            return 0
        
        total_lines = 0
        file_details = {}
        
        for file_path in folder.rglob('*'):
            if file_path.is_file():
                # Skip certain files
                if file_path.name.startswith('.') or file_path.name in ['__pycache__', 'node_modules']:
                    continue
                
                # Filter by extension if specified
                if extensions:
                    if not any(file_path.suffix.endswith(ext) for ext in extensions):
                        continue
                
                lines = self.count_lines_in_file(file_path)
                total_lines += lines
                file_details[str(file_path.relative_to(self.base_path))] = lines
        
        return total_lines, file_details
    
    def count_specific_files(self, patterns):
        """Count lines in files matching specific patterns"""
        total_lines = 0
        file_details = {}
        
        for pattern in patterns:
            for file_path in self.base_path.glob(pattern):
                if file_path.is_file():
                    lines = self.count_lines_in_file(file_path)
                    total_lines += lines
                    file_details[str(file_path.relative_to(self.base_path))] = lines
        
        return total_lines, file_details
    
    def get_folder_stats(self):
        """Get lines of code per folder: php, unity, api, dotnet"""
        folders = ['php', 'unity', 'api', 'dotnet']
        folder_stats = {}
        
        for folder in folders:
            total_lines, file_details = self.count_lines_in_folder(folder)
            folder_stats[folder] = {
                'total_lines': total_lines,
                'file_count': len(file_details),
                'files': file_details
            }
        
        return folder_stats
    
    def get_web_part_stats(self):
        """Get web part stats: index.*, *.html, php/ folder"""
        web_stats = {}
        
        # index.* files
        index_files = ['index.php']
        index_lines, index_details = self.count_specific_files(index_files)
        web_stats['index_files'] = {
            'total_lines': index_lines,
            'files': index_details
        }
        
        # *.html files
        html_files = glob.glob('*.html', root_dir=self.base_path)
        html_lines, html_details = self.count_specific_files(html_files)
        web_stats['html_files'] = {
            'total_lines': html_lines,
            'files': html_details
        }
        
        # php/ folder
        php_lines, php_details = self.count_lines_in_folder('php')
        web_stats['php_folder'] = {
            'total_lines': php_lines,
            'file_count': len(php_details),
            'files': php_details
        }
        
        # Total web part
        web_stats['total_web'] = {
            'total_lines': index_lines + html_lines + php_lines,
            'components': {
                'index_files': index_lines,
                'html_files': html_lines,
                'php_folder': php_lines
            }
        }
        
        return web_stats
    
    def get_backend_part_stats(self):
        """Get backend part stats: api/ folder without index.php"""
        backend_stats = {}
        
        # Count all files in api/ folder
        api_lines, api_details = self.count_lines_in_folder('api')
        
        # Subtract index.php lines if it exists
        index_php_path = self.base_path / 'api' / 'index.php'
        if index_php_path.exists():
            index_php_lines = self.count_lines_in_file(index_php_path)
            backend_lines = api_lines - index_php_lines
            # Remove index.php from details
            api_details.pop('api/index.php', None)
        else:
            backend_lines = api_lines
        
        backend_stats['api_folder'] = {
            'total_lines': backend_lines,
            'file_count': len(api_details),
            'files': api_details
        }
        
        return backend_stats
    
    def get_dotnet_sdk_stats(self):
        """Get .NET SDK stats: SDK.cs and Game.cs files + total"""
        dotnet_patterns = ['dotnet/SDK.cs', 'dotnet/Game.cs']
        total_lines, file_details = self.count_specific_files(dotnet_patterns)
        
        return {
            'total_lines': total_lines,
            'file_count': len(file_details),
            'files': file_details
        }
    
    def get_unity_sdk_stats(self):
        """Get Unity SDK stats: SDK.cs and Game.cs files + total"""
        unity_patterns = ['unity/SDK.cs', 'unity/Game.cs']
        total_lines, file_details = self.count_specific_files(unity_patterns)
        
        return {
            'total_lines': total_lines,
            'file_count': len(file_details),
            'files': file_details
        }
    
    def print_results(self):
        """Print all results in a formatted way"""
        print("=" * 80)
        print("LINES OF CODE ANALYSIS")
        print("=" * 80)
        
        # Folder-based stats
        print("\n[FOLDER] LINES OF CODE PER FOLDER:")
        print("-" * 40)
        folder_stats = self.get_folder_stats()
        for folder, stats in folder_stats.items():
            print(f"{folder.upper():<10}: {stats['total_lines']:,} lines ({stats['file_count']} files)")
        
        # Web part stats
        print("\n[WEB] WEB PART:")
        print("-" * 40)
        web_stats = self.get_web_part_stats()
        print(f"Index Files: {web_stats['index_files']['total_lines']:,} lines")
        print(f"HTML Files:  {web_stats['html_files']['total_lines']:,} lines")
        print(f"PHP Folder:  {web_stats['php_folder']['total_lines']:,} lines")
        print(f"Total Web:   {web_stats['total_web']['total_lines']:,} lines")
        
        # Backend part stats
        print("\n[BACKEND] BACKEND PART:")
        print("-" * 40)
        backend_stats = self.get_backend_part_stats()
        print(f"API Folder (without index.php): {backend_stats['api_folder']['total_lines']:,} lines")
        
        # .NET SDK stats
        print("\n[DOTNET] .NET SDK:")
        print("-" * 40)
        dotnet_stats = self.get_dotnet_sdk_stats()
        for file_path, lines in dotnet_stats['files'].items():
            print(f"{file_path}: {lines:,} lines")
        print(f"Total .NET SDK: {dotnet_stats['total_lines']:,} lines")
        
        # Unity SDK stats
        print("\n[UNITY] UNITY SDK:")
        print("-" * 40)
        unity_stats = self.get_unity_sdk_stats()
        for file_path, lines in unity_stats['files'].items():
            print(f"{file_path}: {lines:,} lines")
        print(f"Total Unity SDK: {unity_stats['total_lines']:,} lines")
        
        print("\n" + "=" * 80)
        print("ANALYSIS COMPLETE")
        print("=" * 80)
    
    def get_detailed_report(self):
        """Get detailed report with all statistics"""
        return {
            'folder_stats': self.get_folder_stats(),
            'web_stats': self.get_web_part_stats(),
            'backend_stats': self.get_backend_part_stats(),
            'dotnet_sdk_stats': self.get_dotnet_sdk_stats(),
            'unity_sdk_stats': self.get_unity_sdk_stats()
        }

def main():
    # Get the directory where this script is located
    script_dir = os.path.dirname(os.path.abspath(__file__))
    
    # Create counter instance
    counter = LOCCounter(script_dir)
    
    # Print results
    counter.print_results()
    
    # Optionally save detailed report to JSON
    try:
        import json
        detailed_report = counter.get_detailed_report()
        with open('loc_report.json', 'w', encoding='utf-8') as f:
            json.dump(detailed_report, f, indent=2, ensure_ascii=False)
        print(f"\n[REPORT] Detailed report saved to: loc_report.json")
    except ImportError:
        print("\n[WARNING] JSON module not available. Detailed report not saved.")

if __name__ == "__main__":
    main()
