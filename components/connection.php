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
