<?php
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
