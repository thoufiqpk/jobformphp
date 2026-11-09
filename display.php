<?php

// Database connection
$conn = new mysqli("localhost", "root", "", "job_application");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve records
$sql = "SELECT id, name, email, mobile, username
        FROM applicants";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Applicant Details</title>

    <style>
        body {
            font-family: Arial;
            background: #e8f0fe;
            text-align: center;
        }

        h2 {
            color: #333;
        }

        table {
            width: 80%;
            margin: 30px auto;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }

        th {
            background: #2563eb;
            color: white;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }
    </style>

</head>

<body>

<h2>Applicant Details</h2>

<table>

    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Mobile Number</th>
        <th>Username</th>
    </tr>

    <?php

    if ($result && $result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {

            echo "<tr>";

            echo "<td>" . htmlspecialchars($row['id']) . "</td>";

            echo "<td>" . htmlspecialchars($row['name']) . "</td>";

            echo "<td>" . htmlspecialchars($row['email']) . "</td>";

            echo "<td>" . htmlspecialchars($row['mobile']) . "</td>";

            echo "<td>" . htmlspecialchars($row['username']) . "</td>";

            echo "</tr>";
        }

    } else {

        echo "<tr><td colspan='5'>No records found</td></tr>";

    }

    $conn->close();

    ?>

</table>

</body>
</html>