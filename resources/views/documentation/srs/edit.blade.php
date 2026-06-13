<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 style="font-family: var(--font-mono); font-weight: 600; font-size: 1.25rem; color: var(--color-text); line-height: 1.3;">
                📋 {{ __('Edit SRS Document') }}
            </h2>
            <div class="flex items-center gap-3">
                @if($srsDocument->project_id)
                    <a href="{{ route('projects.ai-tasks.preview', $srsDocument->project_id) }}" 
                       class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                       style="padding: 0.5rem 1rem; background: linear-gradient(135deg, var(--color-accent), var(--color-purple)); color: white; border-radius: 0.5rem; font-weight: 500; display: flex; align-items: center; gap: 0.5rem; transition: opacity 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"
                       onmouseover="this.style.opacity='0.9'"
                       onmouseout="this.style.opacity='1'">
                        🤖 Generate Tasks
                    </a>
                @endif
                <span style="padding: 0.25rem 0.75rem; font-size: 0.875rem; border-radius: 9999px; 
                    {{ $srsDocument->status === 'draft' ? 'background-color: color-mix(in srgb, var(--color-warning) 15%, transparent); color: var(--color-warning);' : '' }}
                    {{ $srsDocument->status === 'review' ? 'background-color: color-mix(in srgb, var(--color-accent) 15%, transparent); color: var(--color-accent);' : '' }}
                    {{ $srsDocument->status === 'approved' ? 'background-color: color-mix(in srgb, var(--color-success) 15%, transparent); color: var(--color-success);' : '' }}
                    {{ $srsDocument->status === 'final' ? 'background-color: color-mix(in srgb, var(--color-purple) 15%, transparent); color: var(--color-purple);' : '' }}">
                    v{{ $srsDocument->version ?? '1.0' }} - {{ ucfirst($srsDocument->status ?? 'draft') }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('documentation.srs.update', $srsDocument) }}" class="space-y-8" id="srs-form">
                @csrf
                @method('PUT')
                @php
                    $projectOptions = $projects ?? collect();
                    $selectedProjectId = old('project_id', $srsDocument->project_id);
                @endphp

                <!-- Section 1: Introduction -->
                <div style="background-color: var(--color-surface); border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem;">
                    <h2 style="font-family: var(--font-mono); font-size: 1.5rem; font-weight: 700; color: var(--color-text); margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--color-border);">
                        <span style="color: var(--color-accent);">1.</span> Introduction
                    </h2>

                    <div style="margin-bottom: 1.5rem;">
                        <label for="project_id" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.5rem;">Linked Project *</label>
                        <select id="project_id" name="project_id" required
                                class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                style="width: 100%; padding: 0.5rem 1rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">
                            <option value="">Select a project</option>
                            @foreach($projectOptions as $project)
                                <option value="{{ $project->id }}" @selected($selectedProjectId == $project->id)>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id') <span style="color: var(--color-danger); font-size: 0.875rem;">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="title" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.5rem;">
                                <span style="color: var(--color-accent); font-weight: 600;">1.1</span> Document Title *
                            </label>
                            <input type="text" id="title" name="title" required value="{{ $srsDocument->title }}"
                                   class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                   style="width: 100%; padding: 0.5rem 1rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">
                            @error('title') <span style="color: var(--color-danger); font-size: 0.875rem;">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="version" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.5rem;">Version</label>
                                <input type="text" id="version" name="version" value="{{ $srsDocument->version ?? '1.0' }}"
                                       class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                       style="width: 100%; padding: 0.5rem 1rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">
                            </div>
                            <div>
                                <label for="status" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.5rem;">Status</label>
                                <select id="status" name="status"
                                        class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                        style="width: 100%; padding: 0.5rem 1rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">
                                    <option value="draft" @selected(($srsDocument->status ?? 'draft') === 'draft')>Draft</option>
                                    <option value="review" @selected(($srsDocument->status ?? 'draft') === 'review')>Under Review</option>
                                    <option value="approved" @selected(($srsDocument->status ?? 'draft') === 'approved')>Approved</option>
                                    <option value="final" @selected(($srsDocument->status ?? 'draft') === 'final')>Final</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label for="purpose" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.5rem;">
                            <span style="color: var(--color-accent); font-weight: 600;">1.2</span> Purpose
                        </label>
                        <textarea id="purpose" name="purpose" rows="3"
                                  class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                  style="width: 100%; padding: 0.5rem 1rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);"
                                  placeholder="Describe the purpose of this SRS document...">{{ $srsDocument->purpose }}</textarea>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label for="document_conventions" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.5rem;">
                            <span style="color: var(--color-accent); font-weight: 600;">1.3</span> Document Conventions
                        </label>
                        <textarea id="document_conventions" name="document_conventions" rows="2"
                                  class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                  style="width: 100%; padding: 0.5rem 1rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);"
                                  placeholder="Standards or typographical conventions used...">{{ $srsDocument->document_conventions }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="intended_audience" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.5rem;">
                                <span style="color: var(--color-accent); font-weight: 600;">1.4</span> Intended Audience
                            </label>
                            <textarea id="intended_audience" name="intended_audience" rows="2"
                                      class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                      style="width: 100%; padding: 0.5rem 1rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);"
                                      placeholder="Target readers and reading suggestions...">{{ $srsDocument->intended_audience }}</textarea>
                        </div>
                        <div>
                            <label for="product_scope" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.5rem;">
                                <span style="color: var(--color-accent); font-weight: 600;">1.5</span> Product Scope
                            </label>
                            <textarea id="product_scope" name="product_scope" rows="2"
                                      class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                      style="width: 100%; padding: 0.5rem 1rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);"
                                      placeholder="Software objectives and business goals...">{{ $srsDocument->product_scope }}</textarea>
                        </div>
                    </div>

                    <div style="margin-top: 1.5rem;">
                        <label for="references" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.5rem;">
                            <span style="color: var(--color-accent); font-weight: 600;">1.6</span> References
                        </label>
                        <textarea id="references" name="references" rows="2"
                                  class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                  style="width: 100%; padding: 0.5rem 1rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);"
                                  placeholder="Related documents and references...">{{ $srsDocument->references }}</textarea>
                    </div>
                </div>

                <!-- Section 2: Overall Description -->
                <div style="background-color: var(--color-surface); border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem;">
                    <h2 style="font-family: var(--font-mono); font-size: 1.5rem; font-weight: 700; color: var(--color-text); margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--color-border);">
                        <span style="color: var(--color-accent);">2.</span> Overall Description
                    </h2>

                    <div style="margin-bottom: 1.5rem;">
                        <label for="description" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.5rem;">
                            <span style="color: var(--color-accent); font-weight: 600;">2.1</span> Product Description
                        </label>
                        <textarea id="description" name="description" rows="3"
                                  class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                  style="width: 100%; padding: 0.5rem 1rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">{{ $srsDocument->description }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="product_perspective" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.5rem;">
                                <span style="color: var(--color-accent); font-weight: 600;">2.2</span> Product Perspective
                            </label>
                            <textarea id="product_perspective" name="product_perspective" rows="3"
                                      class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                      style="width: 100%; padding: 0.5rem 1rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);"
                                      placeholder="How the product fits into larger context...">{{ $srsDocument->product_perspective }}</textarea>
                        </div>
                        <div>
                            <label for="product_features" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.5rem;">
                                <span style="color: var(--color-accent); font-weight: 600;">2.3</span> Product Features
                            </label>
                            <textarea id="product_features" name="product_features" rows="3"
                                      class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                      style="width: 100%; padding: 0.5rem 1rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);"
                                      placeholder="Major features and capabilities...">{{ $srsDocument->product_features }}</textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="user_classes" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.5rem;">
                                <span style="color: var(--color-accent); font-weight: 600;">2.4</span> User Classes and Characteristics
                            </label>
                            <textarea id="user_classes" name="user_classes" rows="3"
                                      class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                      style="width: 100%; padding: 0.5rem 1rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);"
                                      placeholder="User types and their characteristics...">{{ $srsDocument->user_classes }}</textarea>
                        </div>
                        <div>
                            <label for="operating_environment" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.5rem;">
                                <span style="color: var(--color-accent); font-weight: 600;">2.5</span> Operating Environment
                            </label>
                            <textarea id="operating_environment" name="operating_environment" rows="3"
                                      class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                      style="width: 100%; padding: 0.5rem 1rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);"
                                      placeholder="Hardware, OS, and platform requirements...">{{ $srsDocument->operating_environment }}</textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="design_constraints" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.5rem;">
                                <span style="color: var(--color-accent); font-weight: 600;">2.6</span> Design Constraints
                            </label>
                            <textarea id="design_constraints" name="design_constraints" rows="2"
                                      class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                      style="width: 100%; padding: 0.5rem 1rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">{{ $srsDocument->design_constraints }}</textarea>
                        </div>
                        <div>
                            <label for="constraints" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.5rem;">
                                <span style="color: var(--color-accent); font-weight: 600;">2.7</span> Constraints
                            </label>
                            <textarea id="constraints" name="constraints" rows="2"
                                      class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                      style="width: 100%; padding: 0.5rem 1rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">{{ $srsDocument->constraints }}</textarea>
                        </div>
                        <div>
                            <label for="assumptions" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.5rem;">
                                <span style="color: var(--color-accent); font-weight: 600;">2.8</span> Assumptions
                            </label>
                            <textarea id="assumptions" name="assumptions" rows="2"
                                      class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                      style="width: 100%; padding: 0.5rem 1rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">{{ $srsDocument->assumptions }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Section 3: External Interface Requirements -->
                <div style="background-color: var(--color-surface); border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem;">
                    <h2 style="font-family: var(--font-mono); font-size: 1.5rem; font-weight: 700; color: var(--color-text); margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--color-border);">
                        <span style="color: var(--color-accent);">3.</span> External Interface Requirements
                    </h2>
                    <div style="margin-bottom: 1.5rem;">
                        <label for="external_interfaces" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.5rem;">
                            External Interfaces Description
                        </label>
                        <textarea id="external_interfaces" name="external_interfaces" rows="4"
                                  class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                  style="width: 100%; padding: 0.5rem 1rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);"
                                  placeholder="Describe user interfaces, hardware interfaces, software interfaces, and communication interfaces...">{{ $srsDocument->external_interfaces }}</textarea>
                    </div>
                </div>

                <!-- Section 4: Functional Requirements -->
                <div style="background-color: var(--color-surface); border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--color-border);">
                        <h2 style="font-family: var(--font-mono); font-size: 1.5rem; font-weight: 700; color: var(--color-text);">
                            <span style="color: var(--color-accent);">4.</span> Functional Requirements
                        </h2>
                        <button type="button" onclick="addRequirement('functional')" 
                                class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                style="padding: 0.5rem 1rem; background-color: var(--color-success); color: white; border-radius: 0.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; transition: background-color 0.2s;"
                                onmouseover="this.style.backgroundColor='color-mix(in srgb, var(--color-success) 80%, black)'"
                                onmouseout="this.style.backgroundColor='var(--color-success)'">
                            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Requirement
                        </button>
                    </div>

                    <p style="font-size: 0.875rem; color: var(--color-text); margin-bottom: 1rem; padding: 0.75rem; background-color: color-mix(in srgb, var(--color-accent) 10%, transparent); border-radius: 0.5rem;">
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
                            <div class="empty-message" style="color: var(--color-text-muted); text-align: center; padding: 2rem; border: 2px dashed var(--color-border); border-radius: 0.5rem;">
                                <svg style="width: 3rem; height: 3rem; margin: 0 auto 0.75rem; color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p>No functional requirements added yet.</p>
                                <p style="font-size: 0.875rem; margin-top: 0.25rem;">Click "Add Requirement" to get started.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Section 5: Non-Functional Requirements -->
                <div style="background-color: var(--color-surface); border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--color-border);">
                        <h2 style="font-family: var(--font-mono); font-size: 1.5rem; font-weight: 700; color: var(--color-text);">
                            <span style="color: var(--color-accent);">5.</span> Non-Functional Requirements
                        </h2>
                        <button type="button" onclick="addRequirement('non_functional')" 
                                class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                                style="padding: 0.5rem 1rem; background-color: var(--color-purple); color: white; border-radius: 0.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; transition: background-color 0.2s;"
                                onmouseover="this.style.backgroundColor='color-mix(in srgb, var(--color-purple) 80%, black)'"
                                onmouseout="this.style.backgroundColor='var(--color-purple)'">
                            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Requirement
                        </button>
                    </div>

                    <p style="font-size: 0.875rem; color: var(--color-text); margin-bottom: 1rem; padding: 0.75rem; background-color: color-mix(in srgb, var(--color-purple) 10%, transparent); border-radius: 0.5rem;">
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
                            <div class="empty-message" style="color: var(--color-text-muted); text-align: center; padding: 2rem; border: 2px dashed var(--color-border); border-radius: 0.5rem;">
                                <svg style="width: 3rem; height: 3rem; margin: 0 auto 0.75rem; color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <p>No non-functional requirements added yet.</p>
                                <p style="font-size: 0.875rem; margin-top: 0.25rem;">Click "Add Requirement" to get started.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Section 6: Other Requirements -->
                <div style="background-color: var(--color-surface); border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem;">
                    <h2 style="font-family: var(--font-mono); font-size: 1.5rem; font-weight: 700; color: var(--color-text); margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--color-border);">
                        <span style="color: var(--color-accent);">6.</span> Other Requirements
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="data_requirements" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.5rem;">
                                <span style="color: var(--color-accent); font-weight: 600;">6.1</span> Data Requirements
                            </label>
                            <textarea id="data_requirements" name="data_requirements" rows="3"
                                      class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                      style="width: 100%; padding: 0.5rem 1rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);"
                                      placeholder="Database requirements, data formats, retention policies...">{{ $srsDocument->data_requirements }}</textarea>
                        </div>
                        <div>
                            <label for="dependencies" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.5rem;">
                                <span style="color: var(--color-accent); font-weight: 600;">6.2</span> Dependencies
                            </label>
                            <textarea id="dependencies" name="dependencies" rows="3"
                                      class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                      style="width: 100%; padding: 0.5rem 1rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);"
                                      placeholder="External dependencies and integrations...">{{ $srsDocument->dependencies }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Appendices -->
                <div style="background-color: var(--color-surface); border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem;">
                    <h2 style="font-family: var(--font-mono); font-size: 1.5rem; font-weight: 700; color: var(--color-text); margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--color-border);">
                        📎 Appendices
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="glossary" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.5rem;">
                                Glossary
                            </label>
                            <textarea id="glossary" name="glossary" rows="4"
                                      class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                      style="width: 100%; padding: 0.5rem 1rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);"
                                      placeholder="Define technical terms and acronyms...">{{ $srsDocument->glossary }}</textarea>
                        </div>
                        <div>
                            <label for="appendices" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.5rem;">
                                Additional Appendices
                            </label>
                            <textarea id="appendices" name="appendices" rows="4"
                                      class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                      style="width: 100%; padding: 0.5rem 1rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);"
                                      placeholder="Additional supporting information...">{{ $srsDocument->appendices }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; flex-wrap: wrap; gap: 1rem; padding-top: 1.5rem; position: sticky; bottom: 0; background-color: var(--color-surface-2); padding: 1rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <a href="{{ route('documentation.srs.index') }}" 
                       class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                       style="padding: 0.5rem 1.5rem; border: 1px solid var(--color-border); color: var(--color-text); border-radius: 0.5rem; transition: background-color 0.2s;"
                       onmouseover="this.style.backgroundColor='var(--color-surface-3)'"
                       onmouseout="this.style.backgroundColor='transparent'">
                        ← Back
                    </a>
                    <button type="submit" 
                            class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                            style="padding: 0.5rem 1.5rem; background-color: var(--color-accent); color: white; border-radius: 0.5rem; font-weight: 600; transition: background-color 0.2s;"
                            onmouseover="this.style.backgroundColor='color-mix(in srgb, var(--color-accent) 80%, black)'"
                            onmouseout="this.style.backgroundColor='var(--color-accent)'">
                        💾 Save SRS
                    </button>
                    <a href="{{ route('documentation.srs.pdf', $srsDocument) }}" 
                       class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                       style="padding: 0.5rem 1.5rem; background-color: var(--color-success); color: white; border-radius: 0.5rem; font-weight: 600; transition: background-color 0.2s;"
                       onmouseover="this.style.backgroundColor='color-mix(in srgb, var(--color-success) 80%, black)'"
                       onmouseout="this.style.backgroundColor='var(--color-success)'">
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
                <div class="requirement-item" style="padding: 1rem; background-color: var(--color-surface-2); border-radius: 0.5rem; border: 1px solid var(--color-border); ${parentSection ? 'margin-left: 2rem; border-left: 4px solid color-mix(in srgb, var(--color-accent) 50%, transparent);' : ''}" data-index="${index}">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Section #</label>
                            <input type="text" name="functional_requirements[${index}][section_number]" 
                                   value="${sectionNumber}" required
                                   class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-accent); font-family: var(--font-mono); font-weight: 700;">
                            <input type="hidden" name="functional_requirements[${index}][parent_section]" value="${parentSection}">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Requirement ID</label>
                            <input type="text" name="functional_requirements[${index}][requirement_id]" 
                                   placeholder="FR-${sectionNumber.replace(/\\./g, '')}" required
                                   class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Title</label>
                            <input type="text" name="functional_requirements[${index}][title]" 
                                   placeholder="Requirement Title" required
                                   class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Priority</label>
                                <select name="functional_requirements[${index}][priority]" required
                                        class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                        style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text); font-size: 0.875rem;">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="critical">Critical</option>
                                </select>
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Status</label>
                                <select name="functional_requirements[${index}][status]" 
                                        class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                        style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text); font-size: 0.875rem;">
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
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Description</label>
                            <textarea name="functional_requirements[${index}][description]" required rows="2"
                                      placeholder="Describe the requirement in detail..."
                                      class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                      style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);"></textarea>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Acceptance Criteria</label>
                            <textarea name="functional_requirements[${index}][acceptance_criteria]" rows="2"
                                      placeholder="How will this requirement be verified?"
                                      class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                      style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);"></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Source/Stakeholder</label>
                            <input type="text" name="functional_requirements[${index}][source]" 
                                   placeholder="Who requested this requirement?"
                                   class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">
                        </div>
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text);">🎨 UX Considerations</label>
                                <button type="button" onclick="addUxItem(this, ${index})" 
                                        class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                        style="font-size: 0.75rem; padding: 0.25rem 0.5rem; background-color: var(--color-accent); color: white; border-radius: 0.25rem;">+ Add</button>
                            </div>
                            <div class="ux-items-container space-y-1" data-index="${index}"></div>
                        </div>
                    </div>

                    <div class="children-container space-y-4 mt-4"></div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--color-border);">
                        <button type="button" onclick="addSubRequirement(this, 'functional')" 
                                class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                style="font-size: 0.875rem; padding: 0.25rem 0.75rem; background-color: color-mix(in srgb, var(--color-accent) 15%, transparent); color: var(--color-accent); border-radius: 0.25rem; transition: background-color 0.2s;">
                            + Add Sub-Requirement
                        </button>
                        <button type="button" onclick="removeRequirement(this)" 
                                class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                                style="font-size: 0.875rem; padding: 0.25rem 0.75rem; background-color: color-mix(in srgb, var(--color-danger) 15%, transparent); color: var(--color-danger); border-radius: 0.25rem; transition: background-color 0.2s;">
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
                <div class="requirement-item" style="padding: 1rem; background-color: color-mix(in srgb, var(--color-purple) 10%, var(--color-surface-2)); border-radius: 0.5rem; border: 1px solid color-mix(in srgb, var(--color-purple) 30%, transparent); ${parentSection ? 'margin-left: 2rem; border-left: 4px solid color-mix(in srgb, var(--color-purple) 50%, transparent);' : ''}" data-index="${index}">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Section #</label>
                            <input type="text" name="non_functional_requirements[${index}][section_number]" 
                                   value="${sectionNumber}" required
                                   class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-purple); font-family: var(--font-mono); font-weight: 700;">
                            <input type="hidden" name="non_functional_requirements[${index}][parent_section]" value="${parentSection}">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Requirement ID</label>
                            <input type="text" name="non_functional_requirements[${index}][requirement_id]" 
                                   placeholder="NFR-${sectionNumber.replace(/\\./g, '')}" required
                                   class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Title</label>
                            <input type="text" name="non_functional_requirements[${index}][title]" 
                                   placeholder="Requirement Title" required
                                   class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Category</label>
                            <select name="non_functional_requirements[${index}][category]" required
                                    class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text); font-size: 0.875rem;">
                                ${categoryOptions}
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Priority</label>
                                <select name="non_functional_requirements[${index}][priority]" required
                                        class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                                        style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text); font-size: 0.875rem;">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="critical">Critical</option>
                                </select>
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Status</label>
                                <select name="non_functional_requirements[${index}][status]" 
                                        class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                                        style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text); font-size: 0.875rem;">
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
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Description</label>
                            <textarea name="non_functional_requirements[${index}][description]" required rows="2"
                                      placeholder="Describe the non-functional requirement..."
                                      class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                                      style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);"></textarea>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Acceptance Criteria</label>
                            <textarea name="non_functional_requirements[${index}][acceptance_criteria]" rows="2"
                                      placeholder="How will this be verified?"
                                      class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                                      style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);"></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Measurement Method</label>
                            <input type="text" name="non_functional_requirements[${index}][measurement]" 
                                   placeholder="How to measure (e.g., load test)"
                                   class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Target Value</label>
                            <input type="text" name="non_functional_requirements[${index}][target_value]" 
                                   placeholder="e.g., &lt; 2 seconds, 99.9% uptime"
                                   class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--color-text); margin-bottom: 0.25rem;">Source/Stakeholder</label>
                            <input type="text" name="non_functional_requirements[${index}][source]" 
                                   placeholder="Who requested this?"
                                   class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 0.5rem; background-color: var(--color-surface); color: var(--color-text);">
                        </div>
                    </div>

                    <div class="children-container space-y-4 mt-4"></div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid color-mix(in srgb, var(--color-purple) 30%, transparent);">
                        <button type="button" onclick="addSubRequirement(this, 'non_functional')" 
                                class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                                style="font-size: 0.875rem; padding: 0.25rem 0.75rem; background-color: color-mix(in srgb, var(--color-purple) 15%, transparent); color: var(--color-purple); border-radius: 0.25rem; transition: background-color 0.2s;">
                            + Add Sub-Requirement
                        </button>
                        <button type="button" onclick="removeRequirement(this)" 
                                class="focus-visible:ring-2 focus-visible:ring-[var(--color-purple)] focus-visible:outline-none"
                                style="font-size: 0.875rem; padding: 0.25rem 0.75rem; background-color: color-mix(in srgb, var(--color-danger) 15%, transparent); color: var(--color-danger); border-radius: 0.25rem; transition: background-color 0.2s;">
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
                           class="flex-1 focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                           style="padding: 0.25rem 0.5rem; font-size: 0.875rem; border: 1px solid var(--color-border); border-radius: 0.25rem; background-color: var(--color-surface); color: var(--color-text);">
                    <button type="button" onclick="this.parentElement.remove()" 
                            class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                            style="padding: 0.25rem 0.5rem; background-color: var(--color-danger); color: white; border-radius: 0.25rem; font-size: 0.75rem;">✕</button>
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
