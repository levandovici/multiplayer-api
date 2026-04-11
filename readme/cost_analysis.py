#!/usr/bin/env python3
"""
Code Cost Analysis for Multiplayer API Project
Analyzes development cost, complexity, and maintenance implications
"""

import json
from datetime import datetime

class CodeCostAnalyzer:
    def __init__(self, loc_data_file='loc_report.json'):
        with open(loc_data_file, 'r') as f:
            self.data = json.load(f)
        
        # Industry standard rates (USD per hour)
        self.rates = {
            'junior': 25,
            'mid': 50,
            'senior': 75,
            'lead': 100
        }
        
        # Average lines per hour by complexity
        self.lines_per_hour = {
            'simple': 50,      # HTML, config files
            'moderate': 25,    # PHP scripts, basic C#
            'complex': 10,     # Complex PHP, advanced C#
            'very_complex': 5  # Core SDK, complex algorithms
        }
    
    def calculate_development_time(self, lines, complexity='moderate'):
        """Calculate development time in hours"""
        return lines / self.lines_per_hour[complexity]
    
    def calculate_cost(self, hours, level='mid'):
        """Calculate development cost"""
        return hours * self.rates[level]
    
    def analyze_file_complexity(self, file_path, lines):
        """Determine file complexity based on name and line count"""
        file_path_lower = file_path.lower()
        
        # SDK files are most complex
        if 'sdk.cs' in file_path_lower:
            return 'very_complex'
        
        # Game logic is complex
        if 'game.cs' in file_path_lower or 'game_room.php' in file_path_lower:
            return 'complex'
        
        # Core API files are complex
        if 'matchmaking.php' in file_path_lower or 'index.php' in file_path_lower:
            return 'complex'
        
        # Large files are likely complex
        if lines > 500:
            return 'complex'
        elif lines > 200:
            return 'moderate'
        else:
            return 'simple'
    
    def analyze_folder_cost(self, folder_name, folder_data):
        """Analyze cost for a specific folder"""
        total_hours = 0
        total_cost = 0
        file_breakdown = []
        
        for file_path, lines in folder_data['files'].items():
            complexity = self.analyze_file_complexity(file_path, lines)
            hours = self.calculate_development_time(lines, complexity)
            
            # Determine developer level based on complexity
            if complexity == 'very_complex':
                level = 'senior'
            elif complexity == 'complex':
                level = 'mid'
            else:
                level = 'junior'
            
            cost = self.calculate_cost(hours, level)
            
            total_hours += hours
            total_cost += cost
            
            file_breakdown.append({
                'file': file_path,
                'lines': lines,
                'complexity': complexity,
                'hours': hours,
                'level': level,
                'cost': cost
            })
        
        return {
            'total_hours': total_hours,
            'total_cost': total_cost,
            'file_count': len(file_breakdown),
            'average_cost_per_file': total_cost / len(file_breakdown),
            'files': file_breakdown
        }
    
    def generate_cost_report(self):
        """Generate comprehensive cost analysis"""
        report = {
            'analysis_date': datetime.now().isoformat(),
            'currency': 'USD',
            'summary': {},
            'folders': {},
            'components': {},
            'total_project': {}
        }
        
        # Analyze each folder
        folder_costs = {}
        for folder_name, folder_data in self.data['folder_stats'].items():
            folder_costs[folder_name] = self.analyze_folder_cost(folder_name, folder_data)
            report['folders'][folder_name] = folder_costs[folder_name]
        
        # Analyze SDK components
        dotnet_cost = self.analyze_folder_cost('dotnet_sdk', {
            'files': self.data['dotnet_sdk_stats']['files']
        })
        unity_cost = self.analyze_folder_cost('unity_sdk', {
            'files': self.data['unity_sdk_stats']['files']
        })
        
        report['components']['dotnet_sdk'] = dotnet_cost
        report['components']['unity_sdk'] = unity_cost
        
        # Calculate totals
        total_hours = sum(cost['total_hours'] for cost in folder_costs.values())
        total_cost = sum(cost['total_cost'] for cost in folder_costs.values())
        total_files = sum(cost['file_count'] for cost in folder_costs.values())
        
        # Add SDK costs
        total_hours += dotnet_cost['total_hours'] + unity_cost['total_hours']
        total_cost += dotnet_cost['total_cost'] + unity_cost['total_cost']
        total_files += dotnet_cost['file_count'] + unity_cost['file_count']
        
        report['total_project'] = {
            'total_hours': total_hours,
            'total_cost': total_cost,
            'total_files': total_files,
            'average_cost_per_file': total_cost / total_files,
            'average_hours_per_file': total_hours / total_files
        }
        
        # Generate summary insights
        report['summary'] = {
            'most_expensive_folder': max(folder_costs.items(), key=lambda x: x[1]['total_cost'])[0],
            'most_expensive_file': self._find_most_expensive_file(folder_costs, dotnet_cost, unity_cost),
            'cost_distribution': self._calculate_cost_distribution(folder_costs, dotnet_cost, unity_cost),
            'complexity_breakdown': self._analyze_complexity_distribution(folder_costs, dotnet_cost, unity_cost)
        }
        
        return report
    
    def _find_most_expensive_file(self, folder_costs, dotnet_cost, unity_cost):
        """Find the most expensive single file"""
        all_files = []
        
        for folder_data in folder_costs.values():
            all_files.extend(folder_data['files'])
        
        all_files.extend(dotnet_cost['files'])
        all_files.extend(unity_cost['files'])
        
        return max(all_files, key=lambda x: x['cost'])
    
    def _calculate_cost_distribution(self, folder_costs, dotnet_cost, unity_cost):
        """Calculate cost distribution by component"""
        distribution = {}
        
        for folder_name, cost_data in folder_costs.items():
            distribution[folder_name] = cost_data['total_cost']
        
        distribution['dotnet_sdk'] = dotnet_cost['total_cost']
        distribution['unity_sdk'] = unity_cost['total_cost']
        
        return distribution
    
    def _analyze_complexity_distribution(self, folder_costs, dotnet_cost, unity_cost):
        """Analyze distribution of file complexity"""
        complexity_counts = {'simple': 0, 'moderate': 0, 'complex': 0, 'very_complex': 0}
        complexity_costs = {'simple': 0, 'moderate': 0, 'complex': 0, 'very_complex': 0}
        
        all_file_data = []
        for cost_data in folder_costs.values():
            all_file_data.extend(cost_data['files'])
        all_file_data.extend(dotnet_cost['files'])
        all_file_data.extend(unity_cost['files'])
        
        for file_data in all_file_data:
            complexity = file_data['complexity']
            complexity_counts[complexity] += 1
            complexity_costs[complexity] += file_data['cost']
        
        return {
            'file_counts': complexity_counts,
            'costs': complexity_costs
        }
    
    def print_report(self):
        """Print formatted cost analysis report"""
        report = self.generate_cost_report()
        
        print("=" * 80)
        print("CODE COST ANALYSIS REPORT")
        print("=" * 80)
        print(f"Analysis Date: {report['analysis_date'][:10]}")
        print(f"Currency: {report['currency']}")
        
        # Project Summary
        print(f"\n[SUMMARY] PROJECT SUMMARY:")
        print("-" * 40)
        total = report['total_project']
        print(f"Total Files:     {total['total_files']:,}")
        print(f"Total Hours:     {total['total_hours']:.1f} hours")
        print(f"Total Cost:      ${total['total_cost']:,.2f}")
        print(f"Avg Cost/File:   ${total['average_cost_per_file']:,.2f}")
        print(f"Avg Hours/File:  {total['average_hours_per_file']:.1f} hours")
        
        # Folder Breakdown
        print(f"\n[FOLDERS] FOLDER COST BREAKDOWN:")
        print("-" * 50)
        for folder_name, cost_data in report['folders'].items():
            print(f"{folder_name.upper():<10}: ${cost_data['total_cost']:>8,.2f} ({cost_data['total_hours']:.1f}h, {cost_data['file_count']} files)")
        
        # SDK Costs
        print(f"\n[SDK] SDK COSTS:")
        print("-" * 30)
        print(f".NET SDK:  ${report['components']['dotnet_sdk']['total_cost']:>8,.2f} ({report['components']['dotnet_sdk']['total_hours']:.1f}h)")
        print(f"Unity SDK: ${report['components']['unity_sdk']['total_cost']:>8,.2f} ({report['components']['unity_sdk']['total_hours']:.1f}h)")
        
        # Most Expensive Items
        print(f"\n[INSIGHTS] COST INSIGHTS:")
        print("-" * 30)
        print(f"Most Expensive Folder: {report['summary']['most_expensive_folder'].upper()}")
        most_expensive_file = report['summary']['most_expensive_file']
        print(f"Most Expensive File: {most_expensive_file['file']}")
        print(f"                     ${most_expensive_file['cost']:,.2f} ({most_expensive_file['hours']:.1f}h)")
        
        # Complexity Distribution
        print(f"\n[COMPLEXITY] COMPLEXITY BREAKDOWN:")
        print("-" * 40)
        complexity = report['summary']['complexity_breakdown']
        print("Complexity    Files    Cost")
        for comp in ['simple', 'moderate', 'complex', 'very_complex']:
            count = complexity['file_counts'][comp]
            cost = complexity['costs'][comp]
            print(f"{comp:<12} {count:>6}    ${cost:>8,.2f}")
        
        # Cost Distribution
        print(f"\n[DISTRIBUTION] COST DISTRIBUTION:")
        print("-" * 40)
        distribution = report['summary']['cost_distribution']
        total_cost = sum(distribution.values())
        for component, cost in sorted(distribution.items(), key=lambda x: x[1], reverse=True):
            percentage = (cost / total_cost) * 100
            print(f"{component.upper():<12}: ${cost:>8,.2f} ({percentage:>5.1f}%)")
        
        print("\n" + "=" * 80)
        print("ANALYSIS COMPLETE")
        print("=" * 80)

def main():
    analyzer = CodeCostAnalyzer()
    analyzer.print_report()
    
    # Save detailed report
    report = analyzer.generate_cost_report()
    with open('cost_analysis_report.json', 'w') as f:
        json.dump(report, f, indent=2)
    print(f"\n[REPORT] Detailed cost report saved to: cost_analysis_report.json")

if __name__ == "__main__":
    main()
