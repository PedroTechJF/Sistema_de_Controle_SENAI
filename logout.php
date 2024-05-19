<?php
    if (isset($_COOKIE['login'])) {
        unset($_COOKIE['login']);
        setcookie('login', '', -1);
        echo "<script>window.location.href = 'index.php'</script>";
    } else {
        echo "<script>window.location.href = 'login.php'</script>";
    }
?>