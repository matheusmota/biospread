<?php
$level = $_SESSION["gbp_user_level"];
if (($level == 0) || ($level == 1)) {

    die("Desculpe, mas você não tem permissões suficientes para acessar esta função<br> Em caso de dúvida, procure o administrador do sistema.");

}

?>
