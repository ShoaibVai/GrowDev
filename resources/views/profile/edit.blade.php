<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Profile & CV') }}
            </h2>
            <button type="button" onclick="generatePDF()" class="whitespace-nowrap inline-flex items-center px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold shadow-md">
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
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="text-red-800 font-semibold mb-2">{{ __('Please correct the following errors:') }}</div>
                    <ul class="list-disc list-inside text-red-700 text-sm">
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
                    <div class="p-6 bg-white shadow sm:rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Personal Information') }}</h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Name -->
                            <div class="col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Full Name') }} *</label>
                                <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @error('name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email') }} *</label>
                                <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @error('email')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Phone Number') }}</label>
                                <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number', auth()->user()->phone_number) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @error('phone_number')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>

                            <!-- Location -->
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Location') }}</label>
                                <input type="text" id="location" name="location" value="{{ old('location', auth()->user()->location) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @error('location')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>

                            <!-- Website -->
                            <div>
                                <label for="website" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Website') }}</label>
                                <input type="url" id="website" name="website" value="{{ old('website', auth()->user()->website) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @error('website')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>

                            <!-- LinkedIn -->
                            <div>
                                <label for="linkedin_url" class="block text-sm font-medium text-gray-700 mb-1">{{ __('LinkedIn URL') }}</label>
                                <input type="url" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', auth()->user()->linkedin_url) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @error('linkedin_url')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>

                            <!-- GitHub -->
                            <div>
                                <label for="github_url" class="block text-sm font-medium text-gray-700 mb-1">{{ __('GitHub URL') }}</label>
                                <input type="url" id="github_url" name="github_url" value="{{ old('github_url', auth()->user()->github_url) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @error('github_url')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>

                            <!-- Professional Summary -->
                            <div class="col-span-2">
                                <label for="professional_summary" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Professional Summary') }}</label>
                                <textarea id="professional_summary" name="professional_summary" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('professional_summary', auth()->user()->professional_summary) }}</textarea>
                                @error('professional_summary')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Work Experience Section -->
                    <div class="p-6 bg-white shadow sm:rounded-lg">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ __('Work Experience') }}</h3>
                            <button type="button" onclick="addWorkExperience()" class="px-3 py-1 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700 transition">
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
                    <div class="p-6 bg-white shadow sm:rounded-lg">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ __('Education') }}</h3>
                            <button type="button" onclick="addEducation()" class="px-3 py-1 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700 transition">
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
                    <div class="p-6 bg-white shadow sm:rounded-lg">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ __('Skills') }}</h3>
                            <button type="button" onclick="addSkill()" class="px-3 py-1 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700 transition">
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
                    <div class="p-6 bg-white shadow sm:rounded-lg">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ __('Certifications') }}</h3>
                            <button type="button" onclick="addCertification()" class="px-3 py-1 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700 transition">
                                + {{ __('Add') }}
                            </button>
                        </div>
                        <div id="certifications" class="space-y-4">
                            @foreach (auth()->user()->certifications as $index => $cert)
                                @include('profile.partials.certification-item', ['index' => $index, 'cert' => $cert])
                            @endforeach
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </div>

                <!-- CV Preview Sidebar (Right Side) -->
                <div class="col-span-1">
                    <div class="sticky top-6 p-6 bg-white shadow sm:rounded-lg">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Preview') }}</h4>
                        <div class="text-sm text-gray-600 space-y-3">
                            <div>
                                <div class="font-semibold text-gray-900" id="preview-name">{{ auth()->user()->name }}</div>
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
                            <div id="preview-summary" class="text-xs text-gray-500 line-clamp-3">
                                @if (auth()->user()->professional_summary)
                                    {{ auth()->user()->professional_summary }}
                                @else
                                    <span class="italic text-gray-400">No summary added</span>
                                @endif
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
<div class="form-item p-4 bg-gray-50 rounded-lg border border-gray-200">
    <div class="flex justify-between items-start mb-3">
        <h4 class="font-semibold text-gray-800">Position #${index + 1}</h4>
        <button type="button" onclick="removeElement(this)" class="text-red-600 hover:text-red-800 text-sm font-medium">Remove</button>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Job Title *</label>
            <input type="text" name="work_experiences[${index}][job_title]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Company Name *</label>
            <input type="text" name="work_experiences[${index}][company_name]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
            <input type="date" name="work_experiences[${index}][start_date]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
            <input type="date" name="work_experiences[${index}][end_date]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div class="col-span-2">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="work_experiences[${index}][currently_working]" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <span class="text-sm font-medium text-gray-700">I currently work here</span>
            </label>
        </div>
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="work_experiences[${index}][description]" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
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
<div class="form-item p-4 bg-gray-50 rounded-lg border border-gray-200">
    <div class="flex justify-between items-start mb-3">
        <h4 class="font-semibold text-gray-800">Education #${index + 1}</h4>
        <button type="button" onclick="removeElement(this)" class="text-red-600 hover:text-red-800 text-sm font-medium">Remove</button>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">School / University *</label>
            <input type="text" name="educations[${index}][school_name]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Degree *</label>
            <input type="text" name="educations[${index}][degree]" required placeholder="e.g., Bachelor, Master, Diploma" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Field of Study *</label>
            <input type="text" name="educations[${index}][field_of_study]" required placeholder="e.g., Computer Science" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
            <input type="date" name="educations[${index}][start_date]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">End Date *</label>
            <input type="date" name="educations[${index}][end_date]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="educations[${index}][description]" rows="3" placeholder="Additional details about your education" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
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
<div class="form-item p-3 bg-gray-50 rounded-lg border border-gray-200 flex items-end gap-3">
    <div class="flex-1">
        <label class="block text-sm font-medium text-gray-700 mb-1">Skill Name *</label>
        <input type="text" name="skills[${index}][skill_name]" required placeholder="e.g., JavaScript, Laravel, React" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
    </div>
    <div class="flex-1">
        <label class="block text-sm font-medium text-gray-700 mb-1">Proficiency *</label>
        <select name="skills[${index}][proficiency]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">Select level</option>
            <option value="beginner">Beginner</option>
            <option value="intermediate">Intermediate</option>
            <option value="advanced">Advanced</option>
            <option value="expert">Expert</option>
        </select>
    </div>
    <div>
        <button type="button" onclick="removeElement(this)" class="px-3 py-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition">
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
<div class="form-item p-4 bg-gray-50 rounded-lg border border-gray-200">
    <div class="flex justify-between items-start mb-3">
        <h4 class="font-semibold text-gray-800">Certification #${index + 1}</h4>
        <button type="button" onclick="removeElement(this)" class="text-red-600 hover:text-red-800 text-sm font-medium">Remove</button>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Certification Name *</label>
            <input type="text" name="certifications[${index}][certification_name]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Issuer *</label>
            <input type="text" name="certifications[${index}][issuer]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Issue Date *</label>
            <input type="date" name="certifications[${index}][issue_date]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
            <input type="date" name="certifications[${index}][expiry_date]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Credential URL</label>
            <input type="url" name="certifications[${index}][credential_url]" placeholder="https://..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="certifications[${index}][description]" rows="3" placeholder="Additional details about this certification" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
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
    </script>
</x-app-layout>
