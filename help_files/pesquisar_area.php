<?php
    if (basename(__FILE__, '') === basename($_SERVER['SCRIPT_FILENAME'], '')) {
        echo "<script>window.location.href = '../erro.php?msg_id=1'</script>";
    } else {
        echo '
        <form action="pesquisar.php" method="POST" id="searchForm">
            <input type="search" id="searchInput" class="searchField" name="search" placeholder="Pesquisar...">
            <input type="text" name="pesquisaTp" id="pesquisaTp" hidden>
            <div onclick="this.parentElement.submit()" style="cursor: pointer;" class="submitBtn"><i style="margin: 0" class="fa-solid fa-magnifying-glass"></i></a></div>
        </form>';
    }
?>