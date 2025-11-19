@props([
    'id' => 'modal-' . uniqid(),
    'title' => 'Modal',
    'size' => 'md',
])

@php
    $sizeClasses = match($size) {
        'sm' => 'max-w-sm',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        default => 'max-w-md',
    };
@endphp

<div id="{{ $id }}" class="modal hidden">
    <div class="modal-overlay" onclick="new Modal('#{{ $id }}').close()"></div>
    <div class="modal-content {{ $sizeClasses }}">
        <div class="modal-header">
            <h2 class="modal-title">{{ $title }}</h2>
            <button type="button" class="modal-close" onclick="new Modal('#{{ $id }}').close()">×</button>
        </div>
        <div class="modal-body">
            {{ $slot }}
        </div>
    </div>
</div>

<style>
    .modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 1000;
    }

    .modal.active {
        display: flex;
    }

    .modal-overlay {
        position: absolute;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.5);
        cursor: pointer;
    }

    .modal-content {
        position: relative;
        z-index: 1001;
        background-color: var(--color-bg-primary);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-xl);
        margin: auto;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--spacing-lg);
        border-bottom: 1px solid var(--color-border);
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--color-text-primary);
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 2rem;
        cursor: pointer;
        color: var(--color-text-secondary);
        padding: 0;
        width: 2rem;
        height: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color var(--transition-fast);
    }

    .modal-close:hover {
        color: var(--color-text-primary);
    }

    .modal-body {
        padding: var(--spacing-lg);
    }
</style>
