# Quick Start Guide - UI Animations

## 🚀 Getting Started

The animation system is now fully integrated into GrowDev! Here's how to use it:

## ✨ Automatic Features

These features work automatically without any additional code:

### 1. **Card Hover Effects**
All elements with class `card`, `project-card`, or `data-animate-card` automatically get smooth hover animations.

### 2. **Button Ripples**
All buttons and elements with `data-ripple` get click ripple effects.

### 3. **Scroll Animations**
Elements with AOS data attributes animate when scrolling:
```html
<div data-aos="fade-up">Animates on scroll</div>
```

### 4. **Counter Animations**
Numbers automatically count up:
```html
<span data-counter="250">0</span>
```

### 5. **Progress Bars**
Progress bars animate to their target value:
```html
<div data-progress="75" style="width: 0%"></div>
```

## 🎨 Quick Examples

### Example 1: Animated Card
```blade
<div class="bg-white rounded-lg shadow-lg p-6" data-aos="fade-up" data-animate-card>
    <h3 class="text-xl font-bold">My Card</h3>
    <p>This card will fade up on scroll and have hover effects</p>
</div>
```

### Example 2: Statistics with Counter
```blade
<div class="card" data-aos="flip-left">
    <h4>Total Users</h4>
    <p class="text-3xl font-bold" data-counter="1250">0</p>
</div>
```

### Example 3: Progress Indicator
```blade
<div class="w-full bg-gray-200 rounded-full h-2">
    <div class="bg-indigo-600 h-2 rounded-full" 
         data-progress="85" 
         style="width: 0%"></div>
</div>
```

### Example 4: Animated Button
```blade
<button class="btn-primary" data-ripple data-magnetic>
    Click Me
</button>
```

### Example 5: Show Toast Notification
```javascript
// In your JavaScript
Toast.success('Task completed successfully!');
Toast.error('Failed to save changes');
Toast.warning('Please review your input');
Toast.info('New notification received');
```

## 🎭 AOS Animation Types

Choose from many animation types:

**Fade animations:**
- `fade-up`, `fade-down`, `fade-left`, `fade-right`

**Zoom animations:**
- `zoom-in`, `zoom-out`, `zoom-in-up`, `zoom-in-down`

**Flip animations:**
- `flip-left`, `flip-right`, `flip-up`, `flip-down`

**Slide animations:**
- `slide-up`, `slide-down`, `slide-left`, `slide-right`

### Adding Delays
```html
<div data-aos="fade-up" data-aos-delay="100">Delayed 100ms</div>
<div data-aos="fade-up" data-aos-delay="200">Delayed 200ms</div>
<div data-aos="fade-up" data-aos-delay="300">Delayed 300ms</div>
```

## 🎨 CSS Utility Classes

### Button Styles
```html
<button class="btn-primary">Primary Button</button>
<button class="btn-secondary">Secondary Button</button>
<button class="btn-success">Success Button</button>
<button class="btn-danger">Danger Button</button>
```

### Card Styles
```html
<div class="card-interactive">Hover for effect</div>
<div class="card-glass">Glass morphism</div>
```

### Badges
```html
<span class="badge badge-primary">New</span>
<span class="badge badge-success">Active</span>
<span class="badge badge-warning">Pending</span>
<span class="badge badge-danger">Urgent</span>
```

### Gradients
```html
<div class="bg-gradient-primary">Primary Gradient</div>
<h1 class="gradient-text">Gradient Text</h1>
```

## 🔧 JavaScript API

### Basic Animations
```javascript
// Fade in with scale
Animations.fadeInScale(element);

// Slide in
Animations.slideIn(element, 'left');

// Shake (for errors)
Animations.shake(inputElement);

// Pulse (for notifications)
Animations.pulse(element);
```

### Form Validations
```javascript
// Show success
FormAnimations.validationAnimation(input, true);

// Show error
FormAnimations.validationAnimation(input, false);

// Loading button
const reset = FormAnimations.submitButtonLoading(button);
// Later...
reset(); // Stop loading
```

### Smooth Scroll
```javascript
// Scroll to element
GSAPAnimations.smoothScroll('#section-id');
```

## 📱 Mobile & Accessibility

All animations are:
- ✅ Mobile-friendly
- ✅ Performance optimized
- ✅ Respect `prefers-reduced-motion`
- ✅ Progressively enhanced

## 🎯 Tips

1. **Don't overdo it** - Less is more with animations
2. **Keep it fast** - Most animations should be 300-600ms
3. **Be consistent** - Use similar animations for similar actions
4. **Test on mobile** - Ensure smooth performance
5. **Use delays wisely** - Stagger animations for lists

## 🐛 Common Issues

**Problem:** Animations not showing
**Solution:** Clear cache and rebuild: `npm run build`

**Problem:** Counters not animating
**Solution:** Ensure the element has `data-counter` attribute

**Problem:** Scroll animations triggering multiple times
**Solution:** Add `data-aos-once="true"` to trigger only once

## 📚 More Information

See [UI_ANIMATIONS.md](./UI_ANIMATIONS.md) for complete documentation.

## 🎉 That's It!

Your UI is now equipped with beautiful, performant animations. Enjoy building! 🚀
