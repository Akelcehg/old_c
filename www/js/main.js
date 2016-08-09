/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function () {
$.left_panel = $('#left-panel');
function nav_page_height() {
    var setHeight = $('#main').height();
    menuHeight = $.left_panel.height();

    var windowHeight = $(window).height() - $.navbar_height;
    //set height

    if (setHeight > windowHeight) { // if content height exceedes actual window height and menuHeight
        $.left_panel.css('min-height', setHeight + 'px');
        $.root_.css('min-height', setHeight + $.navbar_height + 'px');

    } else {
       $.left_panel.css('min-height', windowHeight + 'px');
        $.root_.css('min-height', windowHeight + 'px');
    }
}

$('#main').resize(function() {
    nav_page_height();
    check_if_mobile_width();
})

$('#main').ready(function() {
    nav_page_height();
    check_if_mobile_width();
})

$('nav').resize(function() {
    nav_page_height();
})

    var url = document.location.toString();
    if (url.match('#')) {
        $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
    }

// Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    })


});


