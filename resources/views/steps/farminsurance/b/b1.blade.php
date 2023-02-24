<div class="frame">
    <div class="frame-contents">
        <div class="bubble bubble-type-a left">
            <div class="bubble-contents">
            	<i class="bubble-help btn-sidebar" data-content="gardsforsakring-b-1">?</i>
            	<p><span style="font-weight:600;">Ok, då kör vi igång!</span><br/>
                Först behöver vi hitta din gård - det gör vi genom att du fyller i din adress i fältet nedan.</p>
            </div>
        </div>

        <div class="bubble bubble-type-d center">
            <div class="bubble-contents">

                <input id="konfigurator-autocomplete" placeholder="Din adress" type="text">

            </div>
        </div>

        <div id="map-container" class="bubble bubble-type-d">

                <input type="hidden" name="street" value="">
                <input type="hidden" name="zip" value="">
                <input type="hidden" name="city" value="">

                <div id="map"></div>
                <!--
                <h3>Street View</h3>
                <div id="streetview" style="height:200px;"></div>
            	-->
        </div>

        <div class="navigation">
	        <button type="button" class="btn1 btn-next">Gå vidare</button>
	        <button type="button" class="btn2 btn-next" data-skip="1">Hoppa över det här steget</button>
	    </div>

    </div>
</div>

<script
    type='text/javascript'
    src='//maps.googleapis.com/maps/api/js?key={{ $google_maps_secret }}&libraries=places&ver=5.7.6&callback=initializeMaps'
    id='google-libs-js'
    async
></script>

<script type="text/javascript">

    function initializeMaps() {

        var ac = new google.maps.places.Autocomplete(
            (document.getElementById('konfigurator-autocomplete')), {
                types: ['geocode'],
                componentRestrictions: {
                    country: 'se'
                }
            });

        ac.addListener('place_changed', function() {

        	var activeInfoWindow;

            setTimeout(function(){
                //document.getElementById('konfigurator-autocomplete').value = '';
            }, 100);

            jQuery('#map-container').fadeIn(300);

            var place = ac.getPlace();

            var map = new google.maps.Map(
                document.getElementById('map'), {
                    disableDefaultUI: true,
                    scrollwheel: false,
                    mapTypeId: 'satellite',
                    zoomControl: true,
                    zoom: 18,
                    tilt: 0,
                    center: {
                        lat: place.geometry.location.lat(),
                        lng: place.geometry.location.lng()
                    },

                });

            {{--
            // Streetview
            const fenway = {
                lat: place.geometry.location.lat(),
                lng: place.geometry.location.lng()
            };
            const map2 = new google.maps.Map(document.getElementById("streetview"), {
                center: fenway,
                zoom: 14,
            });
            const panorama = new google.maps.StreetViewPanorama(
                document.getElementById("streetview"),
                {
                    position: fenway,
                    pov: {
                        heading: 34,
                        pitch: 10,
                    },
                }
            );
            map2.setStreetView(panorama);

            --}}
           
            var marker = new google.maps.Marker({
				icon: "{{ asset('img/dunstan-marker-s.png') }}",
				animation: google.maps.Animation.DROP,
				position: {
					lat: place.geometry.location.lat(),
					lng: place.geometry.location.lng()
				},
				map: map
			});

			var contentString =
				'<div id="map-window">' +
				'<h3>Har vi hittat rätt?</h3>' +
				'<p>Om det här är din gård - klicka på "Gå vidare" längst ner på sidan.</p>' +
				'</div>';

			var infowindow = new google.maps.InfoWindow({
				content: contentString,
			});

			google.maps.event.addListenerOnce(map, 'tilesloaded', function() {
				infowindow.open(map, marker);
			});

			google.maps.event.addListener(marker, 'click', function() {
							
				if(activeInfoWindow != null) activeInfoWindow.close();

				infowindow.open(map, marker);
				
				activeInfoWindow = infowindow;
			}); 

            function extractFromAddress(components, type) {
                return components.filter((component) => component.types.indexOf(type) === 0).map((item) => item.long_name).pop() || null;
            }

            var address_components = place["address_components"] || [];
            var zip = extractFromAddress(address_components, "postal_code");
            var street = extractFromAddress(address_components, "route");
            var streetNum = extractFromAddress(address_components, "street_number");
            var town = extractFromAddress(address_components, "postal_town");

            // Update hidden fields
            $('.frame').find('input[name=street]').val(street+' '+streetNum);
            $('.frame').find('input[name=zip]').val(zip);
            $('.frame').find('input[name=city]').val(town);

            if (!place.geometry) {
                window.alert("Vi kan inte hitta '" + place.name + "'");
                return;
            }

        });
    }
</script>
