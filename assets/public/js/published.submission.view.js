$(document).ready(function() {

var $grid = $('#content').isotope({
    itemSelector: '.element-item',
    layoutMode: 'vertical'
});

$('input:checkbox').on('click', function() {
    var searchArr = [];
    var searchString;
    $('input:checked').each(function() {
        searchArr.push($(this).val());
    });

    searchString = searchArr.join('');
    $grid.isotope({filter: searchString});
});

});