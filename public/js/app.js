/* ═══════════════════════════════════════════════════════════════════════════════
   Espiritu Habit Tracker — Global JavaScript
   public/js/app.js  (served directly, no build step required)
   ═══════════════════════════════════════════════════════════════════════════════ */

'use strict';

// ── CSRF token helper ──────────────────────────────────────────────────────────
function csrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
}

// ══════════════════════════════════════════════════════════════════════════════
// TOAST NOTIFICATIONS
// ══════════════════════════════════════════════════════════════════════════════

const TOAST_ICONS = {
    success: 'ti-circle-check',
    error:   'ti-alert-circle',
    warning: 'ti-alert-triangle',
    info:    'ti-info-circle',
};

function showToast(message, type = 'success', duration = 3000) {
    let stack = document.getElementById('jsToastStack');
    if (!stack) {
        stack = document.createElement('div');
        stack.id = 'jsToastStack';
        stack.className = 'toast-stack';
        document.body.appendChild(stack);
    }

    const toast = document.createElement('div');
    toast.className = `toast-item toast-${type}`;
    toast.innerHTML = `
        <i class="ti ${TOAST_ICONS[type] || TOAST_ICONS.info}"></i>
        <span>${message}</span>
        <button class="toast-close-btn" aria-label="Dismiss"><i class="ti ti-x"></i></button>
    `;

    toast.querySelector('.toast-close-btn').addEventListener('click', () => dismissToastItem(toast));
    stack.appendChild(toast);

    if (duration > 0) {
        setTimeout(() => dismissToastItem(toast), duration);
    }
    return toast;
}

function dismissToastItem(toast) {
    if (!toast || !toast.isConnected) return;
    toast.classList.add('leaving');
    toast.addEventListener('animationend', () => toast.remove(), { once: true });
}

// Wire up existing server-rendered toasts (from session flash)
function initServerToasts() {
    document.querySelectorAll('.toast-container .toast, .toast-container .toast-item').forEach(toast => {
        const closeBtn = toast.querySelector('.toast-close, .toast-close-btn');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                toast.classList.add('hiding');
                toast.addEventListener('animationend', () => toast.remove(), { once: true });
            });
        }
        setTimeout(() => {
            if (toast.isConnected) {
                toast.classList.add('hiding');
                toast.addEventListener('animationend', () => toast.remove(), { once: true });
            }
        }, 3000);
    });
}

// ══════════════════════════════════════════════════════════════════════════════
// AJAX HABIT LOGGING
// ══════════════════════════════════════════════════════════════════════════════

function initHabitLogging() {
    document.querySelectorAll('.habit-log-form').forEach(form => {
        form.addEventListener('submit', handleHabitLog);
    });
}

async function handleHabitLog(e) {
    e.preventDefault();

    const form    = e.currentTarget;
    const button  = form.querySelector('.btn-log');
    const url     = form.action;
    const isLogged = button.classList.contains('done');

    if (isLogged) {
        showToast('Already logged for today!', 'info');
        return;
    }

    // Loading state
    button.classList.add('loading');
    const originalHtml = button.innerHTML;
    button.innerHTML = '<span class="btn-spin-icon"></span>Logging…';

    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        const data = await res.json().catch(() => ({}));

        if (res.ok) {
            // Update button to "Done" state
            button.innerHTML = '<i class="ti ti-check"></i> Done Today';
            button.classList.remove('loading');
            button.classList.add('done', 'just-logged');
            button.removeEventListener('click', null);
            button.addEventListener('animationend', () => button.classList.remove('just-logged'), { once: true });

            // Update habit card streak if returned
            if (data.streak !== undefined) {
                const card = form.closest('.habit-card');
                if (card) {
                    const streakEl = card.querySelector('[data-stat="streak"]');
                    if (streakEl) streakEl.textContent = data.streak;
                }
            }

            showToast(data.message || 'Habit logged for today!', 'success');
        } else {
            button.innerHTML = originalHtml;
            button.classList.remove('loading');
            showToast(data.message || 'Already logged for today.', 'error');
        }
    } catch {
        button.innerHTML = originalHtml;
        button.classList.remove('loading');
        showToast('Network error. Please try again.', 'error');
    }
}

// ══════════════════════════════════════════════════════════════════════════════
// CLIENT-SIDE HABIT FILTERING (instant, no page reload)
// ══════════════════════════════════════════════════════════════════════════════

function initClientFiltering() {
    const categorySelect = document.getElementById('filterCategory');
    const statusSelect   = document.getElementById('filterStatus');
    const sortSelect     = document.getElementById('filterSort');
    const resultsLabel   = document.getElementById('filterResults');

    if (!categorySelect) return;

    [categorySelect, statusSelect, sortSelect].forEach(sel => {
        if (sel) sel.addEventListener('change', applyFilters);
    });

    // Apply on load to reflect server-side pre-selected values
    applyFilters();

    function applyFilters() {
        const category = categorySelect ? categorySelect.value : 'all';
        const status   = statusSelect   ? statusSelect.value   : 'all';
        const sort     = sortSelect     ? sortSelect.value     : 'recent';

        const cards = [...document.querySelectorAll('.habit-card[data-category]')];
        let visible = 0;

        cards.forEach(card => {
            const catMatch = category === 'all' || card.dataset.category === category;
            const stMatch  = status   === 'all' || card.dataset.status   === status;
            const show     = catMatch && stMatch;

            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        // Sort visible cards
        const grid = document.querySelector('.habits-grid');
        if (grid && sort !== 'recent') {
            const visibleCards = cards.filter(c => c.style.display !== 'none');
            visibleCards.sort((a, b) => {
                if (sort === 'alpha') {
                    const na = a.querySelector('.habit-card-name')?.textContent.trim() || '';
                    const nb = b.querySelector('.habit-card-name')?.textContent.trim() || '';
                    return na.localeCompare(nb);
                }
                if (sort === 'streak') {
                    const sa = parseInt(a.dataset.streak || '0', 10);
                    const sb = parseInt(b.dataset.streak || '0', 10);
                    return sb - sa;
                }
                return 0;
            });
            visibleCards.forEach(c => grid.appendChild(c));
        }

        // Empty state
        const emptyState = document.querySelector('.empty-state');
        if (emptyState) {
            emptyState.style.display = visible === 0 ? 'block' : 'none';
        } else if (visible === 0) {
            showNoResults(grid);
        } else {
            removeNoResults(grid);
        }

        // Result count
        if (resultsLabel) {
            resultsLabel.textContent = `${visible} result${visible !== 1 ? 's' : ''}`;
        }
    }
}

let _noResultsEl = null;
function showNoResults(grid) {
    if (_noResultsEl && grid.contains(_noResultsEl)) return;
    _noResultsEl = document.createElement('div');
    _noResultsEl.className = 'empty-state js-no-results';
    _noResultsEl.style.gridColumn = '1 / -1';
    _noResultsEl.innerHTML = `
        <div class="empty-icon"><i class="ti ti-filter-off"></i></div>
        <h3>No habits match</h3>
        <p>Try changing the filters above.</p>
    `;
    grid.appendChild(_noResultsEl);
}
function removeNoResults(grid) {
    const el = grid.querySelector('.js-no-results');
    if (el) el.remove();
}

// ══════════════════════════════════════════════════════════════════════════════
// FORM VALIDATION
// ══════════════════════════════════════════════════════════════════════════════

function initFormValidation() {
    // Habit create form
    const createForm = document.getElementById('createForm');
    if (createForm) {
        createForm.addEventListener('submit', e => {
            if (!validateHabitForm(createForm)) e.preventDefault();
        });
    }

    // Habit edit form
    const editForm = document.getElementById('editForm');
    if (editForm) {
        editForm.addEventListener('submit', e => {
            if (!validateHabitForm(editForm)) e.preventDefault();
        });
    }

    // Profile forms
    const profileForm = document.querySelector('form[action*="/profile"][method-actual="PUT"]');
    if (profileForm) {
        profileForm.addEventListener('submit', e => {
            if (!validateProfileForm(profileForm)) e.preventDefault();
        });
    }
}

function validateHabitForm(form) {
    let valid = true;

    const name = form.querySelector('[name="name"]');
    if (name) {
        clearFieldError(name);
        if (!name.value.trim() || name.value.trim().length < 3) {
            showFieldError(name, 'Habit name must be at least 3 characters.');
            valid = false;
        } else if (name.value.trim().length > 50) {
            showFieldError(name, 'Habit name must be 50 characters or fewer.');
            valid = false;
        }
    }

    const category = form.querySelector('[name="category"]');
    if (category && !category.value) {
        clearFieldError(category);
        showFieldError(category, 'Please select a category.');
        valid = false;
    }

    const targetDays = form.querySelector('[name="target_days"]');
    if (targetDays) {
        clearFieldError(targetDays);
        const val = parseInt(targetDays.value, 10);
        if (isNaN(val) || val < 1 || val > 7) {
            showFieldError(targetDays, 'Target days must be between 1 and 7.');
            valid = false;
        }
    }

    if (!valid) {
        // Scroll to first error
        const firstError = form.querySelector('.form-control.is-invalid');
        if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    return valid;
}

function validateProfileForm(form) {
    let valid = true;

    const name = form.querySelector('[name="full_name"]');
    if (name) {
        clearFieldError(name);
        if (!name.value.trim() || name.value.trim().length < 3) {
            showFieldError(name, 'Full name must be at least 3 characters.');
            valid = false;
        }
    }

    const email = form.querySelector('[name="email"]');
    if (email) {
        clearFieldError(email);
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
            showFieldError(email, 'Please enter a valid email address.');
            valid = false;
        }
    }

    return valid;
}

function showFieldError(input, message) {
    input.classList.add('is-invalid');
    let errEl = input.parentElement.querySelector('.js-field-error');
    if (!errEl) {
        errEl = document.createElement('div');
        errEl.className = 'field-error js-field-error';
        errEl.innerHTML = '<i class="ti ti-alert-circle"></i>';
        input.after(errEl);
    }
    const existing = errEl.querySelector('i');
    errEl.textContent = message;
    if (existing) errEl.prepend(existing);
}

function clearFieldError(input) {
    input.classList.remove('is-invalid');
    const errEl = input.parentElement.querySelector('.js-field-error');
    if (errEl) errEl.remove();
}

// Live clear on input
function initLiveValidation() {
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('input', () => {
            if (input.classList.contains('is-invalid')) {
                clearFieldError(input);
            }
        });
    });
}

// ══════════════════════════════════════════════════════════════════════════════
// PROGRESS BAR ANIMATION ON SCROLL
// ══════════════════════════════════════════════════════════════════════════════

function initProgressBars() {
    const bars = document.querySelectorAll('.progress-fill, .progress-bar-fill');
    if (!bars.length) return;

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const bar = entry.target;
                const target = bar.dataset.width || bar.style.width;
                bar.style.width = '0%';
                requestAnimationFrame(() => {
                    setTimeout(() => { bar.style.width = target; }, 50);
                });
                observer.unobserve(bar);
            }
        });
    }, { threshold: 0.1 });

    bars.forEach(bar => {
        const w = bar.style.width;
        if (w && w !== '0%') {
            bar.dataset.width = w;
            bar.style.width = '0%';
            observer.observe(bar);
        }
    });
}

// ══════════════════════════════════════════════════════════════════════════════
// CARD STAGGER ANIMATION
// ══════════════════════════════════════════════════════════════════════════════

function initCardStagger() {
    const cards = document.querySelectorAll('.habit-card, .stat-card, .profile-header-card');
    cards.forEach((card, i) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(12px)';
        card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 60 + i * 40);
    });
}

// ══════════════════════════════════════════════════════════════════════════════
// MODAL KEYBOARD HANDLING
// ══════════════════════════════════════════════════════════════════════════════

function initModalKeyboard() {
    document.addEventListener('keydown', e => {
        if (e.key !== 'Escape') return;
        const modals = document.querySelectorAll('.modal-overlay.open, .modal-backdrop.open');
        modals.forEach(m => {
            if (typeof closeModal === 'function') {
                closeModal(m.id);
            } else {
                m.classList.remove('open');
                document.body.style.overflow = '';
            }
        });
    });
}

// ══════════════════════════════════════════════════════════════════════════════
// BUTTON RIPPLE EFFECT
// ══════════════════════════════════════════════════════════════════════════════

function initRipple() {
    document.querySelectorAll('.btn-modal-submit, .btn-primary-pill, .topbar-btn-primary').forEach(btn => {
        btn.style.position = 'relative';
        btn.style.overflow = 'hidden';
        btn.addEventListener('click', function(e) {
            const rect   = this.getBoundingClientRect();
            const size   = Math.max(rect.width, rect.height);
            const x      = e.clientX - rect.left - size / 2;
            const y      = e.clientY - rect.top  - size / 2;
            const ripple = document.createElement('span');
            Object.assign(ripple.style, {
                position: 'absolute',
                width:  size + 'px',
                height: size + 'px',
                left:   x + 'px',
                top:    y + 'px',
                borderRadius: '50%',
                background: 'rgba(255,255,255,0.3)',
                transform: 'scale(0)',
                animation: 'rippleOut 0.5s ease forwards',
                pointerEvents: 'none',
            });
            this.appendChild(ripple);
            ripple.addEventListener('animationend', () => ripple.remove());
        });
    });
}

// Inject ripple keyframe once
(function injectRippleCSS() {
    if (document.getElementById('ripple-style')) return;
    const s = document.createElement('style');
    s.id = 'ripple-style';
    s.textContent = '@keyframes rippleOut { to { transform: scale(2.5); opacity: 0; } }';
    document.head.appendChild(s);
})();

// ══════════════════════════════════════════════════════════════════════════════
// CHART DEFAULTS (Chart.js global config)
// ══════════════════════════════════════════════════════════════════════════════

function initChartDefaults() {
    if (typeof Chart === 'undefined') return;
    Chart.defaults.font.family = "'Segoe UI', system-ui, -apple-system, sans-serif";
    Chart.defaults.color = '#9B8A9B';
    Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(45,31,45,0.85)';
    Chart.defaults.plugins.tooltip.padding = 10;
    Chart.defaults.plugins.tooltip.cornerRadius = 8;
    Chart.defaults.animation.duration = 700;
    Chart.defaults.animation.easing   = 'easeOutQuart';
}

// ══════════════════════════════════════════════════════════════════════════════
// HABIT CARD COLORS  (reads data-color / data-pct, avoids Blade in style attrs)
// ══════════════════════════════════════════════════════════════════════════════

function initHabitCardColors() {
    document.querySelectorAll('.habit-card[data-color]').forEach(card => {
        const color = card.dataset.color;
        const pct   = card.dataset.pct   || '0';

        const bar  = card.querySelector('.habit-card-bar');
        const wrap = card.querySelector('.habit-icon-wrap');
        const icon = card.querySelector('.habit-icon-wrap i');
        const fill = card.querySelector('.progress-fill');

        if (bar)  bar.style.background  = color;
        if (wrap) wrap.style.background = color + '22';
        if (icon) icon.style.color      = color;
        if (fill) {
            fill.style.background = `linear-gradient(90deg, ${color}, ${color}CC)`;
            fill.style.width      = pct + '%';
        }
    });
}

// ══════════════════════════════════════════════════════════════════════════════
// MAIN INIT
// ══════════════════════════════════════════════════════════════════════════════

document.addEventListener('DOMContentLoaded', () => {
    initHabitCardColors();   // must run before initProgressBars
    initServerToasts();
    initHabitLogging();
    initClientFiltering();
    initFormValidation();
    initLiveValidation();
    initProgressBars();
    initCardStagger();
    initModalKeyboard();
    initRipple();
    initChartDefaults();
});
