/**
 * Sponsors Rotating JavaScript
 * Handles automatic rotation of sponsor rows
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        $('.vtc-sponsors-rotating').each(function() {
            var $container = $(this);
            var $rows = $container.find('.vtc-sponsors-row');
            var interval = parseInt($container.data('interval')) || 5000;
            var currentIndex = 0;
            var totalRows = $rows.length;
            
            // Only rotate if there are multiple rows
            if (totalRows <= 1) {
                return;
            }
            
            // Function to show specific row
            function showRow(index) {
                $rows.removeClass('active');
                $rows.eq(index).addClass('active');
            }
            
            // Auto-rotate
            setInterval(function() {
                currentIndex = (currentIndex + 1) % totalRows;
                showRow(currentIndex);
            }, interval);
        });
    });
    
})(jQuery);
