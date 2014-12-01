/**
 * @author Christoffer
 */

$('a').click(function(){
    $('html, body').animate({
        scrollTop: $( $.attr(this, 'href') ).offset().top
    }, 500);
    return false;
});

function confirmation(){
    if (confirm("Är du säker?")) {
    // Save it!
    } else {
        // Do nothing!
    }
}
