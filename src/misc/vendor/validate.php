<?


// Inclui o arquivo com a classe de login
require_once("class/User.class.php");
// Instancia a classe
$userClass = new User();

// Pega os dados vindos do formul�rio
$user = $_POST['user'];
$pwd = $_POST['pwd'];

// Tenta logar o usu�rio com os dados
if ( $userClass->logaUsuario($user, $pwd) ) {
// Usu�rio logado com sucesso, redireciona ele para a p�gina restrita
    header("Location: index.php");
    exit;
} else {
// N�o foi poss�vel logar o usu�rio, exibe a mensagem de erro
    echo "<script>alert('ERRO: ".$userClass->erro."'); </script> <META HTTP-EQUIV=\"Refresh\" CONTENT=\"0 ; URL=./login.php\">";

}


?>
