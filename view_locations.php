<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gis";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name, latitude, longitude, zoom, description FROM locations";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>View Locations</title>
</head>

<body>
    <h1>View Locations</h1>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th>Zoom</th>
            <th>Description</th>
            <th>Map</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["latitude"] . "</td>";
                echo "<td>" . $row["longitude"] . "</td>";
                echo "<td>" . $row["zoom"] . "</td>";
                echo "<td>" . $row["description"] . "</td>";
                echo "<td><a href='view_map?lat=" . $row["latitude"] . "&lng=" . $row["longitude"] . "&zoom=" . $row["zoom"] . "'>View Map</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No results found</td></tr>";
        }
        ?>
    </table>
</body>

</html>

<?php
$conn->close();
?>