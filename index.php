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
    <?php print('<header>
                <a href="?path=projects/">Projects</a>
                <a href="?path=employees/">Employees</a>
            </header>');
    ?>

    <div class="container">
        <?php
        $serverName = 'localhost';
        $username = 'root';
        $password = 'mysql';
        $dbName = 'sprint2-db';

        $conn = mysqli_connect($serverName, $username, $password, $dbName);

        $sql = 'SELECT id, Name, Projects FROM employees';
        $result = mysqli_query($conn, $sql);

        $sqlProj = 'SELECT id, Project, Employees FROM projects';
        $resultProj = mysqli_query($conn, $sqlProj);

        if ($_GET['path'] == 'employees/') {
            print('<table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Projects</th>
            </tr>');

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    print('<tr>
                         <td>' . $row["id"] . '</td>' .
                        '<td>' . $row["Name"] . '</td>' .
                        '<td>' . $row["Projects"] . '</td>
                       </tr>');
                }
            } else {
                print('No results');
            }
            print('</table>');
        } else {
            print('<table>
            <tr>
                <th>ID</th>
                <th>Project</th>
                <th>Employee(s)</th>
            </tr>');

            if (mysqli_num_rows($resultProj) > 0) {
                while ($row = mysqli_fetch_assoc($resultProj)) {
                    print('<tr>
                         <td>' . $row["id"] . '</td>' .
                        '<td>' . $row["Project"] . '</td>' .
                        '<td>' . $row["Employees"] . '</td>
                       </tr>');
                }
            } else {
                print('No results');
            }
            print('</table>');
        }

        mysqli_close($conn);

        ?>
    </div>
</body>

</html>