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

        // Generates projects dropdown options when adding a new employee

        function generateOptions($input)
        {
            if (mysqli_num_rows($input) > 0) {
                while ($row = mysqli_fetch_assoc($input)) {
                    echo "<option value='" . $row['title'] . "'>" . $row['title'] . "</option>";
                }
            }
        }

        // Delete button logic

        if (isset($_POST['delete'])) {
            if ($_GET['path'] == 'employees/') {
                $stmt = $conn->prepare("DELETE FROM empl WHERE id_empl = ?");
            } else {
                $stmt = $conn->prepare("DELETE FROM proj WHERE id_proj = ?");
            }
            $stmt->bind_param("i", $_POST['delete']);
            $stmt->execute();
            $stmt->close();
            header('Location: ' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
            die;
        }

        // Add employee/project logic

        if (isset($_POST['create'])) {
            if ($_GET['path'] == 'employees/') {
                $stmt = $conn->prepare("INSERT INTO empl (fname, assigned_project) VALUES (?, ?)");
                $stmt->bind_param("ss", $fname, $a_project);
                $fname = $_POST['fname'];
                $a_project = $_POST['a_proj'];
            } else {
                $stmt = $conn->prepare("INSERT INTO proj (title) VALUES (?)");
                $stmt->bind_param("s", $title);
                $title = $_POST['new_project'];
            }
            $stmt->execute();
            $stmt->close();
            header('Location: ' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
            die;
        }

        if ($_GET['path'] == 'employees/') {
            print('<table>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Projects</th>
                <th>Actions</th>
            </tr>');

            if (mysqli_num_rows($result) > 0) {
                $counter = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    print('<tr>
                                <td>' . ++$counter . '</td>
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
            print('<form class="new-entry" action="" method="POST">
                        <input type="text" name="fname" placeholder="Enter employee name" />
                        <select name="a_proj">
                            <option value=""></option>');

            generateOptions($resultProj);

            print('</select>
                        <button type="submit" name="create">Create</button>
                   </form>');
        } else {
            print('<table>
            <tr>
                <th>#</th>
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
            print('<form class="new-entry" action="" method="POST">
                        <input type="text" name="new_project" placeholder="Enter project name" />
                        <button type="submit" name="create">Create</button>
                   </form>');
        }

        mysqli_close($conn);

        ?>
    </div>
</body>

</html>