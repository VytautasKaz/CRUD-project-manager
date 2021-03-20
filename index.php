<?php ob_start();
?>
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

        // Establishing connection to mysql server

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

        // Generates dropdown options of existing projects

        function generateOptions($queryRes)
        {
            if (mysqli_num_rows($queryRes) > 0) {
                while ($row = mysqli_fetch_assoc($queryRes)) {
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
            $fname = $_POST['fname'];
            $a_project = $_POST['a_proj'];

            $title = $_POST['new_project'];

            if ($_GET['path'] == 'employees/') {
                $stmt = $conn->prepare("INSERT INTO empl (fname, assigned_project) VALUES (?, ?)");
                $stmt->bind_param("ss", $fname, $a_project);
            } else {
                $stmt = $conn->prepare("INSERT INTO proj (title) VALUES (?)");
                $stmt->bind_param("s", $title);
            }
            if (!empty($fname) || !empty($title)) {
                $stmt->execute();
                $stmt->close();
                print(header('Location: ' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']));
                die;
            }
        }

        // Generating table for employees/projects

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
                                            <button type="submit" name="update" value="' . $row['id_empl'] . '">Update</button>
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

            // Update employee info logic

            if (isset($_POST['update'])) {
                print('<form class="new-entry" action="" method="POST">
                            <label>Update name:</label><br>
                            <input type="text" name="fname" /><br>
                            <label>Reassign project:</label><br>
                            <select name="a_proj">
                                <option value="" placeholder="Select"></option>');

                $title_result = mysqli_query($conn, 'SELECT title FROM proj');

                generateOptions($title_result);

                print('</select><br>
                                <button type="submit" name="emp_update" value="' . $_POST['update'] . '">Update</button>
                        </form>');
            }
            if (isset($_POST['emp_update'])) {
                $id = $_POST['emp_update'];
                $fname = $_POST['fname'];
                $proj = $_POST['a_proj'];
                $stmt = $conn->prepare("UPDATE empl SET fname = ?, assigned_project = ? WHERE id_empl = ?");
                if (!empty($fname)) {
                    $stmt->bind_param("ssi", $fname, $proj, $id);
                    $stmt->execute();
                    $stmt->close();
                    header('Location: ' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
                    die;
                }
            }
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
                                            <button type="submit" name="update" value="' . $row["id_proj"] . '">Update</button>
                                        </form>
                                </td>
                           </tr>');
                }
            } else {
                print('No results');
            }
            print('</table>');

            print('<form class="new-entry" action="" method="POST">
                        <input type="text" name="new_project" placeholder="Enter project title" />
                        <button type="submit" name="create">Create</button>
                   </form>');

            // Update project title logic

            if (isset($_POST['update'])) {
                print('<form class="new-entry" action="" method="POST">
                                <label>Update project title:</label><br>
                                <input type="text" name="upd_title" /><br>
                                <button type="submit" name="proj_update" value="' . $_POST['update'] . '">Update</button>
                            </form>');
            }
            if (isset($_POST['proj_update'])) {
                $id = $_POST['proj_update'];
                $title = $_POST['upd_title'];
                $stmt = $conn->prepare("UPDATE proj SET title = ? WHERE id_proj = ?");
                if (!empty($title)) {
                    $stmt->bind_param("si", $title, $id);
                    $stmt->execute();
                    $stmt->close();
                    header('Location: ' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
                    die;
                }
            }
        }

        mysqli_close($conn);

        ?>
    </div>
</body>

</html>