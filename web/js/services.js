$(document).ready(function() {

    // To support non-js visitors, hide service details if JavaScript is enabled
    $('.service-name .caret').addClass('fa-angle-right').removeClass('fa-angle-down');
    $('.service-details').addClass('hidden');

    $('.service-name').click(function() {
        $('.service-name .caret', $(this).parent()).toggleClass('fa-angle-right').toggleClass('fa-angle-down');
        $('.service-details', $(this).parent()).toggleClass('hidden');
    });
});
