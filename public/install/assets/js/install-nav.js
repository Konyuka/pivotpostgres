/**
 * Install Navigation Helper
 * Handles navigation between installation steps with fallback methods
 */

// Install Navigation Object
const InstallNav = {

    // Base install URL
    getInstallBaseUrl: function() {
        const currentUrl = window.location.href;
        if (currentUrl.includes('/install/')) {
            return currentUrl.substring(0, currentUrl.indexOf('/install/') + 9);
        }
        return window.location.origin + '/install/';
    },

    // Navigate to specific step
    navigateToStep: function(stepFile) {
        const baseUrl = this.getInstallBaseUrl();
        const targetUrl = baseUrl + stepFile;

        console.log('Install Navigation: Attempting to navigate to', targetUrl);

        // Method 1: Direct navigation
        try {
            window.location.href = targetUrl;
        } catch (error) {
            console.error('Primary navigation failed:', error);
            this.fallbackNavigation(targetUrl);
        }

        // Fallback timeout
        setTimeout(() => {
            if (window.location.href !== targetUrl) {
                this.fallbackNavigation(targetUrl);
            }
        }, 2000);
    },

    // Fallback navigation methods
    fallbackNavigation: function(targetUrl) {
        console.log('Install Navigation: Using fallback methods for', targetUrl);

        try {
            // Method 2: Location replace
            window.location.replace(targetUrl);

            // Method 3: Form submission as last resort
            setTimeout(() => {
                if (window.location.href !== targetUrl) {
                    this.createHiddenForm(targetUrl);
                }
            }, 1000);

        } catch (error) {
            console.error('Fallback navigation failed:', error);
            alert('Navigation failed. Please manually go to: ' + targetUrl);
        }
    },

    // Create hidden form for navigation
    createHiddenForm: function(targetUrl) {
        console.log('Install Navigation: Creating hidden form for', targetUrl);

        const form = document.createElement('form');
        form.method = 'GET';
        form.action = targetUrl;
        form.style.display = 'none';

        document.body.appendChild(form);
        form.submit();
    },

    // Debug information
    debugInfo: function() {
        return {
            currentUrl: window.location.href,
            baseUrl: this.getInstallBaseUrl(),
            timestamp: new Date().toISOString()
        };
    }
};

// Make it globally available
window.InstallNav = InstallNav;

// Auto-initialization
document.addEventListener('DOMContentLoaded', function() {
    console.log('Install Navigation Helper loaded:', InstallNav.debugInfo());
});
