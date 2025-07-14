// Wrap in document ready and use no-conflict safe format
jQuery(document).ready(function($) {
    // Initialize MetisMenu
    $('#menu').metisMenu();
    
    // Mobile menu toggle
    $('.mobile-toggle-menu').on('click', function() {
        $('.sidebar-wrapper').toggleClass('active');
        $('.sidebar-overlay').fadeToggle();
        
        // Update Perfect Scrollbar when sidebar is toggled
        if ($('.sidebar-wrapper').hasClass('active') && typeof PerfectScrollbar !== 'undefined') {
            ps.update();
        }
    });
    
    // Close sidebar when clicking overlay
    $('.sidebar-overlay').on('click', function() {
        $('.sidebar-wrapper').removeClass('active');
        $(this).fadeOut();
    });
    
    // Initialize Perfect Scrollbar if plugin is loaded
    if (typeof PerfectScrollbar !== 'undefined') {
        var ps = new PerfectScrollbar('.sidebar-wrapper', {
            wheelSpeed: 1,
            wheelPropagation: true,
            minScrollbarLength: 20,
            suppressScrollX: true
        });
        
        // Update scrollbar when menu items expand/collapse
        $(document).on('shown.metisMenu', '#menu', function() {
            ps.update();
        });
        
        $(document).on('hidden.metisMenu', '#menu', function() {
            ps.update();
        });
    }
    
    // Alternative fallback if Perfect Scrollbar not available
    else {
        console.warn('Perfect Scrollbar not loaded - using native scrolling');
        $('.sidebar-wrapper').css('overflow-y', 'auto');
    }
});