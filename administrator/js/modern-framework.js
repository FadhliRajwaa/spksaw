/**
 * Modern SPK-SAW JavaScript Framework
 * Interactive components and utilities
 */

class ModernFramework {
  constructor() {
    this.init();
  }

  init() {
    this.initSidebar();
    this.initModals();
    this.initTooltips();
    this.initTables();
    this.initForms();
    this.initAlerts();
    this.setupResponsive();
    this.initCalculationSteps();
    this.initDataVisualization();
    this.initResponsiveBehavior();
  }

  // Sidebar Management
  initSidebar() {
    const sidebarToggle = document.querySelector('.modern-header-toggle');
    const sidebar = document.querySelector('.modern-sidebar');
    const header = document.querySelector('.modern-header');
    const main = document.querySelector('.modern-main');

    if (sidebarToggle) {
      sidebarToggle.addEventListener('click', () => {
        sidebar?.classList.toggle('collapsed');
        header?.classList.toggle('sidebar-collapsed');
        main?.classList.toggle('sidebar-collapsed');
        
        // Store state in localStorage
        const isCollapsed = sidebar?.classList.contains('collapsed');
        localStorage.setItem('sidebar-collapsed', isCollapsed);
      });
    }

    // Restore sidebar state
    const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
    if (isCollapsed) {
      sidebar?.classList.add('collapsed');
      header?.classList.add('sidebar-collapsed');
      main?.classList.add('sidebar-collapsed');
    }

    // Mobile sidebar handling
    this.setupMobileSidebar();
  }

  setupMobileSidebar() {
    const sidebarToggle = document.querySelector('.modern-header-toggle');
    const sidebar = document.querySelector('.modern-sidebar');
    const overlay = document.createElement('div');
    overlay.className = 'modern-sidebar-overlay';
    overlay.style.cssText = `
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.5);
      z-index: 999;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
    `;

    if (window.innerWidth <= 1024) {
      document.body.appendChild(overlay);
      
      sidebarToggle?.addEventListener('click', () => {
        sidebar?.classList.toggle('show');
        if (sidebar?.classList.contains('show')) {
          overlay.style.opacity = '1';
          overlay.style.visibility = 'visible';
        } else {
          overlay.style.opacity = '0';
          overlay.style.visibility = 'hidden';
        }
      });

      overlay.addEventListener('click', () => {
        sidebar?.classList.remove('show');
        overlay.style.opacity = '0';
        overlay.style.visibility = 'hidden';
      });
    }
  }

  // Modal Management
  initModals() {
    document.addEventListener('click', (e) => {
      if (e.target.matches('[data-modal-target]')) {
        e.preventDefault();
        const modalId = e.target.getAttribute('data-modal-target');
        this.openModal(modalId);
      }
      
      if (e.target.matches('[data-modal-close]') || e.target.closest('[data-modal-close]')) {
        e.preventDefault();
        this.closeModal(e.target.closest('.modern-modal'));
      }
      
      if (e.target.matches('.modern-modal')) {
        this.closeModal(e.target);
      }
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        const openModal = document.querySelector('.modern-modal.show');
        if (openModal) {
          this.closeModal(openModal);
        }
      }
    });
  }

  openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.classList.add('show');
      document.body.style.overflow = 'hidden';
    }
  }

  closeModal(modal) {
    if (modal) {
      modal.classList.remove('show');
      document.body.style.overflow = '';
    }
  }

  // Enhanced Table Features
  initTables() {
    document.querySelectorAll('.modern-table').forEach(table => {
      this.makeTableResponsive(table);
      this.addTableSearch(table);
      this.addTableSort(table);
    });
  }

  makeTableResponsive(table) {
    if (!table.closest('.modern-table-container')) {
      const wrapper = document.createElement('div');
      wrapper.className = 'modern-table-container';
      table.parentNode.insertBefore(wrapper, table);
      wrapper.appendChild(table);
    }
  }

  addTableSearch(table) {
    const container = table.closest('.modern-table-container');
    if (container && !container.querySelector('.table-search')) {
      const searchWrapper = document.createElement('div');
      searchWrapper.className = 'table-controls mb-3';
      searchWrapper.innerHTML = `
        <div class="modern-flex" style="justify-content: space-between; align-items: center;">
          <div class="table-search">
            <input type="text" class="modern-form-control" placeholder="🔍 Cari data..." style="width: 300px;">
          </div>
          <div class="table-info">
            <span class="text-sm text-muted">Menampilkan <span class="visible-rows">0</span> dari <span class="total-rows">0</span> data</span>
          </div>
        </div>
      `;
      
      container.insertBefore(searchWrapper, table);
      
      const searchInput = searchWrapper.querySelector('input');
      const rows = table.querySelectorAll('tbody tr');
      const totalRows = searchWrapper.querySelector('.total-rows');
      const visibleRows = searchWrapper.querySelector('.visible-rows');
      
      totalRows.textContent = rows.length;
      visibleRows.textContent = rows.length;
      
      searchInput.addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        let visible = 0;
        
        rows.forEach(row => {
          const text = row.textContent.toLowerCase();
          if (text.includes(searchTerm)) {
            row.style.display = '';
            visible++;
          } else {
            row.style.display = 'none';
          }
        });
        
        visibleRows.textContent = visible;
      });
    }
  }

  addTableSort(table) {
    const headers = table.querySelectorAll('th');
    headers.forEach((header, index) => {
      if (!header.classList.contains('no-sort')) {
        header.style.cursor = 'pointer';
        header.style.userSelect = 'none';
        header.innerHTML += ' <i class="fa fa-sort" style="opacity: 0.5; margin-left: 5px;"></i>';
        
        header.addEventListener('click', () => {
          this.sortTable(table, index);
        });
      }
    });
  }

  sortTable(table, columnIndex) {
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const isNumeric = rows.every(row => {
      const cell = row.cells[columnIndex];
      return cell && !isNaN(cell.textContent.trim());
    });
    
    rows.sort((a, b) => {
      const aVal = a.cells[columnIndex]?.textContent.trim() || '';
      const bVal = b.cells[columnIndex]?.textContent.trim() || '';
      
      if (isNumeric) {
        return parseFloat(aVal) - parseFloat(bVal);
      } else {
        return aVal.localeCompare(bVal);
      }
    });
    
    rows.forEach(row => tbody.appendChild(row));
  }

  // Form Enhancements
  initForms() {
    // Add floating labels
    document.querySelectorAll('.modern-form-control').forEach(input => {
      this.addFloatingLabel(input);
    });

    // Form validation
    document.querySelectorAll('form').forEach(form => {
      form.addEventListener('submit', (e) => {
        if (!this.validateForm(form)) {
          e.preventDefault();
        }
      });
    });

    // Auto-resize textareas
    document.querySelectorAll('textarea.modern-form-control').forEach(textarea => {
      textarea.addEventListener('input', () => {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
      });
    });
  }

  addFloatingLabel(input) {
    if (input.placeholder && !input.closest('.floating-label')) {
      const wrapper = document.createElement('div');
      wrapper.className = 'floating-label';
      wrapper.style.cssText = `
        position: relative;
        margin-bottom: 1.5rem;
      `;
      
      const label = document.createElement('label');
      label.textContent = input.placeholder;
      label.style.cssText = `
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        transition: all 0.3s ease;
        pointer-events: none;
        background: white;
        padding: 0 0.25rem;
        color: #64748b;
        font-size: 0.875rem;
      `;
      
      input.parentNode.insertBefore(wrapper, input);
      wrapper.appendChild(input);
      wrapper.appendChild(label);
      
      const updateLabel = () => {
        if (input.value || input === document.activeElement) {
          label.style.top = '0';
          label.style.fontSize = '0.75rem';
          label.style.color = 'var(--accent-blue)';
        } else {
          label.style.top = '50%';
          label.style.fontSize = '0.875rem';
          label.style.color = '#64748b';
        }
      };
      
      input.addEventListener('focus', updateLabel);
      input.addEventListener('blur', updateLabel);
      input.addEventListener('input', updateLabel);
      updateLabel();
    }
  }

  validateForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
      if (!field.value.trim()) {
        this.showFieldError(field, 'Field ini wajib diisi');
        isValid = false;
      } else {
        this.clearFieldError(field);
      }
    });
    
    return isValid;
  }

  showFieldError(field, message) {
    this.clearFieldError(field);
    
    const errorElement = document.createElement('div');
    errorElement.className = 'field-error';
    errorElement.style.cssText = `
      color: #ef4444;
      font-size: 0.75rem;
      margin-top: 0.25rem;
    `;
    errorElement.textContent = message;
    
    field.parentNode.appendChild(errorElement);
    field.style.borderColor = '#ef4444';
  }

  clearFieldError(field) {
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
      existingError.remove();
    }
    field.style.borderColor = '';
  }

  // Alert System
  initAlerts() {
    // Auto-dismiss alerts
    document.querySelectorAll('.modern-alert[data-dismiss]').forEach(alert => {
      const dismissTime = parseInt(alert.getAttribute('data-dismiss')) || 5000;
      setTimeout(() => {
        this.dismissAlert(alert);
      }, dismissTime);
    });

    // Manual dismiss
    document.addEventListener('click', (e) => {
      if (e.target.matches('.alert-dismiss')) {
        e.preventDefault();
        this.dismissAlert(e.target.closest('.modern-alert'));
      }
    });
  }

  dismissAlert(alert) {
    if (alert) {
      alert.style.transition = 'all 0.3s ease';
      alert.style.opacity = '0';
      alert.style.transform = 'translateX(100%)';
      setTimeout(() => {
        alert.remove();
      }, 300);
    }
  }

  showAlert(type, message, dismissible = true) {
    const alert = document.createElement('div');
    alert.className = `modern-alert modern-alert-${type}`;
    alert.innerHTML = `
      <i class="fa fa-${this.getAlertIcon(type)}"></i>
      <span>${message}</span>
      ${dismissible ? '<button class="alert-dismiss" style="margin-left: auto; background: none; border: none; font-size: 1.2rem; cursor: pointer;">&times;</button>' : ''}
    `;
    
    const container = document.querySelector('.modern-main') || document.body;
    container.insertBefore(alert, container.firstChild);
    
    if (dismissible) {
      setTimeout(() => {
        this.dismissAlert(alert);
      }, 5000);
    }
    
    return alert;
  }

  getAlertIcon(type) {
    const icons = {
      success: 'check-circle',
      danger: 'exclamation-circle',
      warning: 'exclamation-triangle',
      info: 'info-circle'
    };
    return icons[type] || 'info-circle';
  }

  // Tooltip System
  initTooltips() {
    document.querySelectorAll('[data-tooltip]').forEach(element => {
      element.addEventListener('mouseenter', (e) => {
        this.showTooltip(e.target);
      });
      
      element.addEventListener('mouseleave', (e) => {
        this.hideTooltip(e.target);
      });
    });
  }

  showTooltip(element) {
    const text = element.getAttribute('data-tooltip');
    const tooltip = document.createElement('div');
    tooltip.className = 'modern-tooltip';
    tooltip.textContent = text;
    tooltip.style.cssText = `
      position: absolute;
      background: var(--primary-dark);
      color: white;
      padding: 0.5rem 0.75rem;
      border-radius: var(--border-radius-sm);
      font-size: 0.75rem;
      z-index: 10000;
      pointer-events: none;
      opacity: 0;
      transition: opacity 0.2s ease;
    `;
    
    document.body.appendChild(tooltip);
    
    const rect = element.getBoundingClientRect();
    tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
    tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + 'px';
    
    requestAnimationFrame(() => {
      tooltip.style.opacity = '1';
    });
    
    element._tooltip = tooltip;
  }

  hideTooltip(element) {
    if (element._tooltip) {
      element._tooltip.remove();
      delete element._tooltip;
    }
  }

  // Responsive Utilities
  setupResponsive() {
    window.addEventListener('resize', () => {
      this.handleResize();
    });
    
    this.handleResize();
  }

  handleResize() {
    // Handle mobile sidebar
    const sidebar = document.querySelector('.modern-sidebar');
    const overlay = document.querySelector('.modern-sidebar-overlay');
    
    if (window.innerWidth > 1024) {
      sidebar?.classList.remove('show');
      if (overlay) {
        overlay.style.opacity = '0';
        overlay.style.visibility = 'hidden';
      }
    }

    // Handle table responsiveness
    document.querySelectorAll('.modern-table').forEach(table => {
      if (window.innerWidth <= 768) {
        table.classList.add('table-responsive');
      } else {
        table.classList.remove('table-responsive');
      }
    });
  }

  // Loading States
  showLoading(element, text = 'Memuat...') {
    const loader = document.createElement('div');
    loader.className = 'modern-loading-overlay';
    loader.innerHTML = `
      <div class="modern-loading">
        <div class="modern-spinner"></div>
        <span>${text}</span>
      </div>
    `;
    loader.style.cssText = `
      position: absolute;
      inset: 0;
      background: rgba(255, 255, 255, 0.9);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
    `;
    
    if (element.style.position !== 'relative' && element.style.position !== 'absolute') {
      element.style.position = 'relative';
    }
    
    element.appendChild(loader);
    return loader;
  }

  hideLoading(element) {
    const loader = element.querySelector('.modern-loading-overlay');
    if (loader) {
      loader.remove();
    }
  }

  // Animation Utilities
  fadeIn(element, duration = 300) {
    element.style.opacity = '0';
    element.style.display = 'block';
    
    let opacity = 0;
    const timer = setInterval(() => {
      opacity += 50 / duration;
      if (opacity >= 1) {
        clearInterval(timer);
        opacity = 1;
      }
      element.style.opacity = opacity;
    }, 50);
  }

  fadeOut(element, duration = 300) {
    let opacity = 1;
    const timer = setInterval(() => {
      opacity -= 50 / duration;
      if (opacity <= 0) {
        clearInterval(timer);
        element.style.display = 'none';
        opacity = 0;
      }
      element.style.opacity = opacity;
    }, 50);
  }

  // Data Utilities
  formatNumber(number, decimals = 0) {
    return new Intl.NumberFormat('id-ID', {
      minimumFractionDigits: decimals,
      maximumFractionDigits: decimals
    }).format(number);
  }

  formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR'
    }).format(amount);
  }

  formatDate(date, options = {}) {
    const defaultOptions = {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    };
    return new Intl.DateTimeFormat('id-ID', { ...defaultOptions, ...options }).format(new Date(date));
  }

  // Initialize calculation step indicators
  initCalculationSteps() {
    const steps = document.querySelectorAll('.step');
    steps.forEach((step, index) => {
      step.addEventListener('click', () => {
        this.updateStepProgress(index);
      });
    });
  }

  // Update step progress
  updateStepProgress(activeIndex) {
    const steps = document.querySelectorAll('.step');
    steps.forEach((step, index) => {
      step.classList.remove('active', 'completed');
      if (index < activeIndex) {
        step.classList.add('completed');
      } else if (index === activeIndex) {
        step.classList.add('active');
      }
    });
  }

  // Initialize data visualization features
  initDataVisualization() {
    // Score highlighting
    const scores = document.querySelectorAll('[data-score]');
    scores.forEach(score => {
      const value = parseFloat(score.dataset.score);
      if (value >= 0.8) {
        score.classList.add('score-high');
      } else if (value >= 0.5) {
        score.classList.add('score-medium');
      } else {
        score.classList.add('score-low');
      }
    });

    // Enhanced table interactions
    const calculationTables = document.querySelectorAll('.table-calculation');
    calculationTables.forEach(table => {
      const rows = table.querySelectorAll('tbody tr');
      rows.forEach(row => {
        row.addEventListener('mouseenter', () => {
          row.classList.add('table-hover-highlight');
        });
        row.addEventListener('mouseleave', () => {
          row.classList.remove('table-hover-highlight');
        });
      });
    });
  }

  // Initialize responsive behavior
  initResponsiveBehavior() {
    const mediaQuery = window.matchMedia('(max-width: 768px)');
    const handleResponsive = (e) => {
      if (e.matches) {
        this.enableMobileMode();
      } else {
        this.enableDesktopMode();
      }
    };
    
    mediaQuery.addListener(handleResponsive);
    handleResponsive(mediaQuery);
  }

  // Enable mobile-specific behaviors
  enableMobileMode() {
    document.body.classList.add('mobile-mode');
    
    // Adjust table scrolling
    const tables = document.querySelectorAll('.table-responsive');
    tables.forEach(table => {
      table.style.overflowX = 'auto';
    });

    // Adjust form layouts
    const formGroups = document.querySelectorAll('.form-group');
    formGroups.forEach(group => {
      group.classList.add('mobile-form-group');
    });
  }

  // Enable desktop-specific behaviors
  enableDesktopMode() {
    document.body.classList.remove('mobile-mode');
    
    // Reset mobile adjustments
    const tables = document.querySelectorAll('.table-responsive');
    tables.forEach(table => {
      table.style.overflowX = '';
    });

    const formGroups = document.querySelectorAll('.form-group');
    formGroups.forEach(group => {
      group.classList.remove('mobile-form-group');
    });
  }
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  window.modernFramework = new ModernFramework();
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
  module.exports = ModernFramework;
}
