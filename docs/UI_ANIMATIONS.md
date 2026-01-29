# UI/UX Animation System

This document describes the enhanced animation and interaction system implemented in GrowDev using anime.js, AOS, GSAP, and other modern libraries.

## 📦 Installed Libraries

- **anime.js** (v3.2.2) - Lightweight JavaScript animation library
- **AOS** (v2.3.4) - Animate On Scroll library
- **GSAP** (v3.12.5) - Professional-grade animation platform
- **Typed.js** (v2.1.0) - Typing animation library
- **Particles.js** (v2.0.0) - Particle background effects

## 🎨 Available Animation Modules

### 1. Animations Module (`animations.js`)

Core animation utilities using anime.js:

```javascript
// Fade in with scale
Animations.fadeInScale(element, { duration: 600, delay: 0 });

// Slide in from direction
Animations.slideIn(element, 'left', { duration: 800 });

// Stagger animation for lists
Animations.staggerFadeIn(elements, { stagger: 100 });

// Card hover effect
Animations.cardHoverEffect(card);

// Button ripple effect
Animations.rippleEffect(button);

// Counter animation
Animations.counterAnimation(element, targetValue, { duration: 2000 });

// Pulse animation
Animations.pulse(element, { loop: 1 });

// Shake animation (for errors)
Animations.shake(element);

// Progress bar animation
Animations.progressBar(element, percent, { duration: 1000 });

// Text reveal animation
Animations.textReveal(element, { stagger: 30 });
```

### 2. Scroll Animations Module (`scroll-animations.js`)

Animate elements on scroll using AOS:

```javascript
// Initialize AOS
ScrollAnimations.init({
    duration: 800,
    once: true,
    offset: 100
});

// Refresh after dynamic content
ScrollAnimations.refresh();

// Add animations to elements
ScrollAnimations.addAnimations(elements, 'fade-up');
```

### 3. GSAP Animations Module (`gsap-animations.js`)

Advanced animations using GSAP:

```javascript
// Parallax effect
GSAPAnimations.parallax(element, speed);

// Reveal on scroll with stagger
GSAPAnimations.revealOnScroll(elements, { stagger: 0.1 });

// Pin element while scrolling
GSAPAnimations.pinElement(element);

// Smooth scroll
GSAPAnimations.smoothScroll(target);

// Magnetic button effect
GSAPAnimations.magneticButton(button);

// Text split and animate
GSAPAnimations.splitText(element, { stagger: 0.1 });
```

### 4. Form Animations Module (`form-animations.js`)

Interactive form enhancements:

```javascript
// Floating labels
FormAnimations.floatingLabels();

// Validation animation
FormAnimations.validationAnimation(input, isValid);

// Submit button loading
const resetButton = FormAnimations.submitButtonLoading(button);
// Later: resetButton();

// Checkbox animation
FormAnimations.checkboxAnimation();

// Progress stepper
FormAnimations.progressStepper(currentStep, totalSteps);
```

### 5. Toast Notifications Module (`toast.js`)

Beautiful animated notifications:

```javascript
// Show toast notification
Toast.success('Operation successful!');
Toast.error('Something went wrong!');
Toast.warning('Please be careful!');
Toast.info('Here is some information');

// Custom duration
Toast.success('Saved!', 5000); // 5 seconds
```

### 6. Typed Animations Module (`typed-animations.js`)

Typing animation effects:

```javascript
TypedAnimations.init(element, {
    strings: ['Welcome to GrowDev', 'Manage your projects'],
    typeSpeed: 50,
    backSpeed: 30,
    loop: true
});
```

### 7. Particles Effect Module (`particles-effect.js`)

Animated background particles:

```javascript
ParticlesEffect.init('particles-js', {
    particleCount: 80,
    color: '#6366f1',
    lineLinked: true
});
```

## 🎯 Data Attributes

### Automatic Animation Classes

Add these data attributes to HTML elements for automatic animations:

```html
<!-- Card hover effect -->
<div data-animate-card>...</div>

<!-- Ripple effect on click -->
<button data-ripple>Click me</button>

<!-- Counter animation -->
<span data-counter="100">0</span>

<!-- Progress bar -->
<div data-progress="75" style="width: 0%"></div>

<!-- Magnetic button effect -->
<button data-magnetic>Hover me</button>

<!-- Stagger list animation -->
<ul data-stagger-list>
    <li>Item 1</li>
    <li>Item 2</li>
    <li>Item 3</li>
</ul>
```

### AOS (Animate On Scroll) Attributes

```html
<!-- Fade up animation -->
<div data-aos="fade-up">Content</div>

<!-- Fade up with delay -->
<div data-aos="fade-up" data-aos-delay="100">Content</div>

<!-- Zoom in animation -->
<div data-aos="zoom-in" data-aos-delay="200">Content</div>

<!-- Slide animations -->
<div data-aos="fade-left">Slide from left</div>
<div data-aos="fade-right">Slide from right</div>

<!-- Flip animations -->
<div data-aos="flip-left">Flip left</div>
<div data-aos="flip-right">Flip right</div>
```

## 🎨 CSS Classes

### Custom Animation Classes

```html
<!-- Fade in animation -->
<div class="animate-fade-in">Content</div>

<!-- Slide animations -->
<div class="animate-slide-in-left">From left</div>
<div class="animate-slide-in-right">From right</div>
<div class="animate-slide-in-up">From bottom</div>
<div class="animate-slide-in-down">From top</div>

<!-- Other animations -->
<div class="animate-scale-in">Scale in</div>
<div class="animate-bounce-in">Bounce in</div>
<div class="animate-rotate-in">Rotate in</div>
```

### Button Classes

```html
<!-- Primary button with gradient -->
<button class="btn-primary">Primary</button>

<!-- Secondary button -->
<button class="btn-secondary">Secondary</button>

<!-- Success button -->
<button class="btn-success">Success</button>

<!-- Danger button -->
<button class="btn-danger">Danger</button>
```

### Card Classes

```html
<!-- Interactive card -->
<div class="card-interactive">Hover me</div>

<!-- Glass morphism card -->
<div class="card-glass">Glass effect</div>
```

### Badge Classes

```html
<span class="badge badge-primary">Primary</span>
<span class="badge badge-success">Success</span>
<span class="badge badge-warning">Warning</span>
<span class="badge badge-danger">Danger</span>
```

### Utility Classes

```html
<!-- Gradient backgrounds -->
<div class="bg-gradient-primary">Gradient</div>
<div class="bg-gradient-success">Success gradient</div>
<div class="bg-gradient-warning">Warning gradient</div>

<!-- Gradient text -->
<h1 class="gradient-text">Gradient Text</h1>

<!-- Floating animation -->
<div class="animate-float">Floating element</div>

<!-- Loading spinner -->
<div class="loader"></div>

<!-- Skeleton loaders -->
<div class="skeleton h-4 w-32"></div>
<div class="skeleton-circle"></div>
```

## 📝 Component Updates

### Enhanced Components

All the following components have been updated with animations:

1. **project-card.blade.php**
   - Hover effects
   - AOS scroll animations
   - Animated progress bars
   - Counter animations
   - Ripple effect on buttons

2. **dashboard-stat.blade.php**
   - Counter animations
   - Hover lift effect
   - Icon rotation on hover
   - AOS flip animations

3. **text-input.blade.php**
   - Focus scale effect
   - Smooth transitions
   - Border color animations

4. **primary-button.blade.php**
   - Gradient background
   - Hover lift effect
   - Ripple effect
   - Shadow on hover

5. **secondary-button.blade.php**
   - Hover lift effect
   - Shadow transitions
   - Ripple effect

## 🚀 Usage Examples

### Example 1: Animated Card Grid

```blade
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach($items as $index => $item)
        <div class="card-interactive" 
             data-aos="fade-up" 
             data-aos-delay="{{ $index * 100 }}"
             data-animate-card>
            <h3>{{ $item->title }}</h3>
            <p>{{ $item->description }}</p>
        </div>
    @endforeach
</div>
```

### Example 2: Animated Statistics

```blade
<div class="grid grid-cols-4 gap-4">
    <x-dashboard-stat 
        title="Total Projects" 
        value="{{ $projectCount }}"
        icon="📊"
        color="indigo" />
    <x-dashboard-stat 
        title="Active Tasks" 
        value="{{ $taskCount }}"
        icon="✅"
        color="green" />
</div>
```

### Example 3: Form with Validation

```javascript
const form = document.querySelector('form');
form.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const button = form.querySelector('button[type="submit"]');
    const resetButton = FormAnimations.submitButtonLoading(button);
    
    try {
        // Your form submission logic
        await submitForm(form);
        Toast.success('Form submitted successfully!');
        resetButton();
    } catch (error) {
        Toast.error('Submission failed: ' + error.message);
        resetButton();
        Animations.shake(form);
    }
});
```

### Example 4: Dynamic Content Loading

```javascript
// After loading dynamic content
htmx.on('htmx:afterSwap', () => {
    ScrollAnimations.refresh();
    
    // Animate new elements
    const newCards = document.querySelectorAll('.new-card');
    Animations.staggerFadeIn(newCards);
});
```

## 🎬 Page Load Animations

The system automatically initializes on page load:

1. **Scroll animations** - AOS initialized
2. **Card hover effects** - Applied to all cards
3. **Button ripples** - Applied to all buttons
4. **Counter animations** - Applied to elements with `data-counter`
5. **Progress bars** - Animated to their target values
6. **Form enhancements** - Floating labels and validation
7. **Alert animations** - Slide in from top with auto-dismiss

## 🔄 Dynamic Content

For AJAX/HTMX loaded content:

```javascript
// Refresh scroll animations
document.addEventListener('htmx:afterSwap', () => {
    ScrollAnimations.refresh();
});
```

## 🎨 Customization

### Modify Animation Durations

```javascript
// In your JavaScript
Animations.fadeInScale(element, {
    duration: 1000,  // 1 second
    delay: 500,      // 0.5 second delay
    easing: 'easeOutElastic(1, .8)'
});
```

### Custom AOS Settings

```javascript
ScrollAnimations.init({
    duration: 1000,
    once: false,  // Animate every time
    offset: 200
});
```

## 🐛 Troubleshooting

### Animations not working?

1. Make sure you've run `npm install`
2. Build assets: `npm run build` or `npm run dev`
3. Clear browser cache
4. Check browser console for errors

### Performance issues?

1. Reduce particle count in `ParticlesEffect`
2. Set `once: true` in AOS options
3. Use `will-change` CSS property sparingly
4. Debounce scroll events if needed

## 📚 Resources

- [Anime.js Documentation](https://animejs.com/documentation/)
- [AOS Documentation](https://michalsnik.github.io/aos/)
- [GSAP Documentation](https://greensock.com/docs/)
- [Typed.js Documentation](https://github.com/mattboldt/typed.js/)

## 🎉 Best Practices

1. **Don't overuse animations** - Use them purposefully
2. **Keep animations short** - 300-600ms for most UI interactions
3. **Use easing functions** - They make animations feel natural
4. **Test on mobile** - Ensure good performance on all devices
5. **Accessibility** - Respect `prefers-reduced-motion`
6. **Progressive enhancement** - Content should work without animations

## 🔮 Future Enhancements

Potential additions:

- [ ] Lottie animations for complex illustrations
- [ ] Three.js for 3D effects
- [ ] More GSAP ScrollTrigger effects
- [ ] Custom cursor animations
- [ ] Page transition effects between routes
- [ ] Loading skeleton screens
- [ ] Micro-interactions for better UX
