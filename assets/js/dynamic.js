// Dynamic Website Features - Real-time Updates and AJAX Loading

// Dynamic Package Loading
class DynamicPackages {
    constructor() {
        this.currentPage = 1;
        this.loading = false;
        this.hasMore = true;
        this.init();
    }

    init() {
        // Real-time search
        const searchInput = document.getElementById('package-search');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.searchPackages(e.target.value);
                }, 500); // Debounce 500ms
            });
        }

        // Dynamic destination filter
        const destinationFilter = document.getElementById('destination-filter');
        if (destinationFilter) {
            destinationFilter.addEventListener('change', (e) => {
                this.filterByDestination(e.target.value);
            });
        }

        // Load more packages (infinite scroll)
        this.setupInfiniteScroll();

        // Load destinations dynamically
        this.loadDestinations();
    }

    async searchPackages(searchTerm) {
        const packagesGrid = document.getElementById('packages-grid');
        if (!packagesGrid) return;

        this.showLoading();
        
        try {
            const response = await fetch(`api/get-packages.php?search=${encodeURIComponent(searchTerm)}`);
            const data = await response.json();
            
            if (data.success) {
                this.renderPackages(data.packages, true);
                this.updateResultsCount(data.total);
            }
        } catch (error) {
            console.error('Search error:', error);
            this.showError('Error searching packages');
        } finally {
            this.hideLoading();
        }
    }

    async filterByDestination(destination) {
        const packagesGrid = document.getElementById('packages-grid');
        if (!packagesGrid) return;

        this.showLoading();
        
        try {
            const url = destination 
                ? `api/get-packages.php?destination=${encodeURIComponent(destination)}`
                : 'api/get-packages.php';
            
            const response = await fetch(url);
            const data = await response.json();
            
            if (data.success) {
                this.renderPackages(data.packages, true);
                this.updateResultsCount(data.total);
            }
        } catch (error) {
            console.error('Filter error:', error);
            this.showError('Error filtering packages');
        } finally {
            this.hideLoading();
        }
    }

    async loadDestinations() {
        const destinationSelect = document.getElementById('destination-filter');
        if (!destinationSelect) return;

        try {
            const response = await fetch('api/get-destinations.php');
            const data = await response.json();
            
            if (data.success && data.destinations) {
                // Clear existing options except "All Destinations"
                const allOption = destinationSelect.querySelector('option[value=""]');
                destinationSelect.innerHTML = '';
                if (allOption) {
                    destinationSelect.appendChild(allOption);
                }

                // Add destinations
                data.destinations.forEach(dest => {
                    const option = document.createElement('option');
                    option.value = dest.destination;
                    option.textContent = `${dest.destination} (${dest.count})`;
                    destinationSelect.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading destinations:', error);
        }
    }

    renderPackages(packages, replace = false) {
        const packagesGrid = document.getElementById('packages-grid');
        if (!packagesGrid) return;

        if (replace) {
            packagesGrid.innerHTML = '';
        }

        if (packages.length === 0) {
            packagesGrid.innerHTML = '<div class="no-results"><p>No packages found matching your criteria.</p></div>';
            return;
        }

        packages.forEach(package => {
            const packageCard = this.createPackageCard(package);
            packagesGrid.appendChild(packageCard);
        });

        // Add fade-in animation
        const cards = packagesGrid.querySelectorAll('.package-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.4s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 50);
        });
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    createPackageCard(package) {
        const card = document.createElement('div');
        card.className = 'package-card';
        
        const baseUrl = typeof window.BASE_URL !== 'undefined' ? window.BASE_URL : (typeof BASE_URL !== 'undefined' ? BASE_URL : window.location.origin + '/');
        
        const discountBadge = package.discount_price 
            ? `<span class="discount-badge">Save ${package.discount_percent}%</span>`
            : '';
        
        const image = package.image 
            ? `<div class="package-image">
                <img src="${baseUrl}${package.image}" alt="${this.escapeHtml(package.title)}" loading="lazy">
                ${discountBadge}
               </div>`
            : '';
        
        const price = package.discount_price
            ? `<span class="old-price">${package.formatted_price}</span>
               <span class="current-price">${package.formatted_discount_price}</span>`
            : `<span class="current-price">${package.formatted_price}</span>`;

        card.innerHTML = `
            ${image}
            <div class="package-content">
                <h3>${this.escapeHtml(package.title)}</h3>
                <p class="package-destination">📍 ${this.escapeHtml(package.destination)}</p>
                <p class="package-duration">⏱️ ${this.escapeHtml(package.duration)}</p>
                <div class="package-price">${price}</div>
                <p class="package-description">${this.escapeHtml(package.short_description)}</p>
                <div class="package-actions">
                    <a href="package-details.php?id=${package.id}" class="btn btn-primary">View Details</a>
                    <a href="booking.php?package=${package.id}" class="btn btn-secondary">Book Now</a>
                </div>
            </div>
        `;

        return card;
    }

    setupInfiniteScroll() {
        const packagesGrid = document.getElementById('packages-grid');
        if (!packagesGrid) return;

        window.addEventListener('scroll', () => {
            if (this.loading || !this.hasMore) return;

            const scrollPosition = window.innerHeight + window.scrollY;
            const pageHeight = document.documentElement.scrollHeight;

            if (scrollPosition >= pageHeight - 200) {
                this.loadMorePackages();
            }
        });
    }

    async loadMorePackages() {
        if (this.loading || !this.hasMore) return;

        this.loading = true;
        this.showLoading();

        try {
            const response = await fetch(`api/get-packages.php?limit=6&offset=${(this.currentPage - 1) * 6}`);
            const data = await response.json();

            if (data.success && data.packages.length > 0) {
                this.renderPackages(data.packages, false);
                this.currentPage++;
                this.hasMore = data.packages.length === 6;
            } else {
                this.hasMore = false;
            }
        } catch (error) {
            console.error('Load more error:', error);
        } finally {
            this.loading = false;
            this.hideLoading();
        }
    }

    showLoading() {
        let loader = document.getElementById('packages-loader');
        if (!loader) {
            loader = document.createElement('div');
            loader.id = 'packages-loader';
            loader.className = 'packages-loader';
            loader.innerHTML = '<div class="loader-spinner"></div><p>Loading packages...</p>';
            const grid = document.getElementById('packages-grid');
            if (grid && grid.parentNode) {
                grid.parentNode.insertBefore(loader, grid.nextSibling);
            }
        }
        loader.style.display = 'block';
    }

    hideLoading() {
        const loader = document.getElementById('packages-loader');
        if (loader) {
            loader.style.display = 'none';
        }
    }

    showError(message) {
        const grid = document.getElementById('packages-grid');
        if (grid) {
            grid.innerHTML = `<div class="error-message">${message}</div>`;
        }
    }

    updateResultsCount(total) {
        const countElement = document.getElementById('results-count');
        if (countElement) {
            countElement.textContent = `${total} package${total !== 1 ? 's' : ''} found`;
        }
    }
}

// Real-time Booking Counter
class BookingCounter {
    constructor() {
        this.init();
    }

    async init() {
        await this.updateBookingCount();
        setInterval(() => this.updateBookingCount(), 30000); // Update every 30 seconds
    }

    async updateBookingCount() {
        try {
            const response = await fetch('api/get-stats.php');
            const data = await response.json();
            
            if (data.success) {
                this.updateCounters(data.stats);
            }
        } catch (error) {
            console.error('Error updating stats:', error);
        }
    }

    updateCounters(stats) {
        const elements = {
            'total-packages': stats.total_packages,
            'active-packages': stats.active_packages,
            'total-bookings': stats.total_bookings,
            'pending-bookings': stats.pending_bookings
        };

        Object.keys(elements).forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                this.animateCounter(element, elements[id]);
            }
        });
    }

    animateCounter(element, target) {
        const current = parseInt(element.textContent) || 0;
        if (current === target) return;

        const increment = target > current ? 1 : -1;
        const duration = 1000;
        const steps = Math.abs(target - current);
        const stepDuration = duration / steps;

        let currentValue = current;
        const timer = setInterval(() => {
            currentValue += increment;
            element.textContent = currentValue;
            
            if (currentValue === target) {
                clearInterval(timer);
            }
        }, stepDuration);
    }
}

// Live Form Validation
class LiveFormValidation {
    constructor() {
        this.init();
    }

    init() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('blur', () => this.validateField(input));
                input.addEventListener('input', () => this.clearError(input));
            });
        });
    }

    validateField(field) {
        const value = field.value.trim();
        const fieldName = field.name;
        let isValid = true;
        let errorMessage = '';

        switch(fieldName) {
            case 'email':
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    isValid = false;
                    errorMessage = 'Please enter a valid email address';
                }
                break;
            case 'phone':
                if (!/^[\d\s\-\+\(\)]+$/.test(value) || value.replace(/\D/g, '').length < 10) {
                    isValid = false;
                    errorMessage = 'Please enter a valid phone number';
                }
                break;
            case 'travel_date':
                const selectedDate = new Date(value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                if (selectedDate < today) {
                    isValid = false;
                    errorMessage = 'Travel date must be in the future';
                }
                break;
        }

        if (field.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = 'This field is required';
        }

        this.showFieldError(field, isValid, errorMessage);
        return isValid;
    }

    showFieldError(field, isValid, message) {
        let errorElement = field.parentNode.querySelector('.field-error');
        
        if (!isValid) {
            if (!errorElement) {
                errorElement = document.createElement('span');
                errorElement.className = 'field-error';
                field.parentNode.appendChild(errorElement);
            }
            errorElement.textContent = message;
            field.classList.add('error');
        } else {
            if (errorElement) {
                errorElement.remove();
            }
            field.classList.remove('error');
        }
    }

    clearError(field) {
        if (field.classList.contains('error')) {
            const errorElement = field.parentNode.querySelector('.field-error');
            if (errorElement) {
                errorElement.remove();
            }
            field.classList.remove('error');
        }
    }
}

// Make BASE_URL available globally (will be set by PHP if not defined)
if (typeof BASE_URL === 'undefined') {
    window.BASE_URL = window.location.origin + '/';
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Initialize dynamic packages if on packages page
    if (document.getElementById('packages-grid')) {
        window.dynamicPackages = new DynamicPackages();
    }

    // Initialize booking counter if on admin dashboard
    if (document.getElementById('total-packages')) {
        window.bookingCounter = new BookingCounter();
    }

    // Initialize live form validation
    window.liveFormValidation = new LiveFormValidation();
});

