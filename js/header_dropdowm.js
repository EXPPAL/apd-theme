jQuery(document).ready(function ($) {
    function timeConvetZone(day, zoneUTC) {
        var offset = day.getTimezoneOffset();
        offset += zoneUTC * 60;
        console.log('Zone diff: ' + offset / 60);

        day.setMinutes(day.getMinutes() + offset); 
        console.log('Time with offset:');
        console.log(day);

    }
    function getDifference (day) {
        var today = new Date();
        timeConvetZone(today, -6);
        var result = Math.floor((day - today) / 1000 / 60 / 60 / 24);
        console.log(result);
        return result > 0 ? result : 0;
    }

    function getPointerStyles (days) {
        var set = {
            color: '',
            hover: ''
        }

        days = days.toString();

        switch(days) {
            case '0':
                set.color = '#fa1e00';
                set.hover = 'rgba(250, 30, 0, 0.5)';
                break;
            case '1':
                set.color = '#fa2d06';
                set.hover = 'rgba(250, 45, 6, 0.5)';
                break;
            case '2':
                set.color = '#fa3c0d';
                set.hover = 'rgba(250, 60, 13, 0.5)';
                break;
            case '3':
                set.color = '#fb4b14';
                set.hover = 'rgba(251, 75, 20, 0.5)';
                break;
            case '4':
                set.color = '#fb5a1a';
                set.hover = 'rgba(251, 90, 26, 0.5)';
                break;
            case '5':
                set.color = '#fb6921';
                set.hover = 'rgba(251, 105, 33, 0.5)';
                break;
            case '6':
                set.color = '#fc7828';
                set.hover = 'rgba(252, 120, 40, 0.5)';
                break;
            case '7':
                set.color = '#fc872e';
                set.hover = 'rgba(252, 135, 46, 0.5)';
                break;
            case '8':
                set.color = '#fd9635';
                set.hover = 'rgba(253, 150, 53, 0.5)';
                break;
            case '9':
                set.color = '#fda53c';
                set.hover = 'rgba(253, 165, 60, 0.5)';
                break;
            case '10':
                set.color = '#fdb442';
                set.hover = 'rgba(253, 180, 76, 0.5)';
                break;
            case '11':
                set.color = '#fec349';
                set.hover = 'rgba(254, 154, 73, 0.5)';
                break;
            case '12':
                set.color = '#fed250';
                set.hover = 'rgba(254, 210, 80, 0.5)';
                break;
            case '13':
                set.color = '#ffe157';
                set.hover = 'rgba(255, 225, 87, 0.5)';
                break;
            default:
                set.color = '#ffe157';
                set.hover = 'rgba(255, 225, 87, 0.5)';
                break;
        }

        return set;
    }



    if (BIG_DEAL != undefined) {

        var $BIGDEAL_MENU_ITEM = $('.mainheader .nav1 li')[0];
        var BIGDEAL_LINK_HREF = $($BIGDEAL_MENU_ITEM).find('a').attr('href');

        if (BIG_DEAL.dat_end !== '') {
            var _date = new Date(BIG_DEAL.dat_end);

            var _daydiff = getDifference(_date);

            var _bg_color = getPointerStyles(_daydiff);

            BIG_DEAL._colorBackground = _bg_color.color;

        }

        var newDigdealLink = '<a href=' + BIGDEAL_LINK_HREF + ' class="dropdown-box first">' + 
                                '<div class="wrapper" style="background-color:' + BIG_DEAL._colorBackground + '">' + 
                                    '<span class="title">' + BIG_DEAL.boxTitle +'</span>' +
                                    '<span class="percent_1">' + BIG_DEAL.percent + '</span>' +
                                    '<span class="percent_2">OFF</span>' +
                                    '<img src="' + BIG_DEAL.productIMG + '">' +
                                    '<span class="price">' + BIG_DEAL.price + '</span>' +
                                    '<div class="product-details">' +
                                        '<span class="company">' + BIG_DEAL.comany + '</span>' +
                                        '<span class="product-title">' + BIG_DEAL.product + '</span>' +
                                    '</div>' +
                                    '<div class="counter-holer"></div>' +
                                '</div>' + 
                            '</a>';

        $('.mainheader .nav1').append(newDigdealLink);

        // var counter = $('#big_deal__caounter > div').clone();

        // $('.dropdown-box.first .counter-holer').append(counter);
        
        var bdmenu = $('.mainheader .nav1 li')[0];

        $(bdmenu).mouseenter(function() {
            if (!($('.dropdown-box.first').hasClass('open'))) {
                $('.dropdow-box.second').removeClass('open');
                // $('.dropdown-box.first').css({'display': 'block', 'opacity': '1'});
                $('.dropdown-box.first').addClass('open');
            }
        });

        $('.dropdown-box.first').mouseout(function() {
            if ($('.dropdown-box.first').hasClass('open')) {
                // $('.dropdown-box.first').css({'display': 'none', 'opacity': '0'});
                $('.dropdown-box.first').removeClass('open');
            }
        });
    }

    if (DEAL != undefined) {

        var $DEAL_MENU_ITEM = $('.mainheader .nav1 li')[1];
        var DEAL_LINK_HREF = $($DEAL_MENU_ITEM).find('a').attr('href');

        if (DEAL.dat_end !== '') {
            var _date = new Date(DEAL.dat_end);

            var _daydiff = getDifference(_date);

            var _bg_color = getPointerStyles(_daydiff);

            DEAL._colorBackground = _bg_color.color;

        }

        var newDealLink = '<a href=' + DEAL_LINK_HREF + ' class="dropdown-box second">' + 
                                '<div class="wrapper" style="background-color:' + DEAL._colorBackground + '">' + 
                                    '<span class="title">' + DEAL.boxTitle +'</span>' +
                                    '<span class="percent_1">' + DEAL.percent + '</span>' +
                                    '<span class="percent_2">OFF</span>' +
                                    '<img src="' + DEAL.productIMG + '">' +
                                    '<span class="price">' + DEAL.price + '</span>' +
                                    '<div class="product-details">' +
                                        '<span class="company">' + DEAL.comany + '</span>' +
                                        '<span class="product-title">' + DEAL.product + '</span>' +
                                    '</div>' +
                                    '<div class="counter-holer"></div>' +
                                '</div>' + 
                            '</a>';

        $('.mainheader .nav1').append(newDealLink);

        // var counter = $('#big_deal__caounter > div').clone();

        // $('.dropdown-box.first .counter-holer').append(counter);
        
        var bdmenu2 = $('.mainheader .nav1 li')[1];

        $(bdmenu2).mouseenter(function() {
            if (!($('.dropdown-box.second').hasClass('open'))) {
                // $('.dropdown-box.second').css({'display': 'block'});
                $('.dropdow-box.first').removeClass('open');
                $('.dropdown-box.second').addClass('open');  
            }
        });

        $('.dropdown-box.second').mouseout(function() {
            if ($('.dropdown-box.second').hasClass('open')) {
                // $('.dropdown-box.second').css({'display': 'none', 'opacity': '0'});
                $('.dropdown-box.second').removeClass('open');
            }
        });
    }
});