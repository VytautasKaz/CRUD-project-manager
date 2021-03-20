<?php
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
                                            <input type="hidden" name="current_name" value="' . $row['fname'] . '" />
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
    print('<form class="new-entry update-entry" action="" method="POST">
                            <label>Update name:</label><br>
                            <input type="text" name="fname" value="' . $_POST['current_name'] . '"/><br>
                            <label>Reassign project:</label><br>
                            <select name="a_proj">
                                <option></option>');

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
