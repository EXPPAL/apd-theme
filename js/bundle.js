'use strict';



jQuery(document).ready(function ($) {

    var jqxhr = $.getJSON( "https://audioplugin.deals/wp-content/themes/apd/app.config.json", function() {
        console.log( "success" );
      })
        .done(function(data) {
            
            console.log(data.testlog.message);
            console.log(data.banner.rotating_time);
    
            starBaner(data.banner);
        })
        .fail(function(e) {
          console.log( "error" + e );

          // starBaner(data.banner);
        })
        .always(function() {
          console.log( "complete" );
        });
        
	

	var $carusels = $('.shop-carusel');
    var scope = [];

    $carusels.each(function (i) {
        scope.push({
            id: i,
            lineWidth: $(this).find('ul').width(),
            itemLength: $(this).find('.item-box').length,
            count: 0
        });
    
        $(this).attr('data-carusel-id', i);
    });
    
        var count = 0;
        var x = void 0;
        var id = void 0;
        var CAPACITY = isRewardsPage() ? Math.floor($('.shop-carusel .viewport').width() / 123) : Math.floor($('.shop-carusel .viewport').width() / 310);
        var INFINITE_ROTATE = true;
        var itemWidth = isRewardsPage() ? 132 : 326;
    
        function activyti(currentCarusel) {
    
            if (currentCarusel.count == 0) {
                $('.shop-carusel[data-carusel-id=' + currentCarusel.id + '] .arrow-left').css({ 'opacity': '0.3' });
            } else {
                $('.shop-carusel[data-carusel-id=' + currentCarusel.id + '] .arrow-left').css({ 'opacity': '1' });
            }
    
            if (currentCarusel.count >= currentCarusel.itemLength - CAPACITY) {
                $('.shop-carusel[data-carusel-id=' + currentCarusel.id + '] .arrow-right').css({ 'opacity': '0.3' });
            } else {
                $('.shop-carusel[data-carusel-id=' + currentCarusel.id + '] .arrow-right').css({ 'opacity': '1' });
            }
        }
    
        function getCaruselID(elem) {
            return $(elem).closest('.shop-carusel').data('carusel-id');
        }
    
        function isRewardsPage() {
            if ($('.Rewards').length) {
                if ($('.shop-carusel li').length <= 7) {
                    $('.shop-carusel .arrow-right').css({ 'opacity': '0' }).off('click');
                }
                return true;
            }
    
            return false;
        }
    
        $('.arrow-right').click(function () {
            id = getCaruselID(this);
            count = scope[id].count;
    
            if (scope[id].itemLength - count >= CAPACITY) {
                x = count * -itemWidth - itemWidth;
                $('.shop-carusel[data-carusel-id=' + id + '] ul').css({ "transform": "translateX(" + x + "px)" });
                scope[id].count++;
    
                // Infinite rotate - Clone items
                // console.log()
                if (INFINITE_ROTATE && scope[id].itemLength - count - CAPACITY <= 1) {
                    // console.log('time to clone!!!');
                    $('.shop-carusel[data-carusel-id=' + id + '] ul li').clone().appendTo($('.shop-carusel[data-carusel-id=' + id + '] ul'));
    
                    // Update carusel items length
                    scope[id].itemLength = $('.shop-carusel[data-carusel-id=' + id + '] ul .item-box').length;
                } else {
                    activyti(scope[id]);
                }
            }
        });
    
        $('.arrow-left').click(function () {
            id = getCaruselID(this);
            count = scope[id].count;
    
            if (count > 0) {
                x = count * -itemWidth + itemWidth;
                $('.shop-carusel[data-carusel-id=' + id + '] ul').css({ "transform": "translateX(" + x + "px)" });
                scope[id].count--;
    
                activyti(scope[id]);
            }
        });

	if ($('.Rewards').length) {
		if ($('.shop-carusel li').length <= 7) {
			$('.shop-carusel .arrow-right').css({ 'opacity': '0' }).off('click');
		}
	}

    /**
     * Component defined drop-down behavior for "BROWSE" button on Subhero ribbon;
     * 
     * @2018 Danilchenko Viktor
     */

    var $menuBtn = $('button[data-action=open-filter]'); // Controll button
    var $filterMenuDropdown = $('.filter-menu-dropdown'); // Menu wrapper
    var $mobileCloseBtn = $('.filter-menu-dropdown button.btn-close'); // Mobile Close button
    var $body = $('body');


    /** 
     * Add hover action to the "BROWSE" button.
     */
    $menuBtn.on('mouseenter', function () {
        if (!$filterMenuDropdown.hasClass('open')) {
            $filterMenuDropdown.addClass('open');

            $menuBtn.on('mouseleave', function () {
                setTimeout(function () {
                    if (!$filterMenuDropdown.hasClass('active')) {
                        $filterMenuDropdown.removeClass('open');
                    }
                }, 500);
            });

            $filterMenuDropdown.mouseenter(function () {
                $filterMenuDropdown.addClass('active');
            });

            $filterMenuDropdown.mouseleave(function () {
                $filterMenuDropdown.removeClass('active').removeClass('open');
            });
        }
    });

    /** 
     * Romove hove behavior for mobile devices.
     * Add click behaviors.
     */
    if (window.matchMedia('(max-device-width: 1100px)').matches) {
        $menuBtn.off('mouseenter');
        $menuBtn.off('mouseleave');
        $filterMenuDropdown.off('mouseenter');
        $filterMenuDropdown.off('mouseleave');
    
        $menuBtn.click(function () {
            if ($filterMenuDropdown.hasClass('open')) {
                $filterMenuDropdown.removeClass('open');
                $body.removeClass('noscroll');
            } else {
                $filterMenuDropdown.addClass('open');
                $body.addClass('noscroll');
            }
        });
    
        $mobileCloseBtn.click(function () {
            $filterMenuDropdown.removeClass('open');
            $body.removeClass('noscroll');
        });
    }

    var $helpPopup = '<div class=help-popup><span>After placing your order, this amount will be awarded to your rewards wallet in your account, which you can then use towards any product within The Shop</span></div>';

    if ($('#help-popup').length) {
        if (window.matchMedia('(max-width: 615px)').matches) {
            $('#help-popup').addClass('mobile');
            let popupBtn = $('#help-popup').detach();
            $('.for-help-button').append(popupBtn);
        }
        $('#help-popup')
            .mouseenter(function() {
                $('#help-popup').parent().append($helpPopup);
        })
            .mouseleave(function() {
                $('.help-popup').remove();
            });
    }

    var $infoIcons = $('.rewards-holder .info .icon');

    $infoIcons.each(function () {
        $(this).hover(function () {
            $(this).closest('.rewards-holder').find('.popup-help-info').show(200);
        }, function () {
            $(this).closest('.rewards-holder').find('.popup-help-info').hide(200);
        });
    });

    $('.rewards-holder button').click(function () {
        $(this).closest('.rewards-holder').find('.popup-reward-input').toggleClass('open');
        $(this).closest('.use_reward').trigger('click');
    });

    $('.shop-carusel li').click(function (event) {
        if (!$('.Rewards').length) {
            event.preventDefault();
        }
        if ($(this).find('.product-details-popup').length) {

            var position = {
                top: isMobile() ? '90px' : 'calc(50% - 250px)',
                left: isMobile() ? '0' : 'calc(50% - 207px)',
                right: isMobile() ? '0' : 'auto'
            };

            $(this).find('.product-details-popup').clone().appendTo('.product-overlay');

            $('.product-overlay').css({
                'display': 'block',
                'position': 'fixed',
                'top': 0,
                'left': 0,
                'right': 0,
                'bottom': 0,
                'overflow-y': 'auto'
            }).addClass('active');

            $('.product-overlay .product-details-popup').css({
                'display': 'block',
                'top': position.top,
                'left': position.left,
                'right': position.right
            });

            $('body').addClass('noscroll');
        }
    });

    $('.product-overlay').click(function (e) {
        if ($(this).hasClass('active')) {
            $(this).find('.product-details-popup').remove();
            $(this).css({
                'display': 'none'
            });

            $('body').removeClass('noscroll');
        }
    });

    var isMobile = function isMobile() {
        return $(window).width() < 450;
    };

    var $valletValue = $('.hero-rewards .wallet-info-holder .content span.value');

    if ($valletValue.length) {
        if ($valletValue.text().length >= 7) {
            $valletValue.css({
                'font-size': '34px'
            });
        }
    }

    $('.button-back-to-top').click(function () {
        $('html, body').animate({
            scrollTop: 0
        }, 1200);
    });

    $('#mobile-search-btn').click(function () {
        $('.search-holder').addClass('open');
    });
    
    $('.search-holder .close-btn').click(function () {
        $('.search-holder').removeClass('open');
    });

    if ($('.subhero-ribbon').length) {
        var ribbonPosition;

        $(window).scroll(function () {
            var position = $('.subhero-ribbon').position();
            if (position.top <= $(window).scrollTop() && !$('.subhero-ribbon').hasClass('sticky')) {
                $('.subhero-ribbon').addClass('sticky');
                ribbonPosition = position.top;
            }

            if (ribbonPosition >= $(window).scrollTop() && $('.subhero-ribbon').hasClass('sticky')) {
                $('.subhero-ribbon').removeClass('sticky');
            }
        });
    }

    if ($('.header2.sticky-navigation').length) {
        var ribbonPosition;

        $(window).scroll(function () {
            var position = $('.header2.sticky-navigation').position();
            if (position.top <= $(window).scrollTop() && !$('.header2.sticky-navigation').hasClass('stuck')) {
                $('.header2.sticky-navigation').addClass('stuck');
                ribbonPosition = position.top;
            }

            if (ribbonPosition >= $(window).scrollTop() && $('.header2.sticky-navigation').hasClass('stuck')) {
                $('.header2.sticky-navigation').removeClass('stuck');
            }
        });
    }

    if ($('.video-popup').length) {
        var src_short = $('.video-popup .viewport.short-video iframe').attr('src');
        var src_long = $('.video-popup .viewport.long-video iframe').attr('src');
        $('.btn-open-video').click(function () {
            $('.video-popup').addClass('active');
        });
    
        $('.video-popup .btn-close, .video-popup').click(function () {
            $('.video-popup').removeClass('active');
            $('.video-popup .viewport').css({ 'display': 'none' });
            $('.video-popup .action-holder').css({ 'display': 'block' });
            $('.video-popup .viewport.long-video iframe').attr('src','');
            $('.video-popup .viewport.short-video iframe').attr('src','');
        });
    
        $('.video-popup .action-holder button').click(function (event) {
            event.stopPropagation();
            if ($(event.target).data('action') == "open-short-video") {
                $('.video-popup .viewport.long-video iframe').attr('src','');
                $('.video-popup .viewport.short-video iframe').attr('src',src_short);
                $('.video-popup .action-holder').css({ 'display': 'none' });
                $('.video-popup .viewport.short-video').css({ 'display': 'block' });
            }
    
            if ($(event.target).data('action') == "open-long-video") {
                $('.video-popup .viewport.long-video iframe').attr('src',src_long);
                $('.video-popup .viewport.short-video iframe').attr('src','');
                $('.video-popup .action-holder').css({ 'display': 'none' });
                $('.video-popup .viewport.long-video').css({ 'display': 'block' });
            }
        });
    }

    function starBaner (_BANNER_SETTINGS) {
        if ($('.c-hero-carusel').length && window.matchMedia("(min-device-width: 767px)").matches) {
            
            if (_BANNER_SETTINGS == undefined) {
                _BANNER_SETTINGS = {
                    "rotating_time": 5000,
                    "schedule": {
                        "isActive" : false
                    }
                }
            }

            const $HERO_CARUSEL = $('.c-hero-carusel');
            const $HERO_CARUSEL_VIEWPORT = $('.c-hero-carusel ul');

            if (_BANNER_SETTINGS.schedule.isActive !== undefined &&  _BANNER_SETTINGS.schedule.isActive) {
                var currentDate = new Date();

                var _start = new Date(_BANNER_SETTINGS.schedule.time_start);
                var _end = new Date(_BANNER_SETTINGS.schedule.time_end);

                if (_start <= currentDate && currentDate <= _end) {
                    $HERO_CARUSEL_VIEWPORT.find('li').remove();
                    $HERO_CARUSEL_VIEWPORT.append('<li><img src=' + _BANNER_SETTINGS.schedule.banner_src + '></li>');
                    return;
                }
            }

            if (_BANNER_SETTINGS.schedule_2.isActive !== undefined &&  _BANNER_SETTINGS.schedule_2.isActive) {
                var currentDate = new Date();

                var _start = new Date(_BANNER_SETTINGS.schedule_2.time_start);
                var _end = new Date(_BANNER_SETTINGS.schedule_2.time_end);

                if (_start <= currentDate && currentDate <= _end) {
                    $HERO_CARUSEL_VIEWPORT.find('li').remove();
                    $HERO_CARUSEL_VIEWPORT.append('<li><img src=' + _BANNER_SETTINGS.schedule_2.banner_src + '></li>');
                    return;
                }
            }
        
            let heroCarusel = {
                items: $('.c-hero-carusel li').length,
                count: 1,
                width: $HERO_CARUSEL.width()
            }

            let controlInit = function () { 
                if ($('.btn-bullet.active').length) {
                    $('.btn-bullet.active').removeClass('active');
                }
                let firstControl = $('.c-hero-carusel .control-holder .btn-bullet')[0];
                $(firstControl).addClass('active');
            }
            
            if (heroCarusel.items > 1) {
                for (let i = 0; i < heroCarusel.items; i++) {
                    $HERO_CARUSEL.find('.control-holder').append('<span class="btn-bullet" data-control-id=' + i + '>');
                }

                controlInit();
            }
        
            $('.c-hero-carusel .control-holder .btn-bullet').click(function () {
                console.log($(this).data('control-id'));
        
                heroCarusel.count = $(this).data('control-id');
        
                let px = heroCarusel.width * heroCarusel.count;
                $HERO_CARUSEL_VIEWPORT.css({'transform': 'translateX(' + -px + 'px)' });
                heroCarusel.count++;
        
                $('.btn-bullet.active').removeClass('active');
                $(this).addClass('active');
            });
            var slideHover = false;
	    $('.c-hero-carusel li').each(function( index, element ){
		$(element).hover(function(){slideHover = true}, function(){slideHover=false;});
	    });
        
            
            setInterval(function() {
		if (!slideHover) {
                if (heroCarusel.count < heroCarusel.items) {
                    let px = heroCarusel.width * heroCarusel.count;
                    $HERO_CARUSEL_VIEWPORT.css({'transform': 'translateX(' + -px + 'px)' });
                    heroCarusel.count++;
    
                    $('.btn-bullet.active').removeClass('active').next().addClass('active');
                } else {
                    $HERO_CARUSEL_VIEWPORT.css({'transform': 'translateX(0px)' });
                    heroCarusel.count = 1;
                    controlInit();
                }
		}
            }, _BANNER_SETTINGS.rotating_time);
            
        }
    }
    
});