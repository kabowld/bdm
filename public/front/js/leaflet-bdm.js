(function() {
    const inputLocation = document.querySelector('#annonce_location');

    if (inputLocation !== null) {
        function addMarker(suggestion) {
            var marker = L.marker(suggestion.latlng, {opacity: .4});
            marker.addTo(map);
            markers.push(marker);
        }

        function removeMarker(marker) {
            map.removeLayer(marker);
        }

        function findBestZoom() {
            var featureGroup = L.featureGroup(markers);
            map.fitBounds(featureGroup.getBounds().pad(0.5), {animate: false});
        }

        let place = places({
            container: inputLocation
        }).configure({
            language: 'fr',
            countries: ['CI'],
            type: 'address',
            aroundLatLngViaIP: false
        });

        let map = L.map('box-map-address', {
            scrollWheelZoom: false,
            zoomControl: false
        });

        let osmLayer = new L.TileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                minZoom: 1,
                maxZoom: 18,
                touchZoom: true,
                attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
            }
        );

        let markers = [];

        map.setView(new L.LatLng(0, 0), 1);
        map.addLayer(osmLayer);

        place.on('suggestions', function (e) {
            markers.forEach(removeMarker);
            markers = [];

            if (e.suggestions.length === 0) {
                map.setView(new L.LatLng(0, 0), 1);
                return;
            }

            e.suggestions.forEach(addMarker);
            findBestZoom();
        });
        place.on('cursorchanged', function (e) {
            markers
                .forEach(function(marker, markerIndex) {
                    if (markerIndex === e.suggestionIndex) {
                        marker.setOpacity(1);
                        marker.setZIndexOffset(1000);
                    } else {
                        marker.setZIndexOffset(0);
                        marker.setOpacity(0.5);
                    }
                });
        });
        place.on('change',  function (e) {
            markers
                .forEach(function(marker, markerIndex) {
                    if (markerIndex === e.suggestionIndex) {
                        markers = [marker];
                        marker.setOpacity(1);
                        findBestZoom();
                    } else {
                        removeMarker(marker);
                    }
                });
            let suggest = e.suggestion;
            document.querySelector('#annonce_lat').value = suggest.latlng.lat;
            document.querySelector('#annonce_lng').value = suggest.latlng.lng;
            document.querySelector('#annonce_postalCode').value = suggest.postcode;
        });
        place.on('clear', function () {
            map.setView(new L.LatLng(0, 0), 1);
            markers.forEach(removeMarker);
            document.querySelector('#annonce_lat').value = '';
            document.querySelector('#annonce_lng').value = '';
            document.querySelector('#annonce_postalCode').value = '';
        });
    }
})();
