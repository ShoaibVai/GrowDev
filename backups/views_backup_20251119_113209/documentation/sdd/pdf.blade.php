<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SDD - {{ $sddDocument->title }}</title>
    <script src="https://cdn.jsdelivr.net/npm/mermaid@latest/dist/mermaid.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        
        .header {
            border-bottom: 3px solid #16a34a;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            margin: 0;
            color: #16a34a;
            font-size: 32px;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .section-title {
            background-color: #f3f4f6;
            border-left: 4px solid #16a34a;
            padding: 12px 15px;
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 15px;
        }
        
        .section-content {
            padding: 0 15px;
        }
        
        .overview-section {
            background-color: #f0fdf4;
            border-left: 4px solid #22c55e;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .overview-section p {
            margin: 0;
            color: #15803d;
            line-height: 1.8;
        }
        
        .component {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        
        .component-name {
            font-weight: bold;
            color: #16a34a;
            font-size: 15px;
            margin-bottom: 8px;
        }
        
        .component-field {
            margin-bottom: 10px;
        }
        
        .component-field-label {
            font-weight: bold;
            color: #4b5563;
            font-size: 13px;
        }
        
        .component-field-value {
            color: #6b7280;
            margin-left: 0;
            padding-left: 10px;
            border-left: 3px solid #d1d5db;
        }
        
        .diagram-section {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        
        .diagram-title {
            font-weight: bold;
            color: #7c3aed;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .diagram-container {
            background-color: white;
            border: 1px solid #e5e7eb;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
        }
        
        .mermaid {
            display: flex;
            justify-content: center;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>🏗️ SOFTWARE DESIGN DESCRIPTION (SDD)</h1>
        <p><strong>Document Title:</strong> {{ $sddDocument->title }}</p>
        <p><strong>Created:</strong> {{ $sddDocument->created_at->format('F d, Y') }}</p>
        <p><strong>Last Updated:</strong> {{ $sddDocument->updated_at->format('F d, Y') }}</p>
    </div>

    <!-- Description -->
    @if ($sddDocument->description)
        <div class="section">
            <div class="section-title">📝 Description</div>
            <div class="section-content">
                <div class="overview-section">
                    <p>{{ $sddDocument->description }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Design Overview -->
    @if ($sddDocument->design_overview)
        <div class="section">
            <div class="section-title">🎨 Design Overview</div>
            <div class="section-content">
                <div class="overview-section">
                    <p>{{ $sddDocument->design_overview }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Architecture Overview -->
    @if ($sddDocument->architecture_overview)
        <div class="section">
            <div class="section-title">🏛️ Architecture Overview</div>
            <div class="section-content">
                <div class="overview-section">
                    <p>{{ $sddDocument->architecture_overview }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Components -->
    @if ($sddDocument->components->count() > 0)
        <div class="page-break"></div>
        <div class="section">
            <div class="section-title">🔧 Components ({{ $sddDocument->components->count() }})</div>
            <div class="section-content">
                @foreach ($sddDocument->components as $component)
                    <div class="component">
                        <div class="component-name">{{ $component->component_name }}</div>
                        
                        <div class="component-field">
                            <div class="component-field-label">Description:</div>
                            <div class="component-field-value">{{ $component->description }}</div>
                        </div>
                        
                        <div class="component-field">
                            <div class="component-field-label">Responsibility:</div>
                            <div class="component-field-value">{{ $component->responsibility }}</div>
                        </div>
                        
                        @if ($component->interfaces)
                            <div class="component-field">
                                <div class="component-field-label">Interfaces:</div>
                                <div class="component-field-value">{{ $component->interfaces }}</div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Diagrams -->
    @if ($sddDocument->diagrams->count() > 0)
        <div class="page-break"></div>
        <div class="section">
            <div class="section-title">📊 Architecture Diagrams ({{ $sddDocument->diagrams->count() }})</div>
            <div class="section-content">
                @foreach ($sddDocument->diagrams as $diagram)
                    <div class="diagram-section">
                        <div class="diagram-title">{{ $diagram->diagram_name }}</div>
                        <p style="font-size: 12px; color: #666; margin-bottom: 10px;">Type: {{ ucfirst($diagram->diagram_type) }}</p>
                        <div class="diagram-container">
                            <div class="mermaid">{{ $diagram->diagram_content }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Footer -->
    <div style="margin-top: 50px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center; color: #666; font-size: 12px;">
        <p>Generated on {{ now()->format('F d, Y g:i A') }} | GrowDev Documentation System</p>
    </div>

    <script>
        mermaid.initialize({ startOnLoad: true, theme: 'default' });
    </script>
</body>
</html>
