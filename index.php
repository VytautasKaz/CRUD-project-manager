<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <header>
        <a href="?path=projects/">Projects</a>
        <a href="?path=employees/">Employees</a>
    </header>



    <div class="container">
        <?php

        // Establishing connection to mysql server

        require('./components/connection.php');

        // Generates dropdown options of existing projects

        require('./components/generate.php');

        // Delete button logic

        require('./components/del.php');

        // Add employee/project logic

        require('./components/newEntry.php');

        // Generating table for employees/projects

        if ($_GET['path'] == 'employees/') {
            require('./components/emp.php');
        } else {
            require('./components/proj.php');
        }

        mysqli_close($conn);
        ?>
    </div>
</body>

</html>