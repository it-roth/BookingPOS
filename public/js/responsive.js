/**
 * Enhanced Responsive JavaScript for BookingPOS
 * Ensures full-screen experience across all devices
 */

document.addEventListener('DOMContentLoaded', function() {
    // Responsive state management
    const responsiveState = {
        isMobile: false,
        isTablet: false,
        isDesktop: false,
        isLandscape: false,
        isPortrait: false,
        currentBreakpoint: 'mobile',
        viewportWidth: window.innerWidth,
        viewportHeight: window.innerHeight
    };

    // Breakpoint definitions
    const breakpoints = {
        mobile: 767,
        tablet: 991,
        desktop: 1200
    };

    // Initialize responsive behavior
    function initializeResponsive() {
        updateResponsiveState();
        setupEventListeners();
        applyResponsiveClasses();
        setupFullScreenSupport();
        setupTouchSupport();
        setupKeyboardSupport();
    }

    // Update responsive state based on current viewport
    function updateResponsiveState() {
        const width = window.innerWidth;
        const height = window.innerHeight;
        
        responsiveState.viewportWidth = width;
        responsiveState.viewportHeight = height;
        responsiveState.isLandscape = width > height;
        responsiveState.isPortrait = height > width;
        
        // Determine device type
        if (width <= breakpoints.mobile) {
            responsiveState.isMobile = true;
            responsiveState.isTablet = false;
            responsiveState.isDesktop = false;
            responsiveState.currentBreakpoint = 'mobile';
        } else if (width <= breakpoints.tablet) {
            responsiveState.isMobile = false;
            responsiveState.isTablet = true;
            responsiveState.isDesktop = false;
            responsiveState.currentBreakpoint = 'tablet';
        } else {
            responsiveState.isMobile = false;
            responsiveState.isTablet = false;
            responsiveState.isDesktop = true;
            responsiveState.currentBreakpoint = 'desktop';
        }
    }

    // Apply responsive classes to body
    function applyResponsiveClasses() {
        const body = document.body;
        
        // Remove existing responsive classes
        body.classList.remove('mobile-view', 'tablet-view', 'desktop-view', 'landscape', 'portrait');
        
        // Add current responsive classes
        body.classList.add(`${responsiveState.currentBreakpoint}-view`);
        
        if (responsiveState.isLandscape) {
            body.classList.add('landscape');
        } else {
            body.classList.add('portrait');
        }
    }

    // Setup event listeners for responsive behavior
    function setupEventListeners() {
        // Window resize handler
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function() {
                updateResponsiveState();
                applyResponsiveClasses();
                handleResponsiveLayout();
            }, 250);
        });

        // Orientation change handler
        window.addEventListener('orientationchange', function() {
            setTimeout(function() {
                updateResponsiveState();
                applyResponsiveClasses();
                handleResponsiveLayout();
            }, 500);
        });

        // Touch events for mobile
        if (responsiveState.isMobile) {
            setupTouchEvents();
        }

        // Keyboard events
        setupKeyboardEvents();
    }

    // Handle responsive layout changes
    function handleResponsiveLayout() {
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        const mainHeader = document.querySelector('.main-header');
        const overlay = document.querySelector('.overlay');

        if (!sidebar || !mainContent || !mainHeader) return;

        if (responsiveState.isMobile) {
            // Mobile layout
            sidebar.style.left = '-100%';
            mainContent.style.marginLeft = '0';
            mainContent.style.width = '100%';
            mainHeader.style.left = '0';
            mainHeader.style.width = '100%';
            
            if (overlay) {
                overlay.classList.remove('show');
            }
        } else if (responsiveState.isTablet) {
            // Tablet layout
            sidebar.style.left = '-320px';
            mainContent.style.marginLeft = '0';
            mainContent.style.width = '100%';
            mainHeader.style.left = '0';
            mainHeader.style.width = '100%';
        } else {
            // Desktop layout
            sidebar.style.left = '0';
            sidebar.style.width = '280px';
            mainContent.style.marginLeft = '280px';
            mainContent.style.width = 'calc(100% - 280px)';
            mainHeader.style.left = '280px';
            mainHeader.style.width = 'calc(100% - 280px)';
        }
    }

    // Setup full screen support
    function setupFullScreenSupport() {
        // Full screen API support
        const fullScreenBtn = document.getElementById('fullscreen-btn');
        if (fullScreenBtn) {
            fullScreenBtn.addEventListener('click', toggleFullScreen);
        }

        // Auto-detect full screen changes
        document.addEventListener('fullscreenchange', handleFullScreenChange);
        document.addEventListener('webkitfullscreenchange', handleFullScreenChange);
        document.addEventListener('mozfullscreenchange', handleFullScreenChange);
        document.addEventListener('MSFullscreenChange', handleFullScreenChange);
    }

    // Toggle full screen
    function toggleFullScreen() {
        if (!document.fullscreenElement && 
            !document.mozFullScreenElement && 
            !document.webkitFullscreenElement && 
            !document.msFullscreenElement) {
            // Enter full screen
            const elem = document.documentElement;
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
            } else if (elem.mozRequestFullScreen) {
                elem.mozRequestFullScreen();
            } else if (elem.webkitRequestFullscreen) {
                elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
            }
        } else {
            // Exit full screen
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            }
        }
    }

    // Handle full screen changes
    function handleFullScreenChange() {
        const isFullScreen = !!(document.fullscreenElement || 
                               document.mozFullScreenElement || 
                               document.webkitFullscreenElement || 
                               document.msFullscreenElement);
        
        document.body.classList.toggle('fullscreen-mode', isFullScreen);
        
        if (isFullScreen) {
            // Adjust layout for full screen
            handleResponsiveLayout();
        }
    }

    // Setup touch support for mobile
    function setupTouchSupport() {
        if (!responsiveState.isMobile) return;

        // Touch gestures for sidebar
        let touchStartX = 0;
        let touchEndX = 0;
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.overlay');

        document.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        });

        document.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipeGesture();
        });

        function handleSwipeGesture() {
            const swipeThreshold = 50;
            const swipeDistance = touchEndX - touchStartX;

            if (Math.abs(swipeDistance) > swipeThreshold) {
                if (swipeDistance > 0 && touchStartX < 50) {
                    // Swipe right from left edge - open sidebar
                    openSidebar();
                } else if (swipeDistance < 0 && sidebar.classList.contains('show')) {
                    // Swipe left when sidebar is open - close sidebar
                    closeSidebar();
                }
            }
        }
    }

    // Setup keyboard support
    function setupKeyboardSupport() {
        document.addEventListener('keydown', function(e) {
            // Escape key to close sidebar on mobile/tablet
            if (e.key === 'Escape') {
                if (responsiveState.isMobile || responsiveState.isTablet) {
                    closeSidebar();
                }
            }

            // Ctrl/Cmd + F for full screen
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                e.preventDefault();
                toggleFullScreen();
            }

            // Ctrl/Cmd + S for sidebar toggle
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                toggleSidebar();
            }
        });
    }

    // Sidebar management functions
    function openSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.overlay');
        
        if (sidebar) {
            sidebar.classList.add('show');
            if (overlay) {
                overlay.classList.add('show');
            }
            document.body.style.overflow = 'hidden';
        }
    }

    function closeSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.overlay');
        
        if (sidebar) {
            sidebar.classList.remove('show');
            if (overlay) {
                overlay.classList.remove('show');
            }
            document.body.style.overflow = '';
        }
    }

    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        if (sidebar && sidebar.classList.contains('show')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    }

    // Setup touch events for mobile
    function setupTouchEvents() {
        // Double tap to zoom prevention
        let lastTouchEnd = 0;
        document.addEventListener('touchend', function(event) {
            const now = (new Date()).getTime();
            if (now - lastTouchEnd <= 300) {
                event.preventDefault();
            }
            lastTouchEnd = now;
        }, false);

        // Prevent zoom on double tap
        document.addEventListener('gesturestart', function(e) {
            e.preventDefault();
        });

        document.addEventListener('gesturechange', function(e) {
            e.preventDefault();
        });

        document.addEventListener('gestureend', function(e) {
            e.preventDefault();
        });
    }

    // Setup keyboard events
    function setupKeyboardEvents() {
        // Focus management for accessibility
        const focusableElements = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                const focusableContent = document.querySelectorAll(focusableElements);
                const firstFocusableElement = focusableContent[0];
                const lastFocusableElement = focusableContent[focusableContent.length - 1];

                if (e.shiftKey) {
                    if (document.activeElement === firstFocusableElement) {
                        lastFocusableElement.focus();
                        e.preventDefault();
                    }
                } else {
                    if (document.activeElement === lastFocusableElement) {
                        firstFocusableElement.focus();
                        e.preventDefault();
                    }
                }
            }
        });
    }

    // Performance optimization for mobile
    function optimizeForMobile() {
        if (responsiveState.isMobile) {
            // Reduce animations on mobile for better performance
            document.body.style.setProperty('--transition-speed', '0.2s');
            
            // Optimize images for mobile
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                if (img.dataset.mobileSrc) {
                    img.src = img.dataset.mobileSrc;
                }
            });
        }
    }

    // Handle viewport height issues on mobile browsers
    function fixViewportHeight() {
        const vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
        
        // Update on resize
        window.addEventListener('resize', function() {
            const vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        });
    }

    // Initialize everything
    initializeResponsive();
    fixViewportHeight();
    optimizeForMobile();

    // Debug information (remove in production)
    console.log('Responsive system initialized:', responsiveState);
});

// Export functions for global access
window.ResponsiveUtils = {
    toggleFullScreen: function() {
        const fullScreenBtn = document.getElementById('fullscreen-btn');
        if (fullScreenBtn) {
            fullScreenBtn.click();
        }
    },
    
    toggleSidebar: function() {
        const toggleBtn = document.querySelector('.toggle-sidebar');
        if (toggleBtn) {
            toggleBtn.click();
        }
    },
    
    getResponsiveState: function() {
        return window.responsiveState || {};
    }
};
