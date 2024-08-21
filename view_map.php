<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login");
    exit();
}

$email = $_SESSION['email'];
?>

<?php include 'layout/header.php' ?>
<?php
include 'koneksi.php';

$id = $_GET['id'];

$query = "SELECT * FROM locations WHERE id = $id";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    $latitude = $row['latitude'];
    $longitude = $row['longitude'];
    $zoom = $row['zoom'];
    $name = $row['name'];
    $description = $row['description'];
    $image = $row['image'];
?>
    <h2>Peta Lokasi: <?php echo $name; ?></h2>

    <div id="map" style="height: 500px;"></div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-routing-machine/3.2.12/leaflet-routing-machine.min.js"></script>
    <script>
        var map = L.map('map').setView([<?php echo $latitude; ?>, <?php echo $longitude; ?>], <?php echo $zoom; ?>);

        var streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        });

        var satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
        });

        var baseLayers = {
            "Peta Jalan": streets,
            "Peta Satelit": satellite
        };

        streets.addTo(map);

        L.control.layers(baseLayers).addTo(map);

        var marker = L.marker([<?php echo $latitude; ?>, <?php echo $longitude; ?>]).addTo(map);

        // Bind popup ke marker dan tambahkan event listener untuk klik
        marker.bindPopup("<b><?php echo $name; ?></b><br><?php echo $description; ?>").openPopup();
        marker.on('click', function() {
            marker.openPopup();
        });

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var userLat = position.coords.latitude;
                var userLng = position.coords.longitude;

                L.Routing.control({
                    waypoints: [
                        L.latLng(userLat, userLng),
                        L.latLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>)
                    ],
                    routeWhileDragging: true,
                    router: new L.Routing.osrmv1({
                        language: 'id',
                    }),
                    lineOptions: {
                        styles: [{
                            color: '#3388ff',
                            opacity: 0.7,
                            weight: 5
                        }]
                    }
                }).addTo(map);
            });
        }
    </script>

<?php
} else {
    echo "Data lokasi tidak ditemukan.";
}
?>
<?php include 'layout/footer.php' ?>