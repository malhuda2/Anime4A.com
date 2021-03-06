/**
 * Created by Azure Cloud on 10/13/2016.
 */

/*** START HOMEPAGE scripts ***/
$(document).ready(function () {
    // Login actions
    $('#userBox>.overlay').on( "click", function (){
        $('#userBox').fadeOut();
    });

    // START LOAD ANIMES DATA
    // Ajax load animes data
    // Danh sách anime mới cập nhật
    $('#homepage>.titleBar>div>.buttonA').addClass('selected');

    // Danh sách anime xem nhiều
    films_data = getAnimesList($('#sidebar>.most_view>.titleBar>div>.buttonM'), 'MostViewList', 'M');
    $('#sidebar>.most_view>.sidebar_items').html(films_data);
    // END LOAD ANIMES DATA
});

/*** END HOMEPAGE scripts ***/

/*** START FILTER ***/
$(document).ready(function () {
    // Danh sách anime mới cập nhật
    $('#homepage>.titleBar>div>.buttonD').on( "click", function(e) {
        var films_data = getAnimesList($(this), 'NewUpdated', 'D');
        $('#homepage>.list_movies>.items').html(films_data);
    });
    $('#homepage>.titleBar>div>.buttonW').on( "click", function(e) {
        var films_data = getAnimesList($(this), 'NewUpdated', 'W');
        $('#homepage>.list_movies>.items').html(films_data);
    });
    $('#homepage>.titleBar>div>.buttonM').on( "click", function(e) {
        var films_data = getAnimesList($(this), 'NewUpdated', 'M');
        $('#homepage>.list_movies>.items').html(films_data);
    });
    $('#homepage>.titleBar>div>.buttonS').on( "click", function(e) {
        var films_data = getAnimesList($(this), 'NewUpdated', 'S');
        $('#homepage>.list_movies>.items').html(films_data);
    });
    $('#homepage>.titleBar>div>.buttonY').on( "click", function(e) {
        var films_data = getAnimesList($(this), 'NewUpdated', 'Y');
        $('#homepage>.list_movies>.items').html(films_data);
    });
    $('#homepage>.titleBar>div>.buttonA').on( "click", function(e) {
        var films_data = getAnimesList($(this), 'NewUpdated', 'A');
        $('#homepage>.list_movies>.items').html(films_data);
    });
});

function getAnimesList(selector, filterMode, filterType) {
    var films_data = null;

    var requestUrl = $('#MainUrl').attr('href');
    switch (filterMode){
        case 'NewUpdated':
            requestUrl += '/get-list-newUpdated';
            $('#homepage>.titleBar>div>.selected').removeClass('selected');
            break;
        case 'NewestList':
            requestUrl += '/get-list-newestAnime';
            $('#sidebar>.newest_film>.titleBar>div>.selected').removeClass('selected');
            break;
        case 'MostViewList':
            requestUrl += '/get-list-mostView';
            $('#sidebar>.most_view>.titleBar>div>.selected').removeClass('selected');
            break;
        default:
            ;
    }
    selector.addClass('selected');

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: requestUrl,
        type: "post",
        data: {'type': filterType, 'page': 1, _token: CSRF_TOKEN},
        async: false,
        success: function(data){
            films_data = data;
        }
    });
    return films_data;
}
// Search scripts
function lookup(inputString) {
    if(inputString.length === 0) {
        $('#suggestions').fadeOut(); // Hide the suggestions box
    } else {
        $('#suggestions').fadeIn(); // Show the suggestions box

        var requestUrl = $('#MainUrl').attr('href') + '/search';
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({ // Do an AJAX call
            url: requestUrl,
            type: "post",
            data: {'searchString': inputString, _token: CSRF_TOKEN},
            async: false,
            success: function(data){
                if(data)
                    $('#searchresults').html(data); // Fill the suggestions box
            }
        });
    }
}
/*** END FILTER ***/

/*** START VIEWPAGE scripts ***/
// Video View Page Animation
$(document).ready(function () {
    // Cuộn tới vị trí đầu Player
    $(window).scroll(function () {
        $(this).delay(1000).queue(function () {

            var pos = $(window).scrollTop();
            var offset = $("div#player").offset().top;
            if (Math.abs(pos - offset) <= 50) {
                $('html,body').animate({
                        scrollTop: offset - 40
                    },
                    'fast');
            }

            $(this).dequeue();
        });
    });
});

// Script Zoom, Light On/Off or View Video Info Player
var playerZoom = false;
$(document).ready(function () {
    $(".videozoom").on( "click", function() {
        if($('.video_player').css('display')==='none')
        {
            alert('Hãy trở lại trang xem phim');
        }else {
            playerZoom = !playerZoom;
            if(playerZoom) {
                $(this).text("Thu Nhỏ");
                $(this).attr('title', "Thu Nhỏ");
                $("#player").css('width', '980px');
                $("#player").css('height', '572px');
                $("#player").attr('width', '980');
                $("#player").attr('height', '572');
                $("#sidebar").css("margin-top", "0");
                $(".shadow").css("height", $(document).height());
            }
            else {
                $(this).text("Phóng To");
                $(this).attr('title', "Phóng To");
                $("#player").css('width', '680px');
                $("#player").css('height', '420px');
                $("#player").attr('width', '680');
                $("#player").attr('height', '420');
                $("#sidebar").css("margin-top", "-420px");
                $(".shadow").css("height", $(document).height());
            }
            $('html,body').animate({
                    scrollTop: $("#player").offset().top - 40},
                'slow');
        }
    });

    $(".lightoff").on( "click", function() {
        $(".shadow").toggle();
        if($("#top_menu").css("z-index")>200)
        {
            $(this).text("Bật Đèn");
            $(this).attr('title', "Bật Đèn");
            $("#top_menu").css("z-index", 198);
        }else {
            $(this).text("Tắt Đèn");
            $(this).attr('title', "Tắt Đèn");
            $("#top_menu").css("z-index", 201);
        }
    });

    $('.video_control>.item>.video_info').on( "click", function(e) {
        if($('.video_detail').css('display')==='none')
        {
            $('#sidebar').css('margin-top', '-' + $('.video_detail').outerHeight() + 'px');
            $('.video_player').css('display', 'none');
            $('.video_detail').fadeIn();
            $(this).text('Xem Phim');
            $(this).attr('title', "Xem Phim");
        }
        else
        {
            $('#sidebar').css('margin-top', '-' + $('.video_player').height() + 'px');
            $('.video_player').fadeIn();
            $('.video_detail').css('display', 'none');
            $(this).text('Thông Tin');
            $(this).attr('title', "Thông Tin");
        }
    });
});

/*** END VIEWPAGE scripts ***/

/*** START USERCP scripts ***/
$(document).ready(function () {
    // script for delete a bookmark
    $('.delBtn').bind('click', function (e) {
        e.preventDefault();
        var _url = $(this).attr('href');
        var _id = _url.substring(_url.lastIndexOf('-')+1);

        var requestUrl = $('#MainUrl').attr('href') + '/bookmark-delete';
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({ // Do an AJAX call
            url: requestUrl,
            type: "post",
            data: {'id': _id, _token: CSRF_TOKEN},
            async: false,
            success: function(data){
                alert('Xóa thành công.');
                $('#userCPBookmarks').html(data);
            }
        });
    });

    // scripts for save a bookmark
    $('.bookmarkBtn').bind('click', function (e) {
        var _url = window.location.href;
        var _id = _url.substring(_url.indexOf('xem-phim/'));
        _id = _id.substring(_id.indexOf('/')+1);
        _id = _id.substring(_id.indexOf('/')+1);
        if(_id.indexOf('/')>0)
            _id = _id.substring(0, _id.indexOf('/'));
        if(_id.indexOf('.')>0)
            _id = _id.substring(0, _id.indexOf('.'));


        var requestUrl = $('#MainUrl').attr('href') + '/bookmark';
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({ // Do an AJAX call
            url: requestUrl,
            type: "post",
            data: {'id': _id, _token: CSRF_TOKEN},
            async: false,
            success: function(data){
                if(data){
                    alert('Lưu thành công.');
                }
                else{
                    alert('Lưu thất bại hoặc đã lưu.');
                }
            }
        });
    });

    // scripts for redirect to next episode
    $('.nextEpBtn').bind('click', function (e) {
        var x = $('.epN'); //returns the matching elements in an array

        var _N = -1;
        for (i = 0; i < x.length; i++) {
            if($(x[i]).hasClass('active'))
            {
                _N = i + 1;
                break;
            }
        }
        if(_N>=0)
            $(location).attr('href', $(x[_N]).attr('href'));
    });

    $('.userCpBtn').bind('click', function (e) {
        userCPToggle();
    });

    $('#userCP>.overlay').bind('click', function (e) {
        userCPToggle();
    });

});
// scripts for show/hide user control panel
function userCPToggle() {
    if($('#userCP').css('display')==='none'){
        $("body").css("overflow", "hidden");
        $('#userCP').fadeIn();
        $('.userCpBtn').text("Đóng");
        $('.userCpBtn').attr('title', "Đóng");
    }
    else{
        $("body").css("overflow", "auto");
        $('#userCP').fadeOut();
        $('.userCpBtn').text("Danh sách");
        $('.userCpBtn').attr('title', "Danh sách");
    }
    $('html,body').animate({
            scrollTop: $("#header").offset().top},
        'fast');
}
/*** END USERCP scripts ***/