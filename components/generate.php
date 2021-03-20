<?php
function generateOptions($queryRes)
{
    if (mysqli_num_rows($queryRes) > 0) {
        while ($row = mysqli_fetch_assoc($queryRes)) {
            echo "<option value='" . $row['title'] . "'>" . $row['title'] . "</option>";
        }
    }
}
