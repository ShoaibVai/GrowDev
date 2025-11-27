<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📋 {{ __('Create SRS Document') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('documentation.srs.store') }}" class="space-y-8">
                @csrf

                <!-- Document Header -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-4">
                        📄 Document Header
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Project Name *</label>
                            <input type="text" id="title" name="title" required 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   placeholder="e.g., E-Commerce Platform"
                                   value="{{ old('title') }}">
                            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="version" class="block text-sm font-medium text-gray-700 mb-2">Version</label>
                            <input type="text" id="version" name="version" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   value="{{ old('version', '1.0') }}" placeholder="1.0">
                        </div>
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                            <input type="date" id="date" name="date" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   value="{{ old('date', date('Y-m-d')) }}">
                        </div>
                        <div>
                            <label for="authors" class="block text-sm font-medium text-gray-700 mb-2">Author(s)</label>
                            <input type="text" id="authors" name="authors" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   value="{{ old('authors', auth()->user()->name) }}" placeholder="Author Name">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="status" name="status" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="draft" @selected(old('status') === 'draft')>Draft</option>
                                <option value="review" @selected(old('status') === 'review')>In Review</option>
                                <option value="approved" @selected(old('status') === 'approved')>Approved</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 1: Introduction -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-4">
                        <span class="text-indigo-600">1.</span> Introduction
                    </h2>

                    <div class="mb-6">
                        <label for="purpose" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">1.1</span> Purpose
                        </label>
                        <textarea id="purpose" name="purpose" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Describe the purpose of this SRS document and the software product...">{{ old('purpose') }}</textarea>
                        @error('purpose') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="document_conventions" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">1.2</span> Document Conventions
                        </label>
                        <textarea id="document_conventions" name="document_conventions" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Describe any standards or typographical conventions used...">{{ old('document_conventions') }}</textarea>
                        @error('document_conventions') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="intended_audience" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">1.3</span> Intended Audience and Reading Suggestions
                        </label>
                        <textarea id="intended_audience" name="intended_audience" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="List the different types of readers and suggest which sections are most relevant to each...">{{ old('intended_audience') }}</textarea>
                        @error('intended_audience') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="product_scope" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">1.4</span> Product Scope
                        </label>
                        <textarea id="product_scope" name="product_scope" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Provide a short description of the software, its objectives, and how it supports business goals...">{{ old('product_scope') }}</textarea>
                        @error('product_scope') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="references" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">1.5</span> References
                        </label>
                        <textarea id="references" name="references" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="List any other documents or web addresses this SRS refers to...">{{ old('references') }}</textarea>
                        @error('references') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Section 2: Overall Description -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-4">
                        <span class="text-indigo-600">2.</span> Overall Description
                    </h2>

                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">2.1</span> Product Description
                        </label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Brief description of the product/project...">{{ old('description') }}</textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="product_perspective" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">2.2</span> Product Perspective
                        </label>
                        <textarea id="product_perspective" name="product_perspective" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Describe how the product fits into the larger system context...">{{ old('product_perspective') }}</textarea>
                        @error('product_perspective') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="product_features" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">2.3</span> Product Features
                        </label>
                        <textarea id="product_features" name="product_features" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Summarize the major features and capabilities of the software...">{{ old('product_features') }}</textarea>
                        @error('product_features') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="user_classes" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">2.4</span> User Classes and Characteristics
                        </label>
                        <textarea id="user_classes" name="user_classes" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Identify the various user classes that will use the system...">{{ old('user_classes') }}</textarea>
                        @error('user_classes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="operating_environment" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">2.5</span> Operating Environment
                        </label>
                        <textarea id="operating_environment" name="operating_environment" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Describe the environment in which the software will operate...">{{ old('operating_environment') }}</textarea>
                        @error('operating_environment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="design_constraints" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">2.6</span> Design and Implementation Constraints
                        </label>
                        <textarea id="design_constraints" name="design_constraints" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Describe any items that will limit developer options...">{{ old('design_constraints') }}</textarea>
                        @error('design_constraints') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="constraints" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-indigo-600 font-semibold">2.7</span> Constraints
                            </label>
                            <textarea id="constraints" name="constraints" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                      placeholder="List any project constraints...">{{ old('constraints') }}</textarea>
                            @error('constraints') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="assumptions" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-indigo-600 font-semibold">2.8</span> Assumptions and Dependencies
                            </label>
                            <textarea id="assumptions" name="assumptions" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                      placeholder="List any assumptions and dependencies...">{{ old('assumptions') }}</textarea>
                            @error('assumptions') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Specific Requirements -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-4">
                        <span class="text-indigo-600">3.</span> Specific Requirements
                    </h2>

                    <!-- 3.1 Functional Requirements -->
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">
                                <span class="text-indigo-600">3.1</span> Functional Requirements
                            </h3>
                            <button type="button" id="add-fr-btn" class="px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition text-sm font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Add Functional Requirement
                            </button>
                        </div>
                        <div id="fr-container" class="space-y-4">
                            <!-- Dynamic FR rows will be added here -->
                        </div>
                    </div>

                    <!-- 3.2 Non-Functional Requirements -->
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">
                                <span class="text-indigo-600">3.2</span> Non-Functional Requirements
                            </h3>
                            <button type="button" id="add-nfr-btn" class="px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition text-sm font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Add Non-Functional Requirement
                            </button>
                        </div>
                        <div id="nfr-container" class="space-y-4">
                            <!-- Dynamic NFR rows will be added here -->
                        </div>
                    </div>

                    <!-- 3.3 External Interface Requirements -->
                    <div class="mb-6">
                        <label for="external_interfaces" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">3.3</span> External Interface Requirements
                        </label>
                        <textarea id="external_interfaces" name="external_interfaces" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Describe user interfaces, hardware interfaces, software interfaces, and communication interfaces...">{{ old('external_interfaces') }}</textarea>
                        @error('external_interfaces') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>



                <!-- Buttons -->
                <div class="flex gap-4 pt-6">
                    <a href="{{ route('documentation.srs.index') }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="button" id="generate-json-btn"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                        📄 Generate SRS Data
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold">
                        ✅ Create SRS Document
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Templates -->
    <template id="fr-row-template">
        <div class="fr-row grid grid-cols-1 md:grid-cols-12 gap-4 mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50 relative group transition-all duration-300 ease-in-out hover:shadow-md">
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">ID</label>
                <input type="text" name="functional_requirements[][id]" class="fr-id w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="FR-001">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Priority</label>
                <select name="functional_requirements[][priority]" class="fr-priority w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="Must-have">Must-have</option>
                    <option value="Should-have">Should-have</option>
                    <option value="Could-have">Could-have</option>
                    <option value="Won't-have">Won't-have</option>
                </select>
            </div>
            <div class="md:col-span-4">
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Description</label>
                <textarea name="functional_requirements[][description]" rows="2" class="fr-desc w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Requirement description..."></textarea>
            </div>
            <div class="md:col-span-3">
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Acceptance Criteria</label>
                <textarea name="functional_requirements[][acceptance]" rows="2" class="fr-criteria w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Criteria..."></textarea>
            </div>
            <div class="md:col-span-1 flex items-end justify-end">
                <button type="button" class="remove-row-btn text-red-400 hover:text-red-600 p-2 rounded-full hover:bg-red-50 transition" title="Remove Requirement">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </template>

    <template id="nfr-row-template">
        <div class="nfr-row grid grid-cols-1 md:grid-cols-12 gap-4 mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50 relative group transition-all duration-300 ease-in-out hover:shadow-md">
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Type</label>
                <select name="non_functional_requirements[][type]" class="nfr-type w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="Performance">Performance</option>
                    <option value="Security">Security</option>
                    <option value="Reliability">Reliability</option>
                    <option value="Usability">Usability</option>
                    <option value="Scalability">Scalability</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">ID</label>
                <input type="text" name="non_functional_requirements[][id]" class="nfr-id w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="NFR-001">
            </div>
            <div class="md:col-span-5">
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Description</label>
                <textarea name="non_functional_requirements[][description]" rows="2" class="nfr-desc w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Requirement description..."></textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Priority</label>
                <select name="non_functional_requirements[][priority]" class="nfr-priority w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="Must-have">Must-have</option>
                    <option value="Should-have">Should-have</option>
                    <option value="Could-have">Could-have</option>
                    <option value="Won't-have">Won't-have</option>
                </select>
            </div>
            <div class="md:col-span-1 flex items-end justify-end">
                <button type="button" class="remove-row-btn text-red-400 hover:text-red-600 p-2 rounded-full hover:bg-red-50 transition" title="Remove Requirement">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const frContainer = document.getElementById('fr-container');
            const nfrContainer = document.getElementById('nfr-container');
            const addFrBtn = document.getElementById('add-fr-btn');
            const addNfrBtn = document.getElementById('add-nfr-btn');
            const generateJsonBtn = document.getElementById('generate-json-btn');
            const frTemplate = document.getElementById('fr-row-template');
            const nfrTemplate = document.getElementById('nfr-row-template');

            // Add Functional Requirement
            addFrBtn.addEventListener('click', function() {
                const clone = frTemplate.content.cloneNode(true);
                frContainer.appendChild(clone);
            });

            // Add Non-Functional Requirement
            addNfrBtn.addEventListener('click', function() {
                const clone = nfrTemplate.content.cloneNode(true);
                nfrContainer.appendChild(clone);
            });

            // Remove Row (Event Delegation)
            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-row-btn')) {
                    const row = e.target.closest('.fr-row') || e.target.closest('.nfr-row');
                    if (row) {
                        row.remove();
                    }
                }
            });

            // Generate SRS Data
            generateJsonBtn.addEventListener('click', function() {
                const form = document.querySelector('form');
                const formData = new FormData(form);
                const srsData = {
                    staticData: {
                        title: formData.get('title'),
                        purpose: formData.get('purpose'),
                        document_conventions: formData.get('document_conventions'),
                        intended_audience: formData.get('intended_audience'),
                        product_scope: formData.get('product_scope'),
                        references: formData.get('references'),
                        description: formData.get('description'),
                        product_perspective: formData.get('product_perspective'),
                        product_features: formData.get('product_features'),
                        user_classes: formData.get('user_classes'),
                        operating_environment: formData.get('operating_environment'),
                        design_constraints: formData.get('design_constraints'),
                        constraints: formData.get('constraints'),
                        assumptions: formData.get('assumptions'),
                        external_interfaces: formData.get('external_interfaces'),
                        version: formData.get('version'),
                        status: formData.get('status'),
                    },
                    functionalRequirements: [],
                    nonFunctionalRequirements: []
                };

                // Collect Functional Requirements
                const frRows = document.querySelectorAll('.fr-row');
                frRows.forEach(row => {
                    srsData.functionalRequirements.push({
                        id: row.querySelector('.fr-id').value,
                        priority: row.querySelector('.fr-priority').value,
                        description: row.querySelector('.fr-desc').value,
                        acceptanceCriteria: row.querySelector('.fr-criteria').value
                    });
                });

                // Collect Non-Functional Requirements
                const nfrRows = document.querySelectorAll('.nfr-row');
                nfrRows.forEach(row => {
                    srsData.nonFunctionalRequirements.push({
                        type: row.querySelector('.nfr-type').value,
                        id: row.querySelector('.nfr-id').value,
                        description: row.querySelector('.nfr-desc').value,
                        priority: row.querySelector('.nfr-priority').value
                    });
                });

                console.log('SRS Data Generated:', srsData);
                alert('SRS Data generated and logged to console! (F12 to view)');
            });
        });
    </script>
</x-app-layout>
