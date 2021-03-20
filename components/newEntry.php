<?php
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
