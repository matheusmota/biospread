<?php
$level = $_SESSION["gbp_user_level"];
if (($level == 0) || ($level == 1)) {

    die("Desculpe, mas voc� n�o tem permiss�es suficientes para acessar esta fun��o<br> Em caso de d�vida, procure o administrador do sistema.");

}

?>
