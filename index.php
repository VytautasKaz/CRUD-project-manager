<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <header>
        <a href="#">Projects</a>
        <a href="#">Employees</a>
    </header>


    <div class="container">
        <?php
        $servername = 'localhost';
        $username = 'root';
        $password = 'mysql';
        $dbName = 'sprint2-db';

        $conn = mysqli_connect($servername, $username, $password, $dbName);

        // if (!$conn) {
        //     die("Connection failed: " . mysqli_connect_error());
        // }
        // echo "Connected successfully";

        $sql = 'SELECT id, Name, Projects FROM employees';
        $result = mysqli_query($conn, $sql);

        print('<table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Projects</th>
            </tr>');

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>
                <td>' . $row["id"] . '</td>' .
                    '<td>' . $row["Name"] . '</td>' .
                    '<td>' . $row["Projects"] . '</td>
                </tr>';
            }
        } else {
            echo "0 results";
        }

        print('</table>');

        mysqli_close($conn);

        ?>
    </div>
</body>

</html>