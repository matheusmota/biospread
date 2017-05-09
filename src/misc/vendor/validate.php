<?


// Inclui o arquivo com a classe de login
require_once("class/User.class.php");
// Instancia a classe
$userClass = new User();

// Pega os dados vindos do formulário
$user = $_POST['user'];
$pwd = $_POST['pwd'];

// Tenta logar o usuário com os dados
if ( $userClass->logaUsuario($user, $pwd) ) {
// Usuário logado com sucesso, redireciona ele para a página restrita
    header("Location: index.php");
    exit;
} else {
// Não foi possível logar o usuário, exibe a mensagem de erro
    echo "<script>alert('ERRO: ".$userClass->erro."'); </script> <META HTTP-EQUIV=\"Refresh\" CONTENT=\"0 ; URL=./login.php\">";

}


?>
