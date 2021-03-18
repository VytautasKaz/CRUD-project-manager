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
        <a href="?path=projects/">Projects</a>
        <a href="?path=employees/">Employees</a>
    </header>

    <div class="container">
        <?php
        $serverName = 'localhost';
        $username = 'root';
        $password = 'mysql';
        $dbName = 'sp2db';

        $conn = mysqli_connect($serverName, $username, $password, $dbName);

        $sql = 'SELECT id_empl, fname, assigned_project FROM empl';
        $result = mysqli_query($conn, $sql);

        $sqlProj = 'SELECT DISTINCT id_proj, title, group_concat(empl.fname SEPARATOR ", ") AS empname FROM proj
        LEFT JOIN empl
        ON proj.title = empl.assigned_project GROUP BY id_proj';
        $resultProj = mysqli_query($conn, $sqlProj);

        if ($_GET['path'] == 'employees/') {
            print('<table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Projects</th>
                <th>Actions</th>
            </tr>');

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    print('<tr>
                                <td>' . $row['id_empl'] . '</td>
                                <td>' . $row['fname'] . '</td>
                                <td>' . $row['assigned_project'] . '</td>
                                <td>
                                        <form action="" method="POST">
                                            <button type="submit" name="delete" value="' . $row['id_empl'] . '" onclick="return confirm(\'Are you sure?\')">Delete</button>
                                        </form>
                                        <form action="" method="POST">
                                            <button type="submit" name="update" value="">Update</button>
                                        </form>
                                </td>
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
                <th>Actions</th>
            </tr>');

            if (mysqli_num_rows($resultProj) > 0) {
                $counter = 0;
                while ($row = mysqli_fetch_assoc($resultProj)) {
                    print('<tr>
                                <td>' . ++$counter . '</td>
                                <td>' . $row['title'] . '</td>
                                <td>' . $row['empname'] . '</td>
                                <td>
                                        <form action="" method="POST">
                                            <button type="submit" name="delete" value="' . $row["id_proj"] . '" onclick="return confirm(\'Are you sure?\')">Delete</button>
                                        </form>
                                        <form action="" method="POST">
                                            <button type="submit" name="update" value="">Update</button>
                                        </form>
                                </td>
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