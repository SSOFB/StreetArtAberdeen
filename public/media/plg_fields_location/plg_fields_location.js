(function($){
    var plg_fields_location_class = function() {
        var root = this;
        var vars = {
            timer:false
        };
        this.fields = {};
        this.maps = {};
        this.markers = {};
        var construct = function() {};
        this.registerField = function(id,element,target) {
            if(document.getElementById(element)) {
                return;
            }
            var t = document.getElementById(target);  
            $(t).after($('<div id="'+element+'"/>'));
            this.fields[id] = {
                element:document.getElementById(element),
                target:t,
                watch:'target',
                method:'setupMap',
                setup:false
            };
            if(!vars.timer) {
                startTimer();
            }
        };
        this.registerDisplay = function(id,element) {
            if(this.fields[id]) {
                return;
            }
            this.fields[id] = { 
                element:document.getElementById(element),
                watch:'element',
                method:'setupDisplay',
                setup:false
            };
            if(!vars.timer) {
                startTimer();
            }
        };
        var startTimer = function() {
            var restart = false;
            $.each(root.fields,function(id,o){
                if(o.setup) {
                    return;
                }
                if(o[o.watch] === null || o[o.watch].offsetParent === null) {
                    restart = true;
                } else {
                    root[o.method](id);
                }
            });
            if(restart) {
                setTimeout(startTimer,1000);
            } else {
                vars.timer = false;
            }
        };
        this.setupMap = function(id) {
            this.setupDisplay(id);
            root.maps[id].addListener('click',function(event){
                var latlon = [event.latLng.lat(),event.latLng.lng()];
                $(root.fields[this.plg_fields_location_id].target).val(latlon.join(","));
                root.markers[this.plg_fields_location_id].setPosition(event.latLng);
            });
            
            if(Joomla.getOptions('plg_fields_location_'+id).searchbox) {
                root.setupSearch(id);
            }
            root.fields[id].setup = true;
        };
        this.setupSearch = function (id) {
            var input = $('<input id="' + root.fields[id].element.id + '_searchbox" class="xcontrols" type="text" placeholder="' + Joomla.JText._('PLG_FIELDS_LOCATION_SEARCHBOX_PLACEHOLDER') + '">');
            $(root.fields[id].target).after(input);
            var searchbox = document.getElementById(root.fields[id].element.id + '_searchbox');
            var searchBox = new google.maps.places.SearchBox(searchbox);
            root.maps[id].controls[google.maps.ControlPosition.TOP_LEFT].push(searchbox);
            root.maps[id].addListener('bounds_changed', function () {
                searchBox.setBounds(root.maps[id].getBounds());
            });
            searchBox.addListener('places_changed', function () {
                var places = searchBox.getPlaces();
                if (places.length == 0) {
                    return;
                }
                var bounds = new google.maps.LatLngBounds();
                if(!places[0].geometry) {
                    console.log('place has no geometry');
                    return;
                }
                root.maps[id].setCenter(places[0].geometry.location);
                root.markers[id].setPosition(places[0].geometry.location);
            });
        };
        this.setupDisplay = function(id) {
            var options = Joomla.getOptions('plg_fields_location_'+id);
            var mapOptions = {
                zoom:parseInt(options.zoom),
                center:new google.maps.LatLng(options.center[0],options.center[1]),
                mapTypeId:google.maps.MapTypeId[options.mapTypeId]
            };
            root.maps[id] = new google.maps.Map(root.fields[id].element,mapOptions);
            root.maps[id].plg_fields_location_id = id;
            root.markers[id] = new google.maps.Marker({position:mapOptions.center,map:root.maps[id]});
            root.fields[id].setup = true;            


            markerMyLocation = new google.maps.Marker();
            const locationButton = document.createElement("div");
            locationButton.textContent = "Go to your current location";
            locationButton.classList.add("custom-map-control-button");
            locationButton.classList.add("btn");
            locationButton.classList.add("btn-primary");
            root.maps[id].controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);
            locationButton.addEventListener("click", () => {
                // Try HTML5 geolocation.
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const pos = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude,
                            };
                            root.maps[id].setCenter(pos);
                            markerMyLocation.setPosition(pos);   
                            markerMyLocation.setMap(root.maps[id]);
                        },
                        () => {
                            handleLocationError(true, markerMyLocation, root.maps[id].getCenter(), root.maps[id]);
                        }
                    );
                } else {
                    // Browser doesn't support Geolocation
                    handleLocationError(false, markerMyLocation, root.maps[id].getCenter(), root.maps[id]);
                }
            });

            // set the lat lon from input, maybe from exif data
            var latlonbox = document.getElementById('jform_com_fields_location');
            latlonbox.addEventListener("change", (event) => {
                var latlon = document.getElementById('jform_com_fields_location').value;
                console.log('latlon: ' + latlon);
                let latlon_arr = latlon.split(',');
                console.log('lat: ' + latlon_arr[0]);
                console.log('lon: ' + latlon_arr[1]);
                const pos = {
                    lat: parseFloat(latlon_arr[0]),
                    lng: parseFloat(latlon_arr[1]),
                };
                root.maps[id].setCenter(pos);
                markerMyLocation.setPosition(pos);   
                markerMyLocation.setMap(root.maps[id]);
                root.maps[id].setZoom(19);
            });
        };

        construct();
    };

    function handleLocationError(browserHasGeolocation, markerMyLocation, pos, map) {
        markerMyLocation.setPosition(pos);
        markerMyLocation.setContent(
           browserHasGeolocation
           ? "Error: The Geolocation service failed."
           : "Error: Your browser doesn't support geolocation."
        );
        markerMyLocation.setMap(map);
    };

    $(document).ready(function(){
        window.plg_fields_location = new plg_fields_location_class();
    });
})(jQuery);
