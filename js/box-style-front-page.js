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
            position: '',
            hover: ''
        }

        days = days.toString();

        switch(days) {
            case '0':
                set.color = '#fa1e00';
                set.position = '76px';
                set.hover = 'rgba(250, 30, 0, 0.5)';
                break;
            case '1':
                set.color = '#fa2d06';
                set.position = '110px';
                set.hover = 'rgba(250, 45, 6, 0.5)';
                break;
            case '2':
                set.color = '#fa3c0d';
                set.position = '146px';
                set.hover = 'rgba(250, 60, 13, 0.5)';
                break;
            case '3':
                set.color = '#fb4b14';
                set.position = '182px';
                set.hover = 'rgba(251, 75, 20, 0.5)';
                break;
            case '4':
                set.color = '#fb5a1a';
                set.position = '216px';
                set.hover = 'rgba(251, 90, 26, 0.5)';
                break;
            case '5':
                set.color = '#fb6921';
                set.position = '246px';
                set.hover = 'rgba(251, 105, 33, 0.5)';
                break;
            case '6':
                set.color = '#fc7828';
                set.position = '280px';
                set.hover = 'rgba(252, 120, 40, 0.5)';
                break;
            case '7':
                set.color = '#fc872e';
                set.position = '312px';
                set.hover = 'rgba(252, 135, 46, 0.5)';
                break;
            case '8':
                set.color = '#fd9635';
                set.position = '343px';
                set.hover = 'rgba(253, 150, 53, 0.5)';
                break;
            case '9':
                set.color = '#fda53c';
                set.position = '376px';
                set.hover = 'rgba(253, 165, 60, 0.5)';
                break;
            case '10':
                set.color = '#fdb442';
                set.position = '410px';
                set.hover = 'rgba(253, 180, 76, 0.5)';
                break;
            case '11':
                set.color = '#fec349';
                set.position = '444px';
                set.hover = 'rgba(254, 154, 73, 0.5)';
                break;
            case '12':
                set.color = '#fed250';
                set.position = '474px';
                set.hover = 'rgba(254, 210, 80, 0.5)';
                break;
            case '13':
                set.color = '#ffe157';
                set.position = '500px';
                set.hover = 'rgba(255, 225, 87, 0.5)';
                break;
            default:
                set.color = '#ffe157';
                set.position = '500px';
                set.hover = 'rgba(255, 225, 87, 0.5)';
                break;
        }

        return set;
    }

    var bd_day = $('#big-deal-day-end').val();

    if (bd_day !== '') {
        bd_day = new Date(bd_day);

        var days = getDifference(bd_day);

        $('#big-deal-counter').text(14 - days);

        var style = getPointerStyles(days);

        $('#big-deal-pointer').css({'background-color': style.color, 'top': style.position});
        $('#big-deal-pointer').closest('.box').css({'border-color': style.color});
        $('head').append('<style>.deal-box-holder .box.big-deal-box:hover .overlay {background: radial-gradient(rgba(255, 255, 255, 0.5), ' + style.hover + ');} </style>');
    }

    var d_day = $('#deal-day-end').val();

    if (d_day !== '') {
        d_day = new Date(d_day);

        var days = getDifference(d_day);

        $('#deal-counter').text(14 - days);

        var style = getPointerStyles(days);

        $('#deal-pointer').css({'background-color': style.color, 'top': style.position});
        $('#deal-pointer').closest('.box').css({'border-color': style.color});
        $('head').append('<style>.deal-box-holder .box.deal-box:hover .overlay {background: radial-gradient(rgba(255, 255, 255, 0.5), ' + style.hover + ');} </style>');
    }
});
