<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📋 {{ __('Edit SRS Document') }}
            </h2>
            <span class="px-3 py-1 text-sm rounded-full 
                {{ $srsDocument->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : '' }}
                {{ $srsDocument->status === 'review' ? 'bg-blue-100 text-blue-800' : '' }}
                {{ $srsDocument->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                {{ $srsDocument->status === 'final' ? 'bg-purple-100 text-purple-800' : '' }}">
                v{{ $srsDocument->version ?? '1.0' }} - {{ ucfirst($srsDocument->status ?? 'draft') }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('documentation.srs.update', $srsDocument) }}" class="space-y-8" id="srs-form">
                @csrf
                @method('PUT')

                <!-- Section 1: Introduction -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-4">
                        <span class="text-indigo-600">1.</span> Introduction
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-indigo-600 font-semibold">1.1</span> Document Title *
                            </label>
                            <input type="text" id="title" name="title" required value="{{ $srsDocument->title }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="version" class="block text-sm font-medium text-gray-700 mb-2">Version</label>
                                <input type="text" id="version" name="version" value="{{ $srsDocument->version ?? '1.0' }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    <option value="draft" @selected(($srsDocument->status ?? 'draft') === 'draft')>Draft</option>
                                    <option value="review" @selected(($srsDocument->status ?? 'draft') === 'review')>Under Review</option>
                                    <option value="approved" @selected(($srsDocument->status ?? 'draft') === 'approved')>Approved</option>
                                    <option value="final" @selected(($srsDocument->status ?? 'draft') === 'final')>Final</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="purpose" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">1.2</span> Purpose
                        </label>
                        <textarea id="purpose" name="purpose" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                  placeholder="Describe the purpose of this SRS document...">{{ $srsDocument->purpose }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label for="document_conventions" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">1.3</span> Document Conventions
                        </label>
                        <textarea id="document_conventions" name="document_conventions" rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                  placeholder="Standards or typographical conventions used...">{{ $srsDocument->document_conventions }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="intended_audience" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-indigo-600 font-semibold">1.4</span> Intended Audience
                            </label>
                            <textarea id="intended_audience" name="intended_audience" rows="2"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                      placeholder="Target readers and reading suggestions...">{{ $srsDocument->intended_audience }}</textarea>
                        </div>
                        <div>
                            <label for="product_scope" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-indigo-600 font-semibold">1.5</span> Product Scope
                            </label>
                            <textarea id="product_scope" name="product_scope" rows="2"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                      placeholder="Software objectives and business goals...">{{ $srsDocument->product_scope }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="references" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-indigo-600 font-semibold">1.6</span> References
                        </label>
                        <textarea id="references" name="references" rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                  placeholder="Related documents and references...">{{ $srsDocument->references }}</textarea>
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
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ $srsDocument->description }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="product_perspective" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-indigo-600 font-semibold">2.2</span> Product Perspective
                            </label>
                            <textarea id="product_perspective" name="product_perspective" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                      placeholder="How the product fits into larger context...">{{ $srsDocument->product_perspective }}</textarea>
                        </div>
                        <div>
                            <label for="product_features" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-indigo-600 font-semibold">2.3</span> Product Features
                            </label>
                            <textarea id="product_features" name="product_features" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                      placeholder="Major features and capabilities...">{{ $srsDocument->product_features }}</textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="user_classes" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-indigo-600 font-semibold">2.4</span> User Classes and Characteristics
                            </label>
                            <textarea id="user_classes" name="user_classes" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                      placeholder="User types and their characteristics...">{{ $srsDocument->user_classes }}</textarea>
                        </div>
                        <div>
                            <label for="operating_environment" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-indigo-600 font-semibold">2.5</span> Operating Environment
                            </label>
                            <textarea id="operating_environment" name="operating_environment" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                      placeholder="Hardware, OS, and platform requirements...">{{ $srsDocument->operating_environment }}</textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="design_constraints" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-indigo-600 font-semibold">2.6</span> Design Constraints
                            </label>
                            <textarea id="design_constraints" name="design_constraints" rows="2"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ $srsDocument->design_constraints }}</textarea>
                        </div>
                        <div>
                            <label for="constraints" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-indigo-600 font-semibold">2.7</span> Constraints
                            </label>
                            <textarea id="constraints" name="constraints" rows="2"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ $srsDocument->constraints }}</textarea>
                        </div>
                        <div>
                            <label for="assumptions" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-indigo-600 font-semibold">2.8</span> Assumptions
                            </label>
                            <textarea id="assumptions" name="assumptions" rows="2"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ $srsDocument->assumptions }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Section 3: External Interface Requirements -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-4">
                        <span class="text-indigo-600">3.</span> External Interface Requirements
                    </h2>
                    <div class="mb-6">
                        <label for="external_interfaces" class="block text-sm font-medium text-gray-700 mb-2">
                            External Interfaces Description
                        </label>
                        <textarea id="external_interfaces" name="external_interfaces" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                  placeholder="Describe user interfaces, hardware interfaces, software interfaces, and communication interfaces...">{{ $srsDocument->external_interfaces }}</textarea>
                    </div>
                </div>

                <!-- Section 4: Functional Requirements -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <div class="flex justify-between items-center mb-6 border-b pb-4">
                        <h2 class="text-2xl font-bold text-gray-900">
                            <span class="text-indigo-600">4.</span> Functional Requirements
                        </h2>
                        <button type="button" onclick="addRequirement('functional')" 
                                class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-semibold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Requirement
                        </button>
                    </div>

                    <p class="text-sm text-gray-600 mb-4 bg-blue-50 p-3 rounded-lg">
                        💡 <strong>Hierarchical Numbering:</strong> Use section numbers like 4.1, 4.1.1, 4.1.2, 4.2, etc. 
                        Sub-requirements will be automatically nested under their parent sections.
                    </p>

                    <div id="functional-requirements-container" class="space-y-4">
                        @forelse ($functionalRequirements as $index => $req)
                            @include('documentation.srs.partials.functional-requirement', [
                                'index' => $index,
                                'req' => $req,
                                'type' => 'functional'
                            ])
                        @empty
                            <div class="empty-message text-gray-500 text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p>No functional requirements added yet.</p>
                                <p class="text-sm mt-1">Click "Add Requirement" to get started.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Section 5: Non-Functional Requirements -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <div class="flex justify-between items-center mb-6 border-b pb-4">
                        <h2 class="text-2xl font-bold text-gray-900">
                            <span class="text-indigo-600">5.</span> Non-Functional Requirements
                        </h2>
                        <button type="button" onclick="addRequirement('non_functional')" 
                                class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition font-semibold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Requirement
                        </button>
                    </div>

                    <p class="text-sm text-gray-600 mb-4 bg-purple-50 p-3 rounded-lg">
                        💡 <strong>Categories:</strong> Performance, Security, Reliability, Availability, Maintainability, 
                        Scalability, Usability, Compatibility, Compliance.
                    </p>

                    <div id="non-functional-requirements-container" class="space-y-4">
                        @forelse ($nonFunctionalRequirements as $index => $req)
                            @include('documentation.srs.partials.non-functional-requirement', [
                                'index' => $index,
                                'req' => $req,
                                'categories' => $nfrCategories
                            ])
                        @empty
                            <div class="empty-message text-gray-500 text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <p>No non-functional requirements added yet.</p>
                                <p class="text-sm mt-1">Click "Add Requirement" to get started.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Section 6: Other Requirements -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-4">
                        <span class="text-indigo-600">6.</span> Other Requirements
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="data_requirements" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-indigo-600 font-semibold">6.1</span> Data Requirements
                            </label>
                            <textarea id="data_requirements" name="data_requirements" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                      placeholder="Database requirements, data formats, retention policies...">{{ $srsDocument->data_requirements }}</textarea>
                        </div>
                        <div>
                            <label for="dependencies" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-indigo-600 font-semibold">6.2</span> Dependencies
                            </label>
                            <textarea id="dependencies" name="dependencies" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                      placeholder="External dependencies and integrations...">{{ $srsDocument->dependencies }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Appendices -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-4">
                        📎 Appendices
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="glossary" class="block text-sm font-medium text-gray-700 mb-2">
                                Glossary
                            </label>
                            <textarea id="glossary" name="glossary" rows="4"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                      placeholder="Define technical terms and acronyms...">{{ $srsDocument->glossary }}</textarea>
                        </div>
                        <div>
                            <label for="appendices" class="block text-sm font-medium text-gray-700 mb-2">
                                Additional Appendices
                            </label>
                            <textarea id="appendices" name="appendices" rows="4"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                      placeholder="Additional supporting information...">{{ $srsDocument->appendices }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-4 pt-6 sticky bottom-0 bg-gray-100 p-4 rounded-lg shadow-lg">
                    <a href="{{ route('documentation.srs.index') }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        ← Back
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold">
                        💾 Save SRS
                    </button>
                    <a href="{{ route('documentation.srs.pdf', $srsDocument) }}" 
                       class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                        📥 Export PDF
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Store categories for non-functional requirements
        const nfrCategories = @json($nfrCategories);
        
        let functionalCounter = {{ $functionalRequirements->count() }};
        let nonFunctionalCounter = {{ $nonFunctionalRequirements->count() }};

        function addRequirement(type) {
            const container = document.getElementById(`${type.replace('_', '-')}-requirements-container`);
            const emptyMessage = container.querySelector('.empty-message');
            if (emptyMessage) emptyMessage.remove();

            const index = type === 'functional' ? functionalCounter++ : nonFunctionalCounter++;
            const baseSection = type === 'functional' ? '4' : '5';
            
            // Calculate the next section number
            const existingItems = container.querySelectorAll('.requirement-item');
            let nextNumber = existingItems.length + 1;
            const sectionNumber = `${baseSection}.${nextNumber}`;

            const html = type === 'functional' 
                ? createFunctionalRequirementHtml(index, sectionNumber)
                : createNonFunctionalRequirementHtml(index, sectionNumber);
            
            container.insertAdjacentHTML('beforeend', html);
        }

        function addSubRequirement(button, type) {
            const parentItem = button.closest('.requirement-item');
            const parentSection = parentItem.querySelector('[name$="[section_number]"]').value;
            const childrenContainer = parentItem.querySelector('.children-container');
            
            // Count existing children to determine next number
            const existingChildren = childrenContainer.querySelectorAll(':scope > .requirement-item');
            const nextChildNumber = existingChildren.length + 1;
            const newSectionNumber = `${parentSection}.${nextChildNumber}`;

            const index = type === 'functional' ? functionalCounter++ : nonFunctionalCounter++;
            
            const html = type === 'functional' 
                ? createFunctionalRequirementHtml(index, newSectionNumber, parentSection)
                : createNonFunctionalRequirementHtml(index, newSectionNumber, parentSection);
            
            childrenContainer.insertAdjacentHTML('beforeend', html);
        }

        function createFunctionalRequirementHtml(index, sectionNumber, parentSection = '') {
            return `
                <div class="requirement-item p-4 bg-gray-50 rounded-lg border border-gray-200 ${parentSection ? 'ml-8 border-l-4 border-l-indigo-300' : ''}" data-index="${index}">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Section #</label>
                            <input type="text" name="functional_requirements[${index}][section_number]" 
                                   value="${sectionNumber}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 font-mono text-indigo-600 font-bold">
                            <input type="hidden" name="functional_requirements[${index}][parent_section]" value="${parentSection}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Requirement ID</label>
                            <input type="text" name="functional_requirements[${index}][requirement_id]" 
                                   placeholder="FR-${sectionNumber.replace(/\\./g, '')}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <input type="text" name="functional_requirements[${index}][title]" 
                                   placeholder="Requirement Title" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                                <select name="functional_requirements[${index}][priority]" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="critical">Critical</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="functional_requirements[${index}][status]" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm">
                                    <option value="draft" selected>Draft</option>
                                    <option value="review">Review</option>
                                    <option value="approved">Approved</option>
                                    <option value="implemented">Implemented</option>
                                    <option value="verified">Verified</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="functional_requirements[${index}][description]" required rows="2"
                                      placeholder="Describe the requirement in detail..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Acceptance Criteria</label>
                            <textarea name="functional_requirements[${index}][acceptance_criteria]" rows="2"
                                      placeholder="How will this requirement be verified?"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Source/Stakeholder</label>
                            <input type="text" name="functional_requirements[${index}][source]" 
                                   placeholder="Who requested this requirement?"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="block text-sm font-medium text-gray-700">🎨 UX Considerations</label>
                                <button type="button" onclick="addUxItem(this, ${index})" 
                                        class="text-xs px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">+ Add</button>
                            </div>
                            <div class="ux-items-container space-y-1" data-index="${index}"></div>
                        </div>
                    </div>

                    <div class="children-container space-y-4 mt-4"></div>

                    <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-200">
                        <button type="button" onclick="addSubRequirement(this, 'functional')" 
                                class="text-sm px-3 py-1 bg-indigo-100 text-indigo-700 rounded hover:bg-indigo-200 transition">
                            + Add Sub-Requirement
                        </button>
                        <button type="button" onclick="removeRequirement(this)" 
                                class="text-sm px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition">
                            🗑️ Remove
                        </button>
                    </div>
                </div>
            `;
        }

        function createNonFunctionalRequirementHtml(index, sectionNumber, parentSection = '') {
            let categoryOptions = '';
            for (const [value, label] of Object.entries(nfrCategories)) {
                categoryOptions += `<option value="${value}">${label}</option>`;
            }

            return `
                <div class="requirement-item p-4 bg-purple-50 rounded-lg border border-purple-200 ${parentSection ? 'ml-8 border-l-4 border-l-purple-300' : ''}" data-index="${index}">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Section #</label>
                            <input type="text" name="non_functional_requirements[${index}][section_number]" 
                                   value="${sectionNumber}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 font-mono text-purple-600 font-bold">
                            <input type="hidden" name="non_functional_requirements[${index}][parent_section]" value="${parentSection}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Requirement ID</label>
                            <input type="text" name="non_functional_requirements[${index}][requirement_id]" 
                                   placeholder="NFR-${sectionNumber.replace(/\\./g, '')}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <input type="text" name="non_functional_requirements[${index}][title]" 
                                   placeholder="Requirement Title" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="non_functional_requirements[${index}][category]" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm">
                                ${categoryOptions}
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                                <select name="non_functional_requirements[${index}][priority]" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="critical">Critical</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="non_functional_requirements[${index}][status]" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm">
                                    <option value="draft" selected>Draft</option>
                                    <option value="review">Review</option>
                                    <option value="approved">Approved</option>
                                    <option value="implemented">Implemented</option>
                                    <option value="verified">Verified</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="non_functional_requirements[${index}][description]" required rows="2"
                                      placeholder="Describe the non-functional requirement..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Acceptance Criteria</label>
                            <textarea name="non_functional_requirements[${index}][acceptance_criteria]" rows="2"
                                      placeholder="How will this be verified?"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Measurement Method</label>
                            <input type="text" name="non_functional_requirements[${index}][measurement]" 
                                   placeholder="How to measure (e.g., load test)"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Target Value</label>
                            <input type="text" name="non_functional_requirements[${index}][target_value]" 
                                   placeholder="e.g., < 2 seconds, 99.9% uptime"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Source/Stakeholder</label>
                            <input type="text" name="non_functional_requirements[${index}][source]" 
                                   placeholder="Who requested this?"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>
                    </div>

                    <div class="children-container space-y-4 mt-4"></div>

                    <div class="flex justify-between items-center mt-4 pt-4 border-t border-purple-200">
                        <button type="button" onclick="addSubRequirement(this, 'non_functional')" 
                                class="text-sm px-3 py-1 bg-purple-100 text-purple-700 rounded hover:bg-purple-200 transition">
                            + Add Sub-Requirement
                        </button>
                        <button type="button" onclick="removeRequirement(this)" 
                                class="text-sm px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition">
                            🗑️ Remove
                        </button>
                    </div>
                </div>
            `;
        }

        function addUxItem(button, reqIndex) {
            const container = button.closest('.requirement-item').querySelector('.ux-items-container');
            const html = `
                <div class="ux-item flex gap-2">
                    <input type="text" name="functional_requirements[${reqIndex}][ux_considerations][]" 
                           placeholder="UX consideration..."
                           class="flex-1 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-indigo-500">
                    <button type="button" onclick="this.parentElement.remove()" 
                            class="px-2 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600">✕</button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        }

        function removeRequirement(button) {
            const item = button.closest('.requirement-item');
            item.remove();
        }
    </script>
    @endpush
</x-app-layout>
