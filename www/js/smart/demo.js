if($.fn.datepicker){
    $.datepicker.setDefaults({
        prevText: '<i class="fa fa-chevron-left"></i>',
        nextText: '<i class="fa fa-chevron-right"></i>',
        beforeShow: function(input, dp){
            setTimeout(function(){
                dp.dpDiv.find('.ui-datepicker-prev').attr('title', 'Prev');
                dp.dpDiv.find('.ui-datepicker-next').attr('title', 'Next');
            }, 100);
        },
        onChangeMonthYear: function(year, month, dp){
            setTimeout(function(){
                dp.dpDiv.find('.ui-datepicker-prev').attr('title', 'Prev');
                dp.dpDiv.find('.ui-datepicker-next').attr('title', 'Next');
            }, 100);
        }
    });
}


//duong: hack for datetimepicker
if($.fn.timepicker){
    $.timepicker.setDefaults({
        prevText: '<i class="fa fa-chevron-left"></i>',
        nextText: '<i class="fa fa-chevron-right"></i>',
        evnts: {
            beforeShow: function(input, dp){
                setTimeout(function(){
                    dp.dpDiv.find('.ui-datepicker-prev').attr('title', 'Prev');
                    dp.dpDiv.find('.ui-datepicker-next').attr('title', 'Next');
                }, 100);
            },
            onChangeMonthYear: function(year, month, dp){
                setTimeout(function(){
                    dp.dpDiv.find('.ui-datepicker-prev').attr('title', 'Prev');
                    dp.dpDiv.find('.ui-datepicker-next').attr('title', 'Next');
                }, 100);
            }
        }
    });
}


$('#ribbon').append('<div class="demo">' +
    '<span id="demo-setting"><i class="fa fa-cog txt-color-blueDark"></i></span> ' +
    '<form>' +
        '<legend class="no-padding margin-bottom-10">Skins</legend>' +
        '<section id="smart-styles">' +
            '<a href="javascript:void(0);" id="smart-style-1" data-skinlogo="/images/profile/logo.png" class="btn btn-block btn-xs txt-color-white" style="background:#3A4558;">' + ($(document.body).hasClass('smart-style-1') ? '<i class="fa fa-check fa-fw" id="skin-checked"></i> ' : '') + 'Dark Elegance</a>' +
            '<a href="javascript:void(0);" id="smart-style-2" data-skinlogo="/images/profile/logo.png" class="btn btn-xs btn-block txt-color-darken margin-top-5" style="background:#fff;">' + ($(document.body).hasClass('smart-style-2') ? '<i class="fa fa-check fa-fw" id="skin-checked"></i> ' : '') + 'Ultra Light</a>' +
        '</section>' +
    '</form>' +
'</div>');

// hide bg options
var smartbgimage =
"<h6 class='margin-top-10 semi-bold'>Background</h6><img src='/images/smart/pattern/graphy-xs.png' data-htmlbg-url='/images/smart/pattern/graphy.png' width='22' height='22' class='margin-right-5 bordered cursor-pointer'><img src='/images/smart/pattern/tileable_wood_texture-xs.png' width='22' height='22' data-htmlbg-url='/images/smart/pattern/tileable_wood_texture.png' class='margin-right-5 bordered cursor-pointer'><img src='/images/smart/pattern/sneaker_mesh_fabric-xs.png' width='22' height='22' data-htmlbg-url='/images/smart/pattern/sneaker_mesh_fabric.png' class='margin-right-5 bordered cursor-pointer'><img src='/images/smart/pattern/nistri-xs.png' data-htmlbg-url='/images/smart/pattern/nistri.png' width='22' height='22' class='margin-right-5 bordered cursor-pointer'><img src='/images/smart/pattern/paper-xs.png' data-htmlbg-url='/images/smart/pattern/paper.png' width='22' height='22' class='bordered cursor-pointer'>";
$("#smart-bgimages")
.fadeOut();

$('#demo-setting')
.click(function () {
        //console.log('setting');
        $('#ribbon .demo')
        .toggleClass('activate');
    })

/*
 * FIXED HEADER
 */
 $('input[type="checkbox"]#smart-fixed-nav')
 .click(function () {
    if ($(this)
        .is(':checked')) {
            //checked
        $('body').addClass("fixed-header");
        nav_page_height();
    } else {
            //unchecked
            $('input[type="checkbox"]#smart-fixed-ribbon')
            .prop('checked', false);
            $('input[type="checkbox"]#smart-fixed-navigation')
            .prop('checked', false);

            $('body').removeClass("fixed-header");
            $('body').removeClass("fixed-navigation");
            $('body').removeClass("fixed-ribbon");

        }
    });

/*
 * FIXED RIBBON
 */
 $('input[type="checkbox"]#smart-fixed-ribbon')
 .click(function () {
    if ($(this)
        .is(':checked')) {
            //checked
        $('input[type="checkbox"]#smart-fixed-nav')
        .prop('checked', true);

        $('body').addClass("fixed-header");
        $('body').addClass("fixed-ribbon");

        $('input[type="checkbox"]#smart-fixed-container')
        .prop('checked', false);
        $('body').removeClass("container");

    } else {
            //unchecked
            $('input[type="checkbox"]#smart-fixed-navigation')
            .prop('checked', false);
            $('body').removeClass("fixed-ribbon");
            $('body').removeClass("fixed-navigation");
        }
    });


/*
 * FIXED NAV
 */
 $('input[type="checkbox"]#smart-fixed-navigation')
 .click(function () {
    if ($(this)
        .is(':checked')) {

            //checked
        $('input[type="checkbox"]#smart-fixed-nav')
        .prop('checked', true);
        $('input[type="checkbox"]#smart-fixed-ribbon')
        .prop('checked', true);

            //apply
            $('body').addClass("fixed-header");
            $('body').addClass("fixed-ribbon");
            $('body').addClass("fixed-navigation");

            $('input[type="checkbox"]#smart-fixed-container')
            .prop('checked', false);
            $('body').removeClass("container");

        } else {
            //unchecked
            $('body').removeClass("fixed-navigation");
        }
    });

/*
 * RTL SUPPORT
 */
 $('input[type="checkbox"]#smart-rtl')
 .click(function () {
    if ($(this)
        .is(':checked')) {

            //checked
        $('body').addClass("smart-rtl");

    } else {
            //unchecked
            $('body').removeClass("smart-rtl");
        }
    });


/*
 * INSIDE CONTAINER
 */
 $('input[type="checkbox"]#smart-fixed-container')
 .click(function () {
    if ($(this)
        .is(':checked')) {
            //checked
        $('body').addClass("container");

        $('input[type="checkbox"]#smart-fixed-ribbon')
        .prop('checked', false);
        $('body').removeClass("fixed-ribbon");

        $('input[type="checkbox"]#smart-fixed-navigation')
        .prop('checked', false);
        $('body').removeClass("fixed-navigation");

        if (smartbgimage) {
            $("#smart-bgimages")
            .append(smartbgimage)
            .fadeIn(1000);
            $("#smart-bgimages img")
            .bind("click", function () {
                var $this = $(this);
                var $html = $('html')
                bgurl = ($this.data("htmlbg-url"));
                $html.css("background-image", "url(" +
                    bgurl + ")");
            })

            smartbgimage = null;
        } else {
            $("#smart-bgimages")
            .fadeIn(1000);
        }


    } else {
            //unchecked
            $('body').removeClass("container");
            $("#smart-bgimages")
            .fadeOut();
            // console.log("container off");
        }
    });

/*
 * REFRESH WIDGET
 */
 $("#reset-smart-widget")
 .bind("click", function () {
    $('#refresh')
    .click();
    return false;
});

/*
 * STYLES
 */
 $("#smart-styles > a")
 .bind("click", function () {
    var $this = $(this);
    var $logo = $("#logo img");
    $('body').removeClassPrefix('smart-style').addClass($this.attr("id"));

    $.cookie('sm_style', $this.attr('id'), {path: '/'});

    $logo.attr('src', $this.data("skinlogo"));
    $("#smart-styles > a #skin-checked").remove();
    $this.prepend("<i class='fa fa-check fa-fw' id='skin-checked'></i>");
});
