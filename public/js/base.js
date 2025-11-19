/* ============================================
   GrowDev - Vanilla JavaScript Utilities
   Modern, Lightweight, No Frameworks Required
   ============================================ */

// ============================================
// Dropdown Functionality
// ============================================

function toggleDropdown(event) {
  event.preventDefault();
  event.stopPropagation();

  const button = event.currentTarget;
  const dropdown = button.parentElement;
  const menu = dropdown.querySelector('.dropdown-menu');

  // Close all other open dropdowns
  document.querySelectorAll('.dropdown-menu.show').forEach(otherMenu => {
    if (otherMenu !== menu) {
      otherMenu.classList.remove('show');
    }
  });

  // Toggle current dropdown
  menu.classList.toggle('show');
}

// Close dropdown when clicking outside
document.addEventListener('click', (event) => {
  if (!event.target.closest('.dropdown')) {
    document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
      menu.classList.remove('show');
    });
  }
});

// ============================================
// Mobile Menu Toggle
// ============================================

function toggleMobileMenu(event) {
  event.preventDefault();
  const mobileMenu = document.getElementById('mobile-menu');
  if (mobileMenu) {
    mobileMenu.classList.toggle('hidden');
  }
}

// Close mobile menu when a link is clicked
document.querySelectorAll('.mobile-menu-item').forEach(item => {
  item.addEventListener('click', () => {
    const mobileMenu = document.getElementById('mobile-menu');
    if (mobileMenu) {
      mobileMenu.classList.add('hidden');
    }
  });
});

// ============================================
// Modal Functionality
// ============================================

class Modal {
  constructor(modalId) {
    this.modal = document.getElementById(modalId);
    if (!this.modal) {
      console.error(`Modal with id "${modalId}" not found`);
      return;
    }
    this.setupListeners();
  }

  setupListeners() {
    // Close button
    const closeBtn = this.modal.querySelector('.modal-close');
    if (closeBtn) {
      closeBtn.addEventListener('click', () => this.close());
    }

    // Close on backdrop click
    this.modal.addEventListener('click', (e) => {
      if (e.target === this.modal) {
        this.close();
      }
    });

    // Close on ESC key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && this.modal.classList.contains('show')) {
        this.close();
      }
    });
  }

  open() {
    this.modal.classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  close() {
    this.modal.classList.remove('show');
    document.body.style.overflow = '';
  }

  toggle() {
    if (this.modal.classList.contains('show')) {
      this.close();
    } else {
      this.open();
    }
  }
}

// ============================================
// Form Handling
// ============================================

class FormHandler {
  constructor(formId) {
    this.form = document.getElementById(formId);
    if (!this.form) {
      console.error(`Form with id "${formId}" not found`);
      return;
    }
    this.setupListeners();
  }

  setupListeners() {
    this.form.addEventListener('submit', (e) => this.handleSubmit(e));
    
    // Real-time validation
    this.form.querySelectorAll('input, textarea, select').forEach(field => {
      field.addEventListener('change', () => this.validateField(field));
      field.addEventListener('blur', () => this.validateField(field));
    });
  }

  validateField(field) {
    const isValid = field.checkValidity();
    const errorContainer = field.parentElement.querySelector('.form-error');

    if (!isValid && field.value) {
      if (errorContainer) {
        errorContainer.textContent = field.validationMessage || 'This field is invalid';
        errorContainer.style.display = 'block';
      }
      field.style.borderColor = 'var(--color-danger)';
    } else {
      if (errorContainer) {
        errorContainer.style.display = 'none';
      }
      field.style.borderColor = '';
    }
  }

  handleSubmit(e) {
    let isValid = true;
    this.form.querySelectorAll('input, textarea, select').forEach(field => {
      if (!field.checkValidity()) {
        this.validateField(field);
        isValid = false;
      }
    });

    if (!isValid) {
      e.preventDefault();
    }
  }

  reset() {
    this.form.reset();
    this.form.querySelectorAll('.form-error').forEach(error => {
      error.style.display = 'none';
    });
  }
}

// ============================================
// Toast Notifications
// ============================================

class Toast {
  constructor(message, type = 'info', duration = 3000) {
    this.message = message;
    this.type = type; // success, danger, warning, info
    this.duration = duration;
    this.create();
  }

  create() {
    const toast = document.createElement('div');
    toast.className = `toast toast-${this.type}`;
    toast.textContent = this.message;
    toast.style.cssText = `
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 16px 24px;
      background: ${this.getBackgroundColor()};
      color: ${this.getTextColor()};
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      z-index: 9999;
      animation: slideLeft 0.3s ease-in-out;
      max-width: 400px;
    `;

    document.body.appendChild(toast);

    if (this.duration) {
      setTimeout(() => {
        toast.style.animation = 'slideRight 0.3s ease-in-out';
        setTimeout(() => toast.remove(), 300);
      }, this.duration);
    }

    return toast;
  }

  getBackgroundColor() {
    const colors = {
      success: 'var(--color-success)',
      danger: 'var(--color-danger)',
      warning: 'var(--color-warning)',
      info: 'var(--color-info)',
    };
    return colors[this.type] || colors.info;
  }

  getTextColor() {
    return 'var(--color-white)';
  }
}

// Helper function for quick toast
function showToast(message, type = 'info') {
  return new Toast(message, type);
}

// ============================================
// Confirm Dialog
// ============================================

function confirm(message, onConfirm, onCancel) {
  const dialog = document.createElement('div');
  dialog.className = 'modal show';
  dialog.style.cssText = `
    display: flex;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 10000;
    justify-content: center;
    align-items: center;
  `;

  const content = document.createElement('div');
  content.className = 'modal-content';
  content.style.cssText = `
    background: white;
    padding: 30px;
    border-radius: 12px;
    max-width: 400px;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
  `;

  const messageEl = document.createElement('p');
  messageEl.textContent = message;
  messageEl.style.marginBottom = '20px';

  const buttonsContainer = document.createElement('div');
  buttonsContainer.style.cssText = 'display: flex; gap: 10px; justify-content: center;';

  const cancelBtn = document.createElement('button');
  cancelBtn.className = 'btn btn-secondary';
  cancelBtn.textContent = 'Cancel';
  cancelBtn.onclick = () => {
    dialog.remove();
    if (onCancel) onCancel();
  };

  const confirmBtn = document.createElement('button');
  confirmBtn.className = 'btn btn-danger';
  confirmBtn.textContent = 'Confirm';
  confirmBtn.onclick = () => {
    dialog.remove();
    if (onConfirm) onConfirm();
  };

  buttonsContainer.appendChild(cancelBtn);
  buttonsContainer.appendChild(confirmBtn);

  content.appendChild(messageEl);
  content.appendChild(buttonsContainer);
  dialog.appendChild(content);

  document.body.appendChild(dialog);

  // Close on background click
  dialog.addEventListener('click', (e) => {
    if (e.target === dialog) {
      dialog.remove();
      if (onCancel) onCancel();
    }
  });

  // Focus confirm button
  confirmBtn.focus();

  return dialog;
}

// ============================================
// Utility Functions
// ============================================

// Format currency
function formatCurrency(amount, currency = 'USD') {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: currency,
  }).format(amount);
}

// Format date
function formatDate(date, format = 'MM/DD/YYYY') {
  const d = new Date(date);
  const day = String(d.getDate()).padStart(2, '0');
  const month = String(d.getMonth() + 1).padStart(2, '0');
  const year = d.getFullYear();
  
  return format
    .replace('DD', day)
    .replace('MM', month)
    .replace('YYYY', year);
}

// Debounce function
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

// Throttle function
function throttle(func, limit) {
  let inThrottle;
  return function(...args) {
    if (!inThrottle) {
      func.apply(this, args);
      inThrottle = true;
      setTimeout(() => inThrottle = false, limit);
    }
  };
}

// Check if element is in viewport
function isInViewport(el) {
  const rect = el.getBoundingClientRect();
  return (
    rect.top >= 0 &&
    rect.left >= 0 &&
    rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
    rect.right <= (window.innerWidth || document.documentElement.clientWidth)
  );
}

// Smooth scroll to element
function scrollToElement(element, offset = 0) {
  const elementPosition = element.getBoundingClientRect().top + window.scrollY - offset;
  window.scrollTo({
    top: elementPosition,
    behavior: 'smooth'
  });
}

// Make element visible when in viewport (lazy loading)
function setupLazyElements(selector) {
  const elements = document.querySelectorAll(selector);
  
  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });

    elements.forEach(el => observer.observe(el));
  } else {
    // Fallback for older browsers
    elements.forEach(el => el.classList.add('visible'));
  }
}

// ============================================
// DOM Ready Handler
// ============================================

function onReady(callback) {
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', callback);
  } else {
    callback();
  }
}

// Export for use
if (typeof module !== 'undefined' && module.exports) {
  module.exports = {
    Modal,
    FormHandler,
    Toast,
    showToast,
    confirm,
    formatCurrency,
    formatDate,
    debounce,
    throttle,
    isInViewport,
    scrollToElement,
    setupLazyElements,
    onReady,
  };
}
