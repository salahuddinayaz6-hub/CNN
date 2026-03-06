<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logging out...</title>
</head>
<body>
    <script>
        window.location.href = 'index.php';
    </script>
</body>
</html>
 
