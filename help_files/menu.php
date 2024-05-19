<?php
    if (basename(__FILE__, '') === basename($_SERVER['SCRIPT_FILENAME'], '')) {
        echo "<script>window.location.href = '../erro.php?msg_id=1'</script>";
    }
?>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar nav_inicial">
        <div class="navbar_left navbar_section">
            <?php
                @$login = $_COOKIE['login'];
                $login_data = explode(",", $login);
                echo "<a href='index.php'>Sistema de Controle SENAI</a>";
                if (isset($login)) {
                    if($login_data[3] === "escolas"){
                        echo '
                            <div class="nav_pages">
                                <a href="cadastro_empresa.php" class="nav_page">Cadastrar Empresa</a>
                                <a href="empresas.php" class="nav_page">Listar Empresas</a>
                                <a href="enviar_relatorio.php" class="nav_page">Enviar Relatórios</a>
                                <a href="relatorios.php" class="nav_page">Listar Relatórios</a>
                                <a href="alunos.php" class="nav_page">Listar Alunos</a>
                                <a href="solicitacoes.php" class="nav_page">Listar Solicitações</a>
                            </div>
                        </div>';
                    } else if($login_data[3] === "empresas"){
                        echo '
                            <div class="nav_pages">
                                <a href="relatorios.php" class="nav_page">Listar Relatórios</a>
                                <a href="alunos.php" class="nav_page">Listar Alunos</a>
                                <a href="solicitacoes.php" class="nav_page">Listar Atestados</a>
                            </div>
                        </div>';
                    } else if($login_data[3] === "alunos"){
                        echo '
                            <div class="nav_pages">
                                <a href="solicitacoes.php" class="nav_page">Ver Solicitações/Justificativas</a>
                                <a href="enviar_justificativa.php" class="nav_page">Enviar Justificativa de Falta</a>
                                <a href="solicitar_documento.php" class="nav_page">Solicitar Documento</a>
                            </div>
                        </div>';
                    }
                    echo '
                    </div>
                    <div class="navbar_right navbar_section" id="searchDiv">
                        <a href="usuario.php" class="btnUser"><i class="fa-regular fa-user"></i>'. (explode(" ", $login_data[1])[0]) .' </a>
                        <a href="logout.php" class="btnLogin">Sair</a>
                    </div>';
                } else {
                    echo '
                    </div>
                    <div class="navbar_right navbar_section">
                        <a href="login.php" class="btnLogin">Login</a>
                    </div>';
                };
            ?>
    </nav>
</body>
</html>