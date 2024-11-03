admin page
<?php

use app\models\UserRole;

echo UserRole::get() ?? "Null";
?>

<br>
<?php
var_export(UserRole::getAllRoles());
?>
<a href="/home">home</a>