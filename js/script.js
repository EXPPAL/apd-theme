var mm = jQuery.noConflict();
var ww = document.body.clientWidth;

mm(document).ready(function () {
    mm(".nav1 li a").each(function () {
        if (mm(this).next().length > 0) {
            mm(this).addClass("parent");
        }
        ;
    })

    mm(".toggleMenu").click(function (e) {
        e.preventDefault();
        mm(this).toggleClass("active");
        mm(".nav1").toggle();
    });
    adjustMenu();
  
  mm("body.page-template-default .action-holder button.single_add_to_cart_button, body.archive .action-holder button.add-to-cart").click(function(){
    	mm(this).hide();
        mm(this).nextAll("img.loading-cart").show();  // show loader gif
  });
  
  mm(".mp-deal-btns-wrapper a.mp-deal-btn-add-to-cart").click(function(){
  	mm(this).find('img.alignnone').hide();
    mm(this).find('img.alignnone').next().show(); // show loader gif
  })

mm(window).bind('resize orientationchange', function () {
    ww = document.body.clientWidth;
    adjustMenu();
});

var adjustMenu = function () {
    if (ww < 768) {
        mm(".toggleMenu").css("display", "inline-block");
        if (!mm(".toggleMenu").hasClass("active")) {
            mm(".nav1").hide();
        } else {
            mm(".nav1").show();
        }
        mm(".nav1 li").unbind('mouseenter mouseleave');
        mm(".nav1 li a.parent").unbind('click').bind('click', function (e) {
            // must be attached to anchor element to prevent bubbling
            e.preventDefault();
            mm(this).parent("li").toggleClass("hover");
        });
    }
    else if (ww >= 768) {
        mm(".toggleMenu").css("display", "none");
        mm(".nav1").show();
        mm(".nav1 li").removeClass("hover");
        mm(".nav1 li a").unbind('click');
        mm(".nav1 li").unbind('mouseenter mouseleave').bind('mouseenter mouseleave', function () {
            // must be attached to li so that mouseleave is not triggered when hover over submenu
            mm(this).toggleClass('hover');
        });
    }
}

mm(document).ready(function () {
    if (mm('#deal-info-popup').length) {
        mm('#deal-info-popup').append('<button>?</button>');
        mm('#deal-info-popup button').hover(function() {
            mm('#deal-info-popup .info-popup').show();
        }, function () {
            mm('#deal-info-popup .info-popup').hide();
        });
    }
});

