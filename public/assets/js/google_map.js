var gmarkers = [];

function initializeMap() {
    gmarkers = [];
    var info_window_marker_array = [];

    $(".user-download-data").each(function () {
        var el = $(this);
        var downloadCoordLat = el.data('download-location-lat');
        var downloadCoordLong = el.data('download-location-long');
        var userName = el.data('user-name');
        var userMobile = el.data('user-mobile');

        var temp_json = {
            "download_coord_lat": downloadCoordLat,
            "download_coord_long": downloadCoordLong,
            "user_name": userName,
            "user_mobile": userMobile,
        };
        info_window_marker_array.push(temp_json)
    });

    var centerLatLng = {lat: 19.4484654, lng: 72.8020317};

    var myOptions = {
        zoom: 14,
        center: centerLatLng,
        mapTypeId: 'roadmap'
    };

    var map = new google.maps.Map(document.getElementById("mapcanvas"), myOptions);
    var infoWindow = new google.maps.InfoWindow();

    for (var i = 0; i < info_window_marker_array.length; i++) {

        var data = info_window_marker_array[i];
        var markerLatLong = {lat: parseFloat(data.download_coord_lat), lng: parseFloat(data.download_coord_long)};
        console.log(markerLatLong)


        var marker = new google.maps.Marker({
            position: markerLatLong,
            map: map,
            // icon: "assets/images/icons/map_marker.png",
        });

        gmarkers.push(marker);

        (function (marker, data, infoWindow) {
            google.maps.event.addListener(marker, "mouseover", function (e) {

                // for popup
                var contentString = 'User';

                infoWindow.setContent(contentString);
                infoWindow.open(map, this);
            });
        })(marker, data, infoWindow);

        marker.addListener('mouseout', function () {
            infoWindow.close(map, this);
        });
    }

    //for styling the info window
    google.maps.event.addListener(infoWindow, 'domready', function () {
        var iwOuter = $('.gm-style-iw');
        var iwBackground = iwOuter.prev();
        iwBackground.children(':nth-child(2)').css({'display': 'none'});
        iwBackground.children(':nth-child(4)').css({'display': 'none'});
        iwOuter.parent().parent().css({left: '115px'});
        iwBackground.children(':nth-child(1)').attr('style', function (i, s) {
            return s + 'left: 76px !important;'
        }).css('z-index', '10');
        iwBackground.children(':nth-child(3)').attr('style', function (i, s) {
            return s + 'left: 76px !important;'
        }).css('z-index', '10');
        iwBackground.children(':nth-child(3)').find('div').children('div:first').css({'box-shadow': 'rgba(0, 150, 136, 1) -1px 0px 0px'});
        iwBackground.children(':nth-child(3)').find('div').children('div:last').css({'box-shadow': 'rgba(0, 150, 136, 1) 1px 0px 0px'});

        var iwCloseBtn = iwOuter.next();
        iwCloseBtn.css({'display': 'none'});
    });
}
//for showing the markers on hover of property cards
function events_call() {
    $(document).on('mouseover', ".user-download-data", function () {
        var el = $(this)
        var index = el.data('index');
        google.maps.event.trigger(gmarkers[index], 'mouseover');
    })
    $(document).on('mouseout', ".user-download-data", function () {
        var el = $(this)
        var index = el.data('index');
        google.maps.event.trigger(gmarkers[index], 'mouseout');
    });
}