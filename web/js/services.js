$(document).ready(function() {
    $('.services-table-row-expander').click(function() {
        $('.services-table-caret', $(this).parent()).toggleClass('fa-angle-right').toggleClass('fa-angle-down');
        $('.service-table-row-content', $(this).parent()).toggleClass('hidden');
    });
});
