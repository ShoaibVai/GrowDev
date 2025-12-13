<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $user->name }} - CV</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 8.5in;
            height: 11in;
            margin: 0 auto;
            padding: 0.5in;
            background: white;
        }

        /* Header */
        .header {
            border-bottom: 3px solid #2563eb;
            margin-bottom: 0.3in;
            padding-bottom: 0.2in;
        }

        .name {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 0.1in;
        }

        .contact-info {
            font-size: 10px;
            color: #666;
            display: flex;
            flex-wrap: wrap;
            gap: 0.2in;
        }

        .contact-info span {
            display: inline-block;
        }

        /* Professional Summary */
        .summary {
            margin-bottom: 0.2in;
            font-size: 11px;
            line-height: 1.5;
        }

        /* Section */
        .section {
            margin-bottom: 0.2in;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #1f2937;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 0.05in;
            margin-bottom: 0.1in;
        }

        /* Entry */
        .entry {
            margin-bottom: 0.15in;
        }

        .entry-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.05in;
        }

        .entry-title {
            font-weight: bold;
            font-size: 11px;
            color: #1f2937;
        }

        .entry-subtitle {
            font-size: 10px;
            color: #666;
            font-style: italic;
        }

        .entry-date {
            font-size: 9px;
            color: #999;
        }

        .entry-description {
            font-size: 10px;
            color: #555;
            margin-top: 0.05in;
            line-height: 1.4;
        }

        /* Work Experience */
        .work-item .entry-title::after {
            content: ' • ';
            margin: 0 0.05in;
        }

        /* Skills */
        .skills-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.1in;
            font-size: 10px;
        }

        .skill-item {
            padding: 0.05in 0.1in;
            background: #f3f4f6;
            border-radius: 2px;
        }

        .skill-name {
            font-weight: bold;
            color: #1f2937;
        }

        .skill-level {
            font-size: 9px;
            color: #666;
        }

        /* Education */
        .education-item {
            margin-bottom: 0.1in;
        }

        /* Certifications */
        .cert-item {
            margin-bottom: 0.1in;
        }

        .cert-name {
            font-weight: bold;
            font-size: 11px;
        }

        .cert-issuer {
            font-size: 10px;
            color: #666;
        }

        /* Links */
        a {
            color: #2563eb;
            text-decoration: none;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="name">{{ $user->name }}</div>
            <div class="contact-info">
                <span>{{ $user->email }}</span>
                @if ($user->phone_number)
                    <span>•</span>
                    <span>{{ $user->phone_number }}</span>
                @endif
                @if ($user->location)
                    <span>•</span>
                    <span>{{ $user->location }}</span>
                @endif
                @if ($user->website)
                    <span>•</span>
                    <span><a href="{{ $user->website }}">{{ parse_url($user->website, PHP_URL_HOST) }}</a></span>
                @endif
                @if ($user->linkedin_url)
                    <span>•</span>
                    <span><a href="{{ $user->linkedin_url }}">LinkedIn</a></span>
                @endif
                @if ($user->github_url)
                    <span>•</span>
                    <span><a href="{{ $user->github_url }}">GitHub</a></span>
                @endif
            </div>
        </div>

        <!-- Professional Summary -->
        @if ($user->professional_summary)
            <div class="summary">
                {{ $user->professional_summary }}
            </div>
        @endif

        <!-- Work Experience -->
        @if ($user->workExperiences->count() > 0)
            <div class="section">
                <div class="section-title">WORK EXPERIENCE</div>
                @foreach ($user->workExperiences as $exp)
                    <div class="entry work-item">
                        <div class="entry-header">
                            <div>
                                <div class="entry-title">{{ $exp->job_title }}</div>
                                <div class="entry-subtitle">{{ $exp->company_name }}</div>
                            </div>
                            <div class="entry-date">
                                {{ $exp->start_date->format('M Y') }}
                                @if ($exp->end_date)
                                    - {{ $exp->end_date->format('M Y') }}
                                @else
                                    - Present
                                @endif
                            </div>
                        </div>
                        @if ($exp->description)
                            <div class="entry-description">{{ $exp->description }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Education -->
        @if ($user->educations->count() > 0)
            <div class="section">
                <div class="section-title">EDUCATION</div>
                @foreach ($user->educations as $edu)
                    <div class="entry education-item">
                        <div class="entry-header">
                            <div>
                                <div class="entry-title">{{ $edu->degree }}</div>
                                <div class="entry-subtitle">{{ $edu->field_of_study }} • {{ $edu->school_name }}</div>
                            </div>
                            <div class="entry-date">
                                {{ $edu->start_date->format('M Y') }} - {{ $edu->end_date->format('M Y') }}
                            </div>
                        </div>
                        @if ($edu->description)
                            <div class="entry-description">{{ $edu->description }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Projects -->
        @if ($user->projects->count() > 0)
            <div class="section">
                <div class="section-title">PROJECTS</div>
                @foreach ($user->projects as $project)
                    <div class="entry">
                        <div class="entry-header">
                            <div>
                                <div class="entry-title">{{ $project->name }}</div>
                                @if ($project->description)
                                    <div class="entry-subtitle">{{ $project->description }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Skills -->
        @if ($user->skills->count() > 0)
            <div class="section">
                <div class="section-title">SKILLS</div>
                <div class="skills-grid">
                    @foreach ($user->skills as $skill)
                        <div class="skill-item">
                            <div class="skill-name">{{ $skill->skill_name }}</div>
                            <div class="skill-level">{{ ucfirst($skill->proficiency) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Certifications -->
        @if ($user->certifications->count() > 0)
            <div class="section">
                <div class="section-title">CERTIFICATIONS</div>
                @foreach ($user->certifications as $cert)
                    <div class="entry cert-item">
                        <div class="entry-header">
                            <div>
                                <div class="cert-name">{{ $cert->certification_name }}</div>
                                <div class="cert-issuer">{{ $cert->issuer }}</div>
                            </div>
                            <div class="entry-date">{{ $cert->issue_date->format('M Y') }}</div>
                        </div>
                        @if ($cert->description)
                            <div class="entry-description">{{ $cert->description }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
