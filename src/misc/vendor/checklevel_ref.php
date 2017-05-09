<?php
$level = $_SESSION["gbp_user_level"];
if (($level == 0)) {

    die("Desculpe, visitante, mas você não tem permissões suficientes para acessar esta função<br> Em caso de dúvida, procure o administrador do sistema.");

}

?>
