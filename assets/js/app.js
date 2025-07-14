// Wrap in document ready and use no-conflict safe format
jQuery(document).ready(function($) {
    // Initialize MetisMenu
    $('#menu').metisMenu();
    
    // Mobile menu toggle
    $('.mobile-toggle-menu').on('click', function() {
        $('.sidebar-wrapper').toggleClass('active');
        $('.sidebar-overlay').fadeToggle();
    });
    
    // Close sidebar when clicking overlay
    $('.sidebar-overlay').on('click', function() {
        $('.sidebar-wrapper').removeClass('active');
        $(this).fadeOut();
    });
});