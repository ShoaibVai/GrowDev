<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SRS - {{ $srsDocument->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        
        .header {
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            margin: 0;
            color: #4f46e5;
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
            border-left: 4px solid #4f46e5;
            padding: 12px 15px;
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 15px;
        }
        
        .section-content {
            padding: 0 15px;
        }
        
        .requirement {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        
        .req-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .req-id {
            font-weight: bold;
            color: #4f46e5;
            font-size: 14px;
        }
        
        .req-title {
            font-weight: bold;
            font-size: 15px;
            color: #1f2937;
        }
        
        .priority-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .priority-low {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .priority-medium {
            background-color: #fed7aa;
            color: #92400e;
        }
        
        .priority-high {
            background-color: #fecaca;
            color: #7f1d1d;
        }
        
        .priority-critical {
            background-color: #dc2626;
            color: #fff;
        }
        
        .req-description {
            margin-bottom: 10px;
            color: #4b5563;
        }
        
        .ux-label {
            font-weight: bold;
            color: #7c3aed;
            font-size: 12px;
            margin-top: 10px;
            margin-bottom: 5px;
        }
        
        .ux-items {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        
        .ux-items li {
            background-color: #f3e8ff;
            padding: 6px 10px;
            margin-bottom: 5px;
            border-left: 3px solid #7c3aed;
            font-size: 13px;
        }
        
        .overview-section {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .overview-section p {
            margin: 0;
            color: #1e40af;
            line-height: 1.8;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>📋 SOFTWARE REQUIREMENTS SPECIFICATION (SRS)</h1>
        <p><strong>Document Title:</strong> {{ $srsDocument->title }}</p>
        <p><strong>Created:</strong> {{ $srsDocument->created_at->format('F d, Y') }}</p>
        <p><strong>Last Updated:</strong> {{ $srsDocument->updated_at->format('F d, Y') }}</p>
    </div>

    <!-- Description -->
    @if ($srsDocument->description)
        <div class="section">
            <div class="section-title">📝 Description</div>
            <div class="section-content">
                <div class="overview-section">
                    <p>{{ $srsDocument->description }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Project Overview -->
    @if ($srsDocument->project_overview)
        <div class="section">
            <div class="section-title">🎯 Project Overview</div>
            <div class="section-content">
                <div class="overview-section">
                    <p>{{ $srsDocument->project_overview }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Scope -->
    @if ($srsDocument->scope)
        <div class="section">
            <div class="section-title">📊 Scope</div>
            <div class="section-content">
                <div class="overview-section">
                    <p>{{ $srsDocument->scope }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Constraints -->
    @if ($srsDocument->constraints)
        <div class="section">
            <div class="section-title">🔒 Constraints</div>
            <div class="section-content">
                <div class="overview-section">
                    <p>{{ $srsDocument->constraints }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Assumptions -->
    @if ($srsDocument->assumptions)
        <div class="section">
            <div class="section-title">💭 Assumptions</div>
            <div class="section-content">
                <div class="overview-section">
                    <p>{{ $srsDocument->assumptions }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Functional Requirements -->
    @if ($srsDocument->functionalRequirements->count() > 0)
        <div class="page-break"></div>
        <div class="section">
            <div class="section-title">✅ Functional Requirements ({{ $srsDocument->functionalRequirements->count() }})</div>
            <div class="section-content">
                @foreach ($srsDocument->functionalRequirements as $req)
                    <div class="requirement">
                        <div class="req-header">
                            <span class="req-id">{{ $req->requirement_id }}</span>
                            <span class="priority-badge priority-{{ $req->priority }}">{{ $req->priority }}</span>
                        </div>
                        <div class="req-title">{{ $req->title }}</div>
                        <div class="req-description">{{ $req->description }}</div>
                        
                        @if ($req->ux_considerations && count($req->ux_considerations) > 0)
                            <div class="ux-label">🎨 UX Considerations:</div>
                            <ul class="ux-items">
                                @foreach ($req->ux_considerations as $ux)
                                    <li>{{ $ux }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Footer -->
    <div style="margin-top: 50px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center; color: #666; font-size: 12px;">
        <p>Generated on {{ now()->format('F d, Y g:i A') }} | GrowDev Documentation System</p>
    </div>
</body>
</html>
