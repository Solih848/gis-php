<?php include 'layout/header-users.php' ?>
<div class="d-flex justify-content-between mb-2 mt-4">
    <div class="d-flex">
        <form id="categoryForm" class="form-inline">
            <label for="category">Pilih Kategori:</label>
            <select id="category" name="category" required>
                <option value="">Pilih Kategori</option>
                <?php
                include 'koneksi.php';
                $query = "SELECT * FROM categories";
                $result = mysqli_query($koneksi, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                    }
                }
                ?>
            </select>
            <button type="submit" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-search"></i> Cari
            </button>
            <button type="button" onclick="refreshPage()" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-arrows-rotate"></i> Refresh</button>
        </form>
        <button type="button" onclick="getCurrentLocation()" class="btn btn-outline-success btn-sm mx-1"><i class="fa-solid fa-location-crosshairs"></i> Lokasi Saat Ini</button>
    </div>
    <form id="mapStyleForm">
        <label for="mapStyle" class="form-inline">Pilih Tampilan Peta:</label>
        <select id="mapStyle" name="mapStyle" onchange="changeMapStyle()">
            <option value="osm">OpenStreetMap</option>
            <option value="esriWorldStreetMap">Esri WorldStreetMap</option>
            <option value="esriSatellite">Esri Satellite</option>
        </select>
    </form>
</div>
<div id="map" style="height: 590px;"></div>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
<script>
    var map = L.map('map').setView([-7.0465, 113.7662], 13); // Set default view
    var currentLocation = null;
    var currentLocationMarker = null;
    var routeControl = null;

    var tileLayers = {
        'osm': L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }),
        'esriWorldStreetMap': L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, DeLorme, NAVTEQ'
        }),
        'esriSatellite': L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
        })
    };

    tileLayers['osm'].addTo(map); // Add default layer

    function changeMapStyle() {
        var selectedStyle = document.getElementById('mapStyle').value;
        map.eachLayer(function(layer) {
            if (layer instanceof L.TileLayer) {
                map.removeLayer(layer);
            }
        });
        tileLayers[selectedStyle].addTo(map);
    }

    document.getElementById('categoryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var categoryId = document.getElementById('category').value;

        fetch('get_locations_by_category?category_id=' + categoryId)
            .then(response => response.json())
            .then(data => {
                map.eachLayer(function(layer) {
                    if (layer instanceof L.Marker && layer !== currentLocationMarker) {
                        map.removeLayer(layer);
                    }
                });

                if (routeControl) {
                    map.removeControl(routeControl); // Remove previous route control
                }

                var waypoints = [];
                data.forEach(function(location) {
                    var categoryIcon = L.icon({
                        iconUrl: 'assets/location/blue.png', // Ubah path ini ke ikon kategori Anda
                        iconSize: [30, 50],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34]
                    });

                    var marker = L.marker([location.latitude, location.longitude], {
                        icon: categoryIcon
                    }).addTo(map);
                    marker.bindPopup("<b>" + location.name + "</b><br>" + location.description + "<br><img src='uploads/" + location.image + "' width='50'>");
                    waypoints.push(L.latLng(location.latitude, location.longitude));

                    marker.on('click', function() {
                        marker.openPopup();
                    });
                });

                if (currentLocation) {
                    routeControl = L.Routing.control({
                        waypoints: [currentLocation, ...waypoints],
                        createMarker: function() {
                            return null;
                        }, // Do not show markers for the route
                        routeWhileDragging: true,
                        addWaypoints: false,
                        show: false // Do not show route instructions
                    }).addTo(map);
                }
            });
    });

    function refreshPage() {
        window.location.reload();
    }

    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) {
        var lat = position.coords.latitude;
        var lon = position.coords.longitude;
        currentLocation = L.latLng(lat, lon);

        var currentLocationIcon = L.icon({
            iconUrl: 'assets/location/red.png', // Ubah path ini ke ikon lokasi Anda
            iconSize: [30, 50],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34]
        });

        if (currentLocationMarker) {
            map.removeLayer(currentLocationMarker); // Remove existing marker if any
        }

        currentLocationMarker = L.marker(currentLocation, {
                icon: currentLocationIcon
            }).addTo(map)
            .bindPopup("<b>Lokasi Saat Ini</b>")
            .openPopup();
        map.setView(currentLocation, 13);
    }

    function showError(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                alert("User denied the request for Geolocation.");
                break;
            case error.POSITION_UNAVAILABLE:
                alert("Location information is unavailable.");
                break;
            case error.TIMEOUT:
                alert("The request to get user location timed out.");
                break;
            case error.UNKNOWN_ERROR:
                alert("An unknown error occurred.");
                break;
        }
    }
</script>
<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Core theme JS-->
<script src="js/scripts.js"></script>
</body>

</html>