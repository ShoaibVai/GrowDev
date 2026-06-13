<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-4">
            <h2 class="font-semibold text-xl leading-tight" style="color: var(--color-text); font-family: var(--font-mono);">
                {{ __('Edit Profile & CV') }}
            </h2>
            <button type="button" onclick="generatePDF()" class="whitespace-nowrap inline-flex items-center px-6 py-2 rounded-lg transition font-semibold shadow-md" style="background-color: var(--color-accent); color: #fff;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2m0 0v-8m0 8H3m18 0h-3"></path>
                </svg>
                {{ __('Export PDF') }}
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 p-4 rounded-lg" style="background-color: var(--color-surface-2); border: 1px solid var(--color-danger);">
                    <div class="font-semibold mb-2" style="color: var(--color-danger);">{{ __('Please correct the following errors:') }}</div>
                    <ul class="list-disc list-inside text-sm" style="color: var(--color-danger);">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" class="grid grid-cols-3 gap-6">
                @csrf
                @method('PUT')

                <!-- Main Content (Left Side) -->
                <div class="col-span-2 space-y-6">
                    <!-- Personal Information Section -->
                    <div class="p-6 rounded-lg shadow" style="background-color: var(--color-surface); border: 1px solid var(--color-border);">
                        <h3 class="text-lg font-semibold mb-4" style="color: var(--color-text); font-family: var(--font-mono);">{{ __('Personal Information') }}</h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Name -->
                            <div class="col-span-2">
                                <label for="name" class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Full Name') }} *</label>
                                <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required
                                    class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                    style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
                                @error('name')<span class="text-sm" style="color: var(--color-danger);">{{ $message }}</span>@enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Email') }} *</label>
                                <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                                    class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                    style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
                                @error('email')<span class="text-sm" style="color: var(--color-danger);">{{ $message }}</span>@enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone_number" class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Phone Number') }}</label>
                                <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number', auth()->user()->phone_number) }}"
                                    class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                    style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
                                @error('phone_number')<span class="text-sm" style="color: var(--color-danger);">{{ $message }}</span>@enderror
                            </div>

                            <!-- Location -->
                            <div>
                                <label for="location" class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Location') }}</label>
                                <input type="text" id="location" name="location" value="{{ old('location', auth()->user()->location) }}"
                                    class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                    style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
                                @error('location')<span class="text-sm" style="color: var(--color-danger);">{{ $message }}</span>@enderror
                            </div>

                            <!-- Website -->
                            <div>
                                <label for="website" class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Website') }}</label>
                                <input type="url" id="website" name="website" value="{{ old('website', auth()->user()->website) }}"
                                    class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                    style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
                                @error('website')<span class="text-sm" style="color: var(--color-danger);">{{ $message }}</span>@enderror
                            </div>

                            <!-- LinkedIn -->
                            <div>
                                <label for="linkedin_url" class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('LinkedIn URL') }}</label>
                                <input type="url" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', auth()->user()->linkedin_url) }}"
                                    class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                    style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
                                @error('linkedin_url')<span class="text-sm" style="color: var(--color-danger);">{{ $message }}</span>@enderror
                            </div>

                            <!-- GitHub -->
                            <div>
                                <label for="github_url" class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('GitHub URL') }}</label>
                                <input type="url" id="github_url" name="github_url" value="{{ old('github_url', auth()->user()->github_url) }}"
                                    class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                    style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
                                @error('github_url')<span class="text-sm" style="color: var(--color-danger);">{{ $message }}</span>@enderror
                            </div>

                            <!-- Professional Summary -->
                            <div class="col-span-2">
                                <label for="professional_summary" class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Professional Summary') }}</label>
                                <textarea id="professional_summary" name="professional_summary" rows="4"
                                    class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                    style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">{{ old('professional_summary', auth()->user()->professional_summary) }}</textarea>
                                @error('professional_summary')<span class="text-sm" style="color: var(--color-danger);">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Work Experience Section -->
                    <div class="p-6 rounded-lg shadow" style="background-color: var(--color-surface); border: 1px solid var(--color-border);">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold" style="color: var(--color-text); font-family: var(--font-mono);">{{ __('Work Experience') }}</h3>
                            <button type="button" onclick="addWorkExperience()" class="px-3 py-1 text-white text-sm rounded transition" style="background-color: var(--color-accent);" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                                + {{ __('Add') }}
                            </button>
                        </div>
                        <div id="work-experiences" class="space-y-4">
                            @foreach (auth()->user()->workExperiences as $index => $exp)
                                @include('profile.partials.work-experience-item', ['index' => $index, 'exp' => $exp])
                            @endforeach
                        </div>
                    </div>

                    <!-- Education Section -->
                    <div class="p-6 rounded-lg shadow" style="background-color: var(--color-surface); border: 1px solid var(--color-border);">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold" style="color: var(--color-text); font-family: var(--font-mono);">{{ __('Education') }}</h3>
                            <button type="button" onclick="addEducation()" class="px-3 py-1 text-white text-sm rounded transition" style="background-color: var(--color-accent);" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                                + {{ __('Add') }}
                            </button>
                        </div>
                        <div id="educations" class="space-y-4">
                            @foreach (auth()->user()->educations as $index => $edu)
                                @include('profile.partials.education-item', ['index' => $index, 'edu' => $edu])
                            @endforeach
                        </div>
                    </div>

                    <!-- Skills Section -->
                    <div class="p-6 rounded-lg shadow" style="background-color: var(--color-surface); border: 1px solid var(--color-border);">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold" style="color: var(--color-text); font-family: var(--font-mono);">{{ __('Skills') }}</h3>
                            <button type="button" onclick="addSkill()" class="px-3 py-1 text-white text-sm rounded transition" style="background-color: var(--color-accent);" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                                + {{ __('Add') }}
                            </button>
                        </div>
                        <div id="skills" class="space-y-3">
                            @foreach (auth()->user()->skills as $index => $skill)
                                @include('profile.partials.skill-item', ['index' => $index, 'skill' => $skill])
                            @endforeach
                        </div>
                    </div>

                    <!-- Certifications Section -->
                    <div class="p-6 rounded-lg shadow" style="background-color: var(--color-surface); border: 1px solid var(--color-border);">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold" style="color: var(--color-text); font-family: var(--font-mono);">{{ __('Certifications') }}</h3>
                            <button type="button" onclick="addCertification()" class="px-3 py-1 text-white text-sm rounded transition" style="background-color: var(--color-accent);" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                                + {{ __('Add') }}
                            </button>
                        </div>
                        <div id="certifications" class="space-y-4">
                            @foreach (auth()->user()->certifications as $index => $cert)
                                @include('profile.partials.certification-item', ['index' => $index, 'cert' => $cert])
                            @endforeach
                        </div>
                    </div>

                    <!-- Projects Section -->
                    <div class="p-6 rounded-lg shadow" style="background-color: var(--color-surface); border: 1px solid var(--color-border);">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold" style="color: var(--color-text); font-family: var(--font-mono);">{{ __('Projects') }}</h3>
                            <button type="button" onclick="addManualProject()" class="px-3 py-1 text-white text-sm rounded transition" style="background-color: var(--color-accent);" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                                + {{ __('Add') }}
                            </button>
                        </div>
                        <p class="text-sm mb-4" style="color: var(--color-text-muted);">Showcase custom side projects and see the ones generated through GrowDev automatically.</p>

                        <div id="manual-projects" class="space-y-4">
                            @forelse(($manualProjects ?? collect()) as $index => $project)
                                @include('profile.partials.project-item', ['index' => $index, 'project' => $project])
                            @empty
                                <p class="text-sm" style="color: var(--color-text-muted);">No manual projects yet. Use the button above to add one.</p>
                            @endforelse
                        </div>

                        <div class="mt-6 pt-6" style="border-top: 1px solid var(--color-border);">
                            <h4 class="text-sm font-semibold mb-2" style="color: var(--color-text); font-family: var(--font-mono);">{{ __('Projects created in GrowDev') }}</h4>
                            <div class="space-y-3">
                                @forelse(($autoProjects ?? collect()) as $project)
                                    <div class="p-4 rounded-lg" style="background-color: var(--color-surface-2); border: 1px solid var(--color-border);">
                                        <div class="flex justify-between items-center mb-1">
                                            <div class="font-semibold" style="color: var(--color-text);">{{ $project->name }}</div>
                                            <span class="text-xs px-2 py-0.5 rounded-full" style="@if($project->status === 'completed') background-color: color-mix(in srgb, var(--color-success) 15%, transparent); color: var(--color-success); @elseif($project->status === 'on_hold') background-color: color-mix(in srgb, #f59e0b 15%, transparent); color: #f59e0b; @else background-color: color-mix(in srgb, var(--color-accent) 15%, transparent); color: var(--color-accent); @endif font-family: var(--font-mono);">
                                                {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                            </span>
                                        </div>
                                        @if($project->description)
                                            <p class="text-xs mb-2" style="color: var(--color-text-muted);">{{ \Illuminate\Support\Str::limit($project->description, 120) }}</p>
                                        @endif
                                        <div class="text-xs flex flex-wrap gap-4" style="color: var(--color-text-muted); font-family: var(--font-mono);">
                                            <span>📄 {{ $project->srsDocuments->count() }} SRS</span>
                                            @if($project->start_date)
                                                <span>🗓️ Starts {{ $project->start_date->format('M d, Y') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm" style="color: var(--color-text-muted);">Projects you create inside GrowDev will appear here, along with their SRS documentation.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg transition focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="color: var(--color-text); border: 1px solid var(--color-border);" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="px-4 py-2 text-white rounded-lg transition focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-accent);" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </div>

                <!-- CV Preview Sidebar (Right Side) -->
                <div class="col-span-1">
                    <div class="sticky top-6 p-6 rounded-lg shadow" style="background-color: var(--color-surface); border: 1px solid var(--color-border);">
                        <h4 class="text-lg font-semibold mb-4" style="color: var(--color-text); font-family: var(--font-mono);">{{ __('Preview') }}</h4>
                        <div class="text-sm space-y-3" style="color: var(--color-text-muted);">
                            <div>
                                <div class="font-semibold" style="color: var(--color-text);" id="preview-name">{{ auth()->user()->name }}</div>
                                <div id="preview-email">{{ auth()->user()->email }}</div>
                            </div>
                            <div id="preview-location" class="text-xs">
                                @if (auth()->user()->location)
                                    📍 {{ auth()->user()->location }}
                                @endif
                            </div>
                            <div id="preview-phone" class="text-xs">
                                @if (auth()->user()->phone_number)
                                    📞 {{ auth()->user()->phone_number }}
                                @endif
                            </div>
                            <div id="preview-summary" class="text-xs line-clamp-3" style="color: var(--color-text-muted);">
                                @if (auth()->user()->professional_summary)
                                    {{ auth()->user()->professional_summary }}
                                @else
                                    <span class="italic" style="color: var(--color-text-muted); opacity: 0.6;">No summary added</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="sticky top-6 mt-4 p-6 rounded-lg shadow" style="background-color: var(--color-surface); border: 1px solid var(--color-border);">
                        <h4 class="text-lg font-semibold mb-4" style="color: var(--color-text); font-family: var(--font-mono);">{{ __('Notification Preferences') }}</h4>
                            <div class="text-sm space-y-3" style="color: var(--color-text-muted);">
                            <div class="flex items-center gap-2">
                                <input type="hidden" name="email_on_task_assigned" value="0">
                                <input type="checkbox" id="email_on_task_assigned" name="email_on_task_assigned" value="1" class="rounded focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="accent-color: var(--color-accent);" {{ old('email_on_task_assigned', $preference?->email_on_task_assigned ?? true) ? 'checked' : '' }}>
                                <label for="email_on_task_assigned" class="text-sm font-medium" style="color: var(--color-text);">{{ __('Email me when a task is assigned to me') }}</label>
                            </div>
                            <div class="mt-4 pt-4" style="border-top: 1px solid var(--color-border);">
                                <h5 class="text-sm font-semibold mb-2" style="color: var(--color-text); font-family: var(--font-mono);">{{ __('Digest Preview & History') }}</h5>
                                <button id="digest-preview-btn" class="px-3 py-1 text-white rounded" style="background-color: var(--color-accent);">{{ __('Preview Digest') }}</button>
                                <div id="digest-preview" class="mt-3 text-xs" style="color: var(--color-text);"></div>
                                <h6 class="mt-4 font-medium" style="color: var(--color-text); font-family: var(--font-mono);">{{ __('Recent Digests') }}</h6>
                                <div id="digest-history" class="mt-2 text-xs max-h-40 overflow-auto" style="color: var(--color-text-muted);"></div>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="hidden" name="email_on_task_status_change" value="0">
                                <input type="checkbox" id="email_on_task_status_change" name="email_on_task_status_change" value="1" class="rounded focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="accent-color: var(--color-accent);" {{ old('email_on_task_status_change', $preference?->email_on_task_status_change ?? true) ? 'checked' : '' }}>
                                <label for="email_on_task_status_change" class="text-sm font-medium" style="color: var(--color-text);">{{ __('Email me on task status changes') }}</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="hidden" name="email_reminders" value="0">
                                <input type="checkbox" id="email_reminders" name="email_reminders" value="1" class="rounded focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="accent-color: var(--color-accent);" {{ old('email_reminders', $preference?->email_reminders ?? true) ? 'checked' : '' }}>
                                <label for="email_reminders" class="text-sm font-medium" style="color: var(--color-text);">{{ __('Email me task reminders') }}</label>
                            </div>

                            <div class="mt-3">
                                <label for="digest_frequency" class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Notification Digest') }}</label>
                                <div class="grid grid-cols-2 gap-3 items-center">
                                    <select id="digest_frequency" name="digest_frequency"
                                        class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                        style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
                                        <option value="none" {{ old('digest_frequency', $preference?->digest_frequency ?? 'none') === 'none' ? 'selected' : '' }}>{{ __('None') }}</option>
                                        <option value="daily" {{ old('digest_frequency', $preference?->digest_frequency ?? 'none') === 'daily' ? 'selected' : '' }}>{{ __('Daily') }}</option>
                                        <option value="weekly" {{ old('digest_frequency', $preference?->digest_frequency ?? 'none') === 'weekly' ? 'selected' : '' }}>{{ __('Weekly') }}</option>
                                    </select>
                                    <input type="time" id="digest_time" name="digest_time" value="{{ old('digest_time', $preference?->digest_time) }}"
                                        class="px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                        style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="timezone" class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Timezone') }}</label>
                                <select id="timezone" name="timezone"
                                    class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                    style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
                                    @foreach (($timezones ?? []) as $tzId => $label)
                                        <option value="{{ $tzId }}" {{ old('timezone', $preference?->timezone) == $tzId ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="digest_day" class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Weekly digest day') }}</label>
                                <select id="digest_day" name="digest_day"
                                    class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                    style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
                                    <option value="" {{ old('digest_day', $preference?->digest_day) === null ? 'selected' : '' }}>{{ __('Select day') }}</option>
                                    <option value="sun" {{ old('digest_day', $preference?->digest_day) == 'sun' ? 'selected' : '' }}>{{ __('Sunday') }}</option>
                                    <option value="mon" {{ old('digest_day', $preference?->digest_day) == 'mon' ? 'selected' : '' }}>{{ __('Monday') }}</option>
                                    <option value="tue" {{ old('digest_day', $preference?->digest_day) == 'tue' ? 'selected' : '' }}>{{ __('Tuesday') }}</option>
                                    <option value="wed" {{ old('digest_day', $preference?->digest_day) == 'wed' ? 'selected' : '' }}>{{ __('Wednesday') }}</option>
                                    <option value="thu" {{ old('digest_day', $preference?->digest_day) == 'thu' ? 'selected' : '' }}>{{ __('Thursday') }}</option>
                                    <option value="fri" {{ old('digest_day', $preference?->digest_day) == 'fri' ? 'selected' : '' }}>{{ __('Friday') }}</option>
                                    <option value="sat" {{ old('digest_day', $preference?->digest_day) == 'sat' ? 'selected' : '' }}>{{ __('Saturday') }}</option>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label class="block text-sm font-medium mb-2" style="color: var(--color-text);">{{ __('Other email notifications') }}</label>
                                <div class="flex items-center gap-2">
                                    <input type="hidden" name="email_on_team_invitation" value="0">
                                    <input type="checkbox" id="email_on_team_invitation" name="email_on_team_invitation" value="1" class="rounded focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="accent-color: var(--color-accent);" {{ old('email_on_team_invitation', $preference?->email_on_team_invitation ?? true) ? 'checked' : '' }}>
                                    <label for="email_on_team_invitation" class="text-sm font-medium" style="color: var(--color-text);">{{ __('Email on team invitations') }}</label>
                                </div>
                                <div class="flex items-center gap-2 mt-2">
                                    <input type="hidden" name="email_on_srs_update" value="0">
                                    <input type="checkbox" id="email_on_srs_update" name="email_on_srs_update" value="1" class="rounded focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="accent-color: var(--color-accent);" {{ old('email_on_srs_update', $preference?->email_on_srs_update ?? true) ? 'checked' : '' }}>
                                    <label for="email_on_srs_update" class="text-sm font-medium" style="color: var(--color-text);">{{ __('Email on SRS updates') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Work Experience Management
        function addWorkExperience() {
            const container = document.getElementById('work-experiences');
            const index = container.children.length;
            const html = `
<div class="form-item p-4 rounded-lg" style="background-color: var(--color-surface-2); border: 1px solid var(--color-border);">
    <div class="flex justify-between items-start mb-3">
        <h4 class="font-semibold text-sm" style="color: var(--color-text); font-family: var(--font-mono);">Position #${index + 1}</h4>
        <button type="button" onclick="removeElement(this)" class="text-sm font-medium" style="color: var(--color-danger);">Remove</button>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">Job Title *</label>
            <input type="text" name="work_experiences[${index}][job_title]" required class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">Company Name *</label>
            <input type="text" name="work_experiences[${index}][company_name]" required class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">Start Date *</label>
            <input type="date" name="work_experiences[${index}][start_date]" required class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">End Date</label>
            <input type="date" name="work_experiences[${index}][end_date]" class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>
        <div class="col-span-2">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="work_experiences[${index}][currently_working]" value="1" class="rounded focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="accent-color: var(--color-accent);">
                <span class="text-sm font-medium" style="color: var(--color-text);">I currently work here</span>
            </label>
        </div>
        <div class="col-span-2">
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">Description</label>
            <textarea name="work_experiences[${index}][description]" rows="3" class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);"></textarea>
        </div>
    </div>
</div>`;
            container.insertAdjacentHTML('beforeend', html);
        }

        // Education Management
        function addEducation() {
            const container = document.getElementById('educations');
            const index = container.children.length;
            const html = `
<div class="form-item p-4 rounded-lg" style="background-color: var(--color-surface-2); border: 1px solid var(--color-border);">
    <div class="flex justify-between items-start mb-3">
        <h4 class="font-semibold text-sm" style="color: var(--color-text); font-family: var(--font-mono);">Education #${index + 1}</h4>
        <button type="button" onclick="removeElement(this)" class="text-sm font-medium" style="color: var(--color-danger);">Remove</button>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">School / University *</label>
            <input type="text" name="educations[${index}][school_name]" required class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">Degree *</label>
            <input type="text" name="educations[${index}][degree]" required placeholder="e.g., Bachelor, Master, Diploma" class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">Field of Study *</label>
            <input type="text" name="educations[${index}][field_of_study]" required placeholder="e.g., Computer Science" class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">Start Date *</label>
            <input type="date" name="educations[${index}][start_date]" required class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">End Date *</label>
            <input type="date" name="educations[${index}][end_date]" required class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>
        <div class="col-span-2">
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">Description</label>
            <textarea name="educations[${index}][description]" rows="3" placeholder="Additional details about your education" class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);"></textarea>
        </div>
    </div>
</div>`;
            container.insertAdjacentHTML('beforeend', html);
        }

        // Skills Management
        function addSkill() {
            const container = document.getElementById('skills');
            const index = container.children.length;
            const html = `
<div class="form-item p-3 rounded-lg flex items-end gap-3" style="background-color: var(--color-surface-2); border: 1px solid var(--color-border);">
    <div class="flex-1">
        <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">Skill Name *</label>
        <input type="text" name="skills[${index}][skill_name]" required placeholder="e.g., JavaScript, Laravel, React" class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
    </div>
    <div class="flex-1">
        <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">Proficiency *</label>
        <select name="skills[${index}][proficiency]" required class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
            <option value="">Select level</option>
            <option value="beginner">Beginner</option>
            <option value="intermediate">Intermediate</option>
            <option value="advanced">Advanced</option>
            <option value="expert">Expert</option>
        </select>
    </div>
    <div>
        <button type="button" onclick="removeElement(this)" class="px-3 py-2 rounded-lg transition" style="color: var(--color-danger);">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>`;
            container.insertAdjacentHTML('beforeend', html);
        }

        // Certifications Management
        function addCertification() {
            const container = document.getElementById('certifications');
            const index = container.children.length;
            const html = `
<div class="form-item p-4 rounded-lg" style="background-color: var(--color-surface-2); border: 1px solid var(--color-border);">
    <div class="flex justify-between items-start mb-3">
        <h4 class="font-semibold text-sm" style="color: var(--color-text); font-family: var(--font-mono);">Certification #${index + 1}</h4>
        <button type="button" onclick="removeElement(this)" class="text-sm font-medium" style="color: var(--color-danger);">Remove</button>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">Certification Name *</label>
            <input type="text" name="certifications[${index}][certification_name]" required class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">Issuer *</label>
            <input type="text" name="certifications[${index}][issuer]" required class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">Issue Date *</label>
            <input type="date" name="certifications[${index}][issue_date]" required class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">Expiry Date</label>
            <input type="date" name="certifications[${index}][expiry_date]" class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>
        <div class="col-span-2">
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">Credential URL</label>
            <input type="url" name="certifications[${index}][credential_url]" placeholder="https://..." class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>
        <div class="col-span-2">
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">Description</label>
            <textarea name="certifications[${index}][description]" rows="3" placeholder="Additional details about this certification" class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);"></textarea>
        </div>
    </div>
</div>`;
            container.insertAdjacentHTML('beforeend', html);
        }

        // Manual Projects Management
        function addManualProject() {
            const container = document.getElementById('manual-projects');
            if (!container) return;
            const index = container.querySelectorAll('.form-item').length;
            const html = `
<div class="form-item p-4 rounded-lg" style="background-color: var(--color-surface-2); border: 1px solid var(--color-border);">
    <div class="flex justify-between items-start mb-3">
        <h4 class="font-semibold text-sm" style="color: var(--color-text); font-family: var(--font-mono);">Project #${index + 1}</h4>
        <button type="button" onclick="removeElement(this)" class="text-sm font-medium" style="color: var(--color-danger);">Remove</button>
    </div>
    <input type="hidden" name="projects_manual[${index}][id]" value="">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">Project Name *</label>
            <input type="text" name="projects_manual[${index}][name]" required class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">Status *</label>
            <select name="projects_manual[${index}][status]" required class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
                <option value="active">Active</option>
                <option value="on_hold">On Hold</option>
                <option value="completed">Completed</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">Type *</label>
            <select name="projects_manual[${index}][type]" required class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
                <option value="solo">Solo</option>
                <option value="team">Team</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">Start Date</label>
            <input type="date" name="projects_manual[${index}][start_date]" class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">End Date</label>
            <input type="date" name="projects_manual[${index}][end_date]" class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">Description</label>
            <textarea name="projects_manual[${index}][description]" rows="3" class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);"></textarea>
        </div>
    </div>
</div>`;
            container.insertAdjacentHTML('beforeend', html);
        }

        // Remove Element
        function removeElement(element) {
            if (confirm('Are you sure?')) {
                element.closest('.form-item').remove();
            }
        }

        // Generate PDF
        function generatePDF() {
            window.location.href = '{{ route('profile.cv-pdf') }}';
        }

        // Update preview as user types
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const locationInput = document.getElementById('location');
            const phoneInput = document.getElementById('phone_number');
            const summaryInput = document.getElementById('professional_summary');

            if (nameInput) nameInput.addEventListener('input', function() {
                document.getElementById('preview-name').textContent = this.value || '{{ auth()->user()->name }}';
            });

            if (emailInput) emailInput.addEventListener('input', function() {
                document.getElementById('preview-email').textContent = this.value || '{{ auth()->user()->email }}';
            });

            if (locationInput) locationInput.addEventListener('input', function() {
                document.getElementById('preview-location').textContent = this.value ? '📍 ' + this.value : '';
            });

            if (phoneInput) phoneInput.addEventListener('input', function() {
                document.getElementById('preview-phone').textContent = this.value ? '📞 ' + this.value : '';
            });

            if (summaryInput) summaryInput.addEventListener('input', function() {
                document.getElementById('preview-summary').textContent = this.value || 'No summary added';
            });
        });

        // Toggle digest time input based on frequency selection
        document.addEventListener('DOMContentLoaded', function() {
            const digestSelect = document.getElementById('digest_frequency');
            const digestTime = document.getElementById('digest_time');
            const digestDay = document.getElementById('digest_day');
            function toggleDigestTime() {
                if (!digestSelect || !digestTime) return;
                const val = digestSelect.value;
                digestTime.disabled = (val === 'none');
                digestTime.classList.toggle('opacity-50', val === 'none');
                if (digestDay) {
                    digestDay.disabled = (val !== 'weekly');
                    digestDay.classList.toggle('opacity-50', val !== 'weekly');
                }
            }
            if (digestSelect) {
                digestSelect.addEventListener('change', toggleDigestTime);
            }
            toggleDigestTime();
        });
        // Digest preview and history
        document.addEventListener('DOMContentLoaded', function() {
            const previewBtn = document.getElementById('digest-preview-btn');
            const previewDiv = document.getElementById('digest-preview');
            const historyDiv = document.getElementById('digest-history');

            if (previewBtn) {
                previewBtn.addEventListener('click', function() {
                    fetch('{{ route('profile.digests.preview') }}')
                        .then(res => res.json())
                        .then(res => {
                            if (!res.success) return;
                            const items = res.data;
                            if (items.length === 0) {
                                previewDiv.textContent = '{{ __('No pending digest events') }}';
                                return;
                            }
                            previewDiv.innerHTML = '';
                            items.forEach(e => {
                                const div = document.createElement('div');
                                div.className = 'p-2';
                                div.style.borderBottom = '1px solid var(--color-border)';
                                div.textContent = e.event_type + ' - ' + (e.payload && e.payload.task_id ? 'Task #' + e.payload.task_id : '');
                                previewDiv.appendChild(div);
                            });
                        });
                });
            }

            // load recent history
            fetch('{{ route('profile.digests.history') }}')
                .then(res => res.json())
                .then(res => {
                    if (!res.success) return;
                    const items = res.data;
                    if (!items || items.length === 0) {
                        historyDiv.textContent = '{{ __('No digests in history') }}';
                        return;
                    }
                    historyDiv.innerHTML = '';
                    items.forEach(e => {
                        const div = document.createElement('div');
                        div.className = 'p-2';
                        div.style.borderBottom = '1px solid var(--color-border)';
                        div.textContent = `${new Date(e.updated_at).toLocaleString()} - ${e.event_type}`;
                        historyDiv.appendChild(div);
                    });
                });
        });
    </script>
</x-app-layout>
