<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight" data-aos="fade-right">
            {{ __('Animation Demo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- Hero Section with Particles --}}
            <div class="relative bg-gradient-primary rounded-lg overflow-hidden" style="min-height: 300px;">
                <div id="particles-hero" class="absolute inset-0"></div>
                <div class="relative z-10 p-8 text-white text-center">
                    <h1 class="text-4xl font-bold mb-4" data-aos="fade-down">
                        Welcome to GrowDev
                    </h1>
                    <p class="text-xl mb-6" data-aos="fade-up" data-aos-delay="200">
                        Experience beautiful animations and interactions
                    </p>
                    <div data-aos="zoom-in" data-aos-delay="400">
                        <button class="btn-primary px-8 py-3 text-lg" data-magnetic data-ripple>
                            Get Started
                        </button>
                    </div>
                </div>
            </div>

            {{-- Animated Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-dashboard-stat 
                    title="Total Projects" 
                    value="142"
                    icon="📊"
                    color="indigo" />
                <x-dashboard-stat 
                    title="Active Tasks" 
                    value="89"
                    icon="✅"
                    color="green" />
                <x-dashboard-stat 
                    title="Team Members" 
                    value="24"
                    icon="👥"
                    color="purple" />
                <x-dashboard-stat 
                    title="Completed" 
                    value="356"
                    icon="🎉"
                    color="yellow" />
            </div>

            {{-- Interactive Cards --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-2xl font-bold mb-6" data-aos="fade-right">Interactive Cards</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="card-interactive p-6" data-aos="flip-left" data-aos-delay="0">
                        <div class="text-4xl mb-4">🚀</div>
                        <h3 class="text-xl font-bold mb-2">Fast Performance</h3>
                        <p class="text-gray-600">Lightning-fast animations optimized for all devices</p>
                    </div>
                    <div class="card-interactive p-6" data-aos="flip-left" data-aos-delay="100">
                        <div class="text-4xl mb-4">🎨</div>
                        <h3 class="text-xl font-bold mb-2">Beautiful Design</h3>
                        <p class="text-gray-600">Modern UI with smooth transitions and effects</p>
                    </div>
                    <div class="card-interactive p-6" data-aos="flip-left" data-aos-delay="200">
                        <div class="text-4xl mb-4">📱</div>
                        <h3 class="text-xl font-bold mb-2">Responsive</h3>
                        <p class="text-gray-600">Works perfectly on mobile, tablet, and desktop</p>
                    </div>
                </div>
            </div>

            {{-- Buttons Demo --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-2xl font-bold mb-6" data-aos="fade-right">Button Styles</h2>
                <div class="flex flex-wrap gap-4" data-aos="fade-up">
                    <button class="btn-primary" data-ripple>Primary Button</button>
                    <button class="btn-secondary" data-ripple>Secondary Button</button>
                    <button class="btn-success" data-ripple>Success Button</button>
                    <button class="btn-danger" data-ripple>Danger Button</button>
                    <button class="btn-primary" data-magnetic data-ripple>Magnetic Button</button>
                </div>
            </div>

            {{-- Progress Bars --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-2xl font-bold mb-6" data-aos="fade-right">Progress Indicators</h2>
                <div class="space-y-4" data-aos="fade-up">
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="text-sm font-medium">HTML/CSS</span>
                            <span class="text-sm font-medium" data-counter="95">0</span>%
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full" 
                                 data-progress="95" 
                                 style="width: 0%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="text-sm font-medium">JavaScript</span>
                            <span class="text-sm font-medium" data-counter="88">0</span>%
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 h-2 rounded-full" 
                                 data-progress="88" 
                                 style="width: 0%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="text-sm font-medium">PHP/Laravel</span>
                            <span class="text-sm font-medium" data-counter="92">0</span>%
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-red-500 to-red-600 h-2 rounded-full" 
                                 data-progress="92" 
                                 style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Badges --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-2xl font-bold mb-6" data-aos="fade-right">Badges</h2>
                <div class="flex flex-wrap gap-3" data-aos="fade-up">
                    <span class="badge badge-primary">Primary</span>
                    <span class="badge badge-success">Success</span>
                    <span class="badge badge-warning">Warning</span>
                    <span class="badge badge-danger">Danger</span>
                </div>
            </div>

            {{-- Toast Notifications Demo --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-2xl font-bold mb-6" data-aos="fade-right">Toast Notifications</h2>
                <div class="flex flex-wrap gap-4" data-aos="fade-up">
                    <button class="btn-success" onclick="Toast.success('Success! Operation completed.')">
                        Show Success
                    </button>
                    <button class="btn-danger" onclick="Toast.error('Error! Something went wrong.')">
                        Show Error
                    </button>
                    <button class="btn-primary" onclick="Toast.info('Info: Here is some information.')">
                        Show Info
                    </button>
                    <button class="btn-secondary" onclick="Toast.warning('Warning: Please be careful.')">
                        Show Warning
                    </button>
                </div>
            </div>

            {{-- Stagger List Animation --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-2xl font-bold mb-6" data-aos="fade-right">Stagger Animations</h2>
                <ul class="space-y-3" data-stagger-list>
                    <li class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold">1</div>
                        <span>First item in the list</span>
                    </li>
                    <li class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold">2</div>
                        <span>Second item in the list</span>
                    </li>
                    <li class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold">3</div>
                        <span>Third item in the list</span>
                    </li>
                    <li class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold">4</div>
                        <span>Fourth item in the list</span>
                    </li>
                </ul>
            </div>

            {{-- Loading Skeleton Demo --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-2xl font-bold mb-6" data-aos="fade-right">Loading Skeletons</h2>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-3">Card Skeleton</h3>
                        <x-skeleton-loader type="card" />
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        // Initialize particles on the hero section
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof ParticlesEffect !== 'undefined') {
                ParticlesEffect.init('particles-hero', {
                    particleCount: 50,
                    color: '#ffffff',
                    lineColor: '#ffffff',
                    opacity: 0.3,
                    speed: 1,
                    size: 2
                });
            }

            // Animate progress bars
            setTimeout(() => {
                const progressBars = document.querySelectorAll('[data-progress]');
                progressBars.forEach(bar => {
                    const progress = bar.getAttribute('data-progress');
                    bar.style.width = progress + '%';
                });
            }, 500);
        });
    </script>
    @endpush
</x-app-layout>
