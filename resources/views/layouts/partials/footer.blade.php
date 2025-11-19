<footer class="bg-gray-50 border-t border-gray-200 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <!-- About -->
            <div>
                <h3 class="text-lg font-bold text-primary mb-4">📘 GrowDev</h3>
                <p class="text-sm text-gray-600">Professional development management platform for teams and individuals.</p>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-primary transition">Home</a></li>
                    <li><a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-primary transition">Dashboard</a></li>
                    <li><a href="{{ route('documentation.srs.index') }}" class="text-gray-600 hover:text-primary transition">Documentation</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Contact</h4>
                <p class="text-sm text-gray-600">Email: support@growdev.com</p>
                <p class="text-sm text-gray-600">Phone: +1 (555) 000-0000</p>
            </div>
        </div>

        <div class="border-t border-gray-200 pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-sm text-gray-600">&copy; {{ date('Y') }} GrowDev. All rights reserved.</p>
            <div class="flex space-x-4 text-sm mt-4 md:mt-0">
                <a href="#" class="text-gray-600 hover:text-primary transition">Privacy Policy</a>
                <a href="#" class="text-gray-600 hover:text-primary transition">Terms of Service</a>
                <a href="#" class="text-gray-600 hover:text-primary transition">Contact Us</a>
            </div>
        </div>
    </div>
</footer>

<style>
    .grid {
        display: grid;
    }

    .grid-cols-1 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }

    .gap-8 {
        gap: var(--spacing-2xl);
    }

    .mb-8 {
        margin-bottom: var(--spacing-2xl);
    }

    .mb-4 {
        margin-bottom: var(--spacing-lg);
    }

    .space-y-2 > * + * {
        margin-top: var(--spacing-sm);
    }

    .mt-4 {
        margin-top: var(--spacing-lg);
    }

    .pt-8 {
        padding-top: var(--spacing-2xl);
    }

    .flex {
        display: flex;
    }

    .flex-col {
        flex-direction: column;
    }

    .justify-between {
        justify-content: space-between;
    }

    .items-center {
        align-items: center;
    }

    @media (min-width: 768px) {
        .grid-cols-1 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .md:flex-row {
            flex-direction: row;
        }

        .md:mt-0 {
            margin-top: 0;
        }
    }
</style>
