<?php
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
                                            <input type="hidden" name="current_project" value="' . $row['title'] . '" />
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
    print('<form class="new-entry update-entry" action="" method="POST">
                                <label>Update project title:</label><br>
                                <input type="text" name="upd_title" value="' . $_POST['current_project'] . '"/><br>
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
