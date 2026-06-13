<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 style="font-family: var(--font-mono); font-weight: 600; font-size: 1.25rem; color: var(--color-text); line-height: 1.3;">
                📋 {{ __('SRS Documents') }}
            </h2>
            <a href="{{ route('documentation.srs.create') }}" 
               style="background-color: var(--color-accent); color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 600; transition: background-color 0.2s;"
               onmouseover="this.style.backgroundColor='color-mix(in srgb, var(--color-accent) 80%, black)'"
               onmouseout="this.style.backgroundColor='var(--color-accent)'">
                + {{ __('Create New SRS') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Messages -->
            @if (session('success'))
                <div style="background-color: color-mix(in srgb, var(--color-success) 15%, transparent); border: 1px solid var(--color-success); color: var(--color-success); padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Controls -->
            <x-list-controls :route="route('documentation.srs.index')" :query="request()->q" :sort="request()->sort" :view="request()->view ?? 'grid'">
                <a href="{{ route('documentation.srs.create') }}" 
                   style="background-color: var(--color-accent); color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 600; transition: background-color 0.2s;"
                   onmouseover="this.style.backgroundColor='color-mix(in srgb, var(--color-accent) 80%, black)'"
                   onmouseout="this.style.backgroundColor='var(--color-accent)'">
                    + {{ __('Create New SRS') }}
                </a>
            </x-list-controls>

            <!-- SRS Documents List -->
            @if ($srsDocuments->count())
                @if(request('view') == 'list')
                    <div style="background-color: var(--color-surface); border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
                        <table class="min-w-full" style="border-collapse: collapse;">
                            <thead style="background-color: var(--color-surface-2);">
                                <tr>
                                    <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Title</th>
                                    <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Requirements</th>
                                    <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Created</th>
                                    <th style="padding: 0.75rem 1.5rem;"></th>
                                </tr>
                            </thead>
                            <tbody style="background-color: var(--color-surface); border-top: 1px solid var(--color-border);">
                                @foreach($srsDocuments as $srs)
                                    <tr style="border-bottom: 1px solid var(--color-border);">
                                        <td style="padding: 1rem 1.5rem; white-space: nowrap;">
                                            <div style="font-size: 0.875rem; font-weight: 500; color: var(--color-text);">{{ $srs->title }}</div>
                                            <div style="font-size: 0.875rem; color: var(--color-text-muted);">{{ \Illuminate\Support\Str::limit($srs->description, 100) }}</div>
                                        </td>
                                        <td style="padding: 1rem 1.5rem; white-space: nowrap; font-size: 0.875rem; color: var(--color-text-muted);">{{ $srs->functionalRequirements->count() }}</td>
                                        <td style="padding: 1rem 1.5rem; white-space: nowrap; font-size: 0.875rem; color: var(--color-text-muted);">{{ $srs->created_at->format('M d, Y') }}</td>
                                        <td style="padding: 1rem 1.5rem; white-space: nowrap; text-align: right; font-size: 0.875rem; font-weight: 500;">
                                            <a href="{{ route('documentation.srs.edit', $srs) }}" style="color: var(--color-accent); text-decoration: underline;">Edit</a>
                                            <a href="{{ route('documentation.srs.pdf', $srs) }}" style="color: var(--color-success); text-decoration: underline; margin-left: 1rem;">PDF</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($srsDocuments as $srs)
                            <x-srs-card :srs="$srs" />
                        @endforeach
                    </div>
                @endif
        @else
            <div style="text-align: center; padding: 3rem 0; background-color: var(--color-surface); border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="margin: 0 auto 1rem; width: 6rem; height: 6rem; display: flex; align-items: center; justify-content: center; background-color: color-mix(in srgb, var(--color-accent) 15%, transparent); border-radius: 50%;">
                    <svg style="width: 3rem; height: 3rem; color: var(--color-accent);" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p style="color: var(--color-text); font-size: 1.125rem; margin-bottom: 1rem;">No SRS documents yet.</p>
                <a href="{{ route('documentation.srs.create') }}" 
                   style="display: inline-block; padding: 0.5rem 1.5rem; background-color: var(--color-accent); color: white; border-radius: 0.5rem; transition: background-color 0.2s;"
                   onmouseover="this.style.backgroundColor='color-mix(in srgb, var(--color-accent) 80%, black)'"
                   onmouseout="this.style.backgroundColor='var(--color-accent)'">
                    Create Your First SRS
                </a>
            </div>
            @endif
            <div class="mt-6">
                {{ $srsDocuments->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
