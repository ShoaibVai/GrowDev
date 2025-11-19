<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="shrink-0 flex items-center">
                <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-primary">📘 GrowDev</a>
            </div>

            <div class="hidden md:flex md:items-center md:space-x-1">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                <div class="relative">
                    <button type="button" class="nav-link" onclick="toggleDropdown(event)">
                        📄 Documentation
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div class="dropdown-menu">
                        <a href="{{ route('documentation.srs.create') }}" class="dropdown-item">📋 Create SRS</a>
                        <a href="{{ route('documentation.sdd.create') }}" class="dropdown-item">🏗️ Create SDD</a>
                        <div style="border-top: 1px solid var(--color-border);"></div>
                        <a href="{{ route('documentation.srs.index') }}" class="dropdown-item">📑 View SRS</a>
                        <a href="{{ route('documentation.sdd.index') }}" class="dropdown-item">🔧 View SDD</a>
                    </div>
                </div>
            </div>

            <div class="hidden md:flex md:items-center md:space-x-2">
                <div class="relative">
                    <button type="button" class="nav-link" onclick="toggleDropdown(event)">
                        {{ Auth::user()->name }}
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div class="dropdown-menu">
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">👤 Profile</a>
                        <div style="border-top: 1px solid var(--color-border);"></div>
                        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                            @csrf
                            <button type="submit" class="dropdown-item" style="width: 100%; text-align: left;">🚪 Logout</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="flex md:hidden items-center">
                <button type="button" class="mobile-menu-toggle" onclick="toggleMobileMenu()" aria-label="Toggle menu">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 6H21M3 12H21M3 18H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu" class="mobile-menu hidden">
        <ul style="list-style: none; padding: 0; margin: 0;">
            <li><a href="{{ route('dashboard') }}" class="mobile-menu-item">Dashboard</a></li>
            <li><a href="{{ route('documentation.srs.create') }}" class="mobile-menu-item">Create SRS</a></li>
            <li><a href="{{ route('documentation.sdd.create') }}" class="mobile-menu-item">Create SDD</a></li>
            <li><a href="{{ route('documentation.srs.index') }}" class="mobile-menu-item">View SRS</a></li>
            <li><a href="{{ route('documentation.sdd.index') }}" class="mobile-menu-item">View SDD</a></li>
            <li><a href="{{ route('profile.edit') }}" class="mobile-menu-item">Profile</a></li>
            <li style="border-top: 1px solid var(--color-border); padding-top: var(--spacing-md); margin-top: var(--spacing-md);">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="mobile-menu-item" style="width: 100%; text-align: left;">Logout</button>
                </form>
            </li>
        </ul>
    </div>
</nav>

<style>
    .nav-link {
        display: inline-flex;
        align-items: center;
        gap: var(--spacing-sm);
        padding: var(--spacing-md) var(--spacing-lg);
        color: var(--color-text-primary);
        text-decoration: none;
        font-weight: 500;
        transition: all var(--transition-fast);
        position: relative;
    }

    .nav-link:hover {
        color: var(--color-primary);
        background-color: var(--color-bg-secondary);
        border-radius: var(--radius-md);
    }

    .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: var(--spacing-lg);
        right: var(--spacing-lg);
        height: 3px;
        background-color: var(--color-primary);
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background-color: var(--color-bg-primary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-lg);
        z-index: 1000;
        min-width: 200px;
        margin-top: var(--spacing-xs);
    }

    .dropdown-menu.active {
        display: block;
    }

    .dropdown-item {
        display: block;
        width: 100%;
        padding: var(--spacing-md) var(--spacing-lg);
        color: var(--color-text-primary);
        text-decoration: none;
        text-align: left;
        background: none;
        border: none;
        cursor: pointer;
        font-family: inherit;
        font-size: inherit;
        transition: all var(--transition-fast);
    }

    .dropdown-item:hover {
        background-color: var(--color-bg-secondary);
        color: var(--color-primary);
    }

    .mobile-menu-toggle {
        background: none;
        border: none;
        cursor: pointer;
        padding: var(--spacing-md);
        color: var(--color-text-primary);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .mobile-menu-toggle:hover {
        color: var(--color-primary);
    }

    .mobile-menu {
        background-color: var(--color-bg-primary);
        border-top: 1px solid var(--color-border);
        padding: 0;
    }

    .mobile-menu.hidden {
        display: none;
    }

    .mobile-menu-item {
        display: block;
        padding: var(--spacing-md) var(--spacing-lg);
        color: var(--color-text-primary);
        text-decoration: none;
        transition: all var(--transition-fast);
        border: none;
        background: none;
        cursor: pointer;
        font-family: inherit;
        font-size: inherit;
    }

    .mobile-menu-item:hover {
        background-color: var(--color-bg-secondary);
        color: var(--color-primary);
    }
</style>
