<?php
//
//// Verifica se houve POST e se o usuário ou a senha é(são) vazio(s)
//if (!empty($_POST) AND (empty($_POST['user']) OR empty($_POST['pwd']))) {
//    header("Location: login.php"); exit;
//}
//
?>
<?php
//$conn_access = odbc_connect ( "bp_db", "", "");
//if ($conn_access) {
//    echo "Conectado!";
//} else {
//    echo "Erro na conexão com o banco de dados";
//}
//
//
//$user =$_POST['user'];
//$pwd = $_POST['pwd'];
//
//
////$rs=odbc_exec($conn_access,"UPDATE user set pwd ='".SHA1('matheus')."' where username = 'matheus'  ;");
////die();
//$sql = "SELECT COUNT(id) AS [numresults]  FROM user WHERE (username = '". $user ."' AND pwd = '". SHA1($pwd)."' ) ;";
//$rs=odbc_exec($conn_access,$sql);
//
//if (odbc_result($rs,"numresults") != 1) {
//// Mensagem de erro quando os dados são inválidos e/ou o usuário não foi encontrado
//    echo "Login inválido!"; exit;
//} else {
//// Salva os dados encontados na variável $resultado
//    $sql = "SELECT * FROM user WHERE (username = '". $user ."' AND pwd = '". SHA1($pwd)."' ) ;";
//    $rs=odbc_exec($conn_access,$sql);
//
//    echo odbc_result($rs,"full_name");
//}
?>
<?php

/**
 * Classe para controle de login e permissões de usuário
 *
 * @author Thiago Belem <contato@thiagobelem.net>
 * @version 1.0
 */
class User {

//connexão com o BD
    var  $connection = null;

    /**
     * Nome do banco de dados onde está a tabela de usuários
     * @var string
     */
    var $bancoDeDados = 'bp_db';

    /**
     * Nome da tabela de usuários
     * @var string
     */

    var $tabelaUsuarios = 'user';

    /**
     * Nomes dos campos onde ficam o usuário e a senha de cada usuário
     * Formato: tipo => nome_do_campo
     * @var array
     */
    var $campos = array(
    'usuario' => 'username',
    'senha' => 'pwd'
    );

    /**
     * Nomes dos campos que serão pegos da tabela de usuarios e salvos na sessão,
     * caso o valor seja false nenhum dado será consultado
     * @var mixed
     */
    var $dados = array('id', 'full_name', 'email', 'active', 'user_level', 'username', 'pwd');

    /**
     * Inicia a sessão se necessário?
     * @var boolean
     */
    var $iniciaSessao = true;

    /**
     * Prefixo das chaves usadas na sessão
     * @var string
     */
    var $prefixoChaves = 'gbp_';

    /**
     * Usa um cookie para melhorar a segurança?
     * @var boolean
     */
    var $cookie = true;

    /**
     * Armazena as mensagens de erro
     * @var string
     */
    var $erro = '';


    function __construct() {
        include_once 'MysqlConnection.class.php';
        $this->connection = new ConnectMysql();
        $this->connection->connectToMysql();
    }


    /**
     * Usa algum tipo de encriptação para codificar uma senha
     *
     * Método protegido: Só pode ser acessado por dentro da classe
     *
     * @param string $senha - A senha que será codificada
     * @return string - A senha já codificada
     */
    function __codificaSenha($senha) {
    // Altere aqui caso você use, por exemplo, o MD5:
        return SHA1($senha);
    //return $senha;
    }

    /**
     * Valida se um usuário existe
     *
     * @param string $usuario - O usuário que será validado
     * @param string $senha - A senha que será validada
     * @return boolean - Se o usuário existe ou não
     */
    function validaUsuario($usuario, $senha) {
        $senha = $this->__codificaSenha($senha);

        // Procura por usuários com o mesmo usuário e senha
        $sql = "SELECT COUNT(*) AS total
				FROM {$this->tabelaUsuarios}
        			WHERE {$this->campos['usuario']} = '{$usuario}'
                                  AND {$this->campos['senha']} = '{$senha}'";
        $query = mysql_query($sql,$this->connection->getConnection());
        if ($query) {

            $total = mysql_result($query, 0, 'total');
            //    $rs=odbc_exec($conn_access,$sql);
            //
            //    echo odbc_result($rs,"full_name");

            // Limpa a consulta da memória
            mysql_free_result($query);
        } else {
        // A consulta foi mal sucedida, retorna false
            return false;
        }

        // Se houver apenas um usuário, retorna true
        return ($total == 1) ? true : false;
    }

    /**
     * Loga um usuário no sistema salvando seus dados na sessão
     *
     * @param string $usuario - O usuário que será logado
     * @param string $senha - A senha do usuário
     * @return boolean - Se o usuário foi logado ou não
     */
    function logaUsuario($usuario, $senha) {
        if ($this->validaUsuario($usuario, $senha)) {
            if ($this->iniciaSessao AND !isset($_SESSION)) {session_start();}

            // Traz dados da tabela?
            if ($this->dados != false) {
            // Adiciona o campo do usuário na lista de dados
                if (!in_array($this->campos['usuario'], $this->dados)) {
                    $this->dados[] = 'username';
                }

                // Monta o formato SQL da lista de campos
                $dados = '' . join(', ', array_unique($this->dados)) . '';



                // Consulta os dados
                $sql = "SELECT {$dados} FROM {$this->tabelaUsuarios}
			WHERE {$this->campos['usuario']} = '{$usuario}';";

                $query = mysql_query( $sql,$this->connection->getConnection());

                // Se a consulta falhou
                if (!$query) {
                // A consulta foi mal sucedida, retorna false
                    $this->erro = 'A consulta dos dados é inválida';
                    return false;
                } else {
                // Traz os dados encontrados para um array
                    $dados = mysql_fetch_array($query);
                    // Limpa a consulta da memória
                    mysql_free_result($query);

                    // Passa os dados para a sessão
                    foreach ($dados AS $chave=>$valor) {
                        $_SESSION["$this->prefixoChaves$chave"] = $valor;

                    }
                }
            }

            // Usuário logado com sucesso
            $_SESSION[$this->prefixoChaves . 'logado'] = true;

            // Define um cookie para maior segurança?
            if ($this->cookie) {
            // Monta uma cookie com informações gerais sobre o usuário: usuario, ip e navegador
                $valor = join('#', array($usuario, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']));

                // Encripta o valor do cookie
                $valor = sha1($valor);

                setcookie($this->prefixoChaves . 'token', $valor, 0, '/');
            }

            // Fim da verificação, retorna true
            return true;
        } else {
            $this->erro = 'Usuário e/ou senha inválido(a)';
            return false;
        }
    }

    /**
     * Verifica se há um usuário logado no sistema
     *
     * @return boolean - Se há um usuário logado ou não
     */
    function usuarioLogado() {
    // Inicia a sessão?
        if ($this->iniciaSessao AND !isset($_SESSION)) {
            session_start();
        }

        // Verifica se não existe o valor na sessão
        if (!isset($_SESSION[$this->prefixoChaves . 'logado']) OR !$_SESSION[$this->prefixoChaves . 'logado']) {

            return false;
        }

        // Faz a verificação do cookie?
        if ($this->cookie) {
        // Verifica se o cookie não existe
            if (!isset($_COOKIE[$this->prefixoChaves . 'token'])) {

                return false;
            } else {
            // Monta o valor do cookie
                $valor = join('#', array($_SESSION[$this->prefixoChaves . 'username'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']));

                // Encripta o valor do cookie
                $valor = sha1($valor);

                // Verifica o valor do cookie
                //AQUI!
                if ($_COOKIE[$this->prefixoChaves . 'token'] !== $valor) {
                    return false;
                }
            }
        }

        // A sessão e o cookie foram verificados, há um usuário logado
        return true;
    }

    /**
     * Faz logout do usuário logado
     *
     * @return boolean
     */
    function logout() {
    // Inicia a sessão?
        if ($this->iniciaSessao AND !isset($_SESSION)) {
            session_start();
        }

        // Tamanho do prefixo
        $tamanho = strlen($this->prefixoChaves);

        // Destroi todos os valores da sessão relativos ao sistema de login
        foreach ($_SESSION AS $chave=>$valor) {
        // Remove apenas valores cujas chaves comecem com o prefixo correto
            if (substr($chave, 0, $tamanho) == $this->prefixoChaves) {
                unset($_SESSION[$chave]);
            }
        }

        // Destrói asessão se ela estiver vazia
        if (count($_SESSION) == 0) {
            session_destroy();

            // Remove o cookie da sessão se ele existir
            if (isset($_COOKIE['PHPSESSID'])) {
                setcookie('PHPSESSID', false, (time() - 3600));
                unset($_COOKIE['PHPSESSID']);
            }
        }

        // Remove o cookie com as informações do visitante
        if ($this->cookie AND isset($_COOKIE[$this->prefixoChaves . 'token'])) {
            setcookie($this->prefixoChaves . 'token', false, (time() - 3600), '/');
            unset($_COOKIE[$this->prefixoChaves . 'token']);
        }

        // Retorna SE não há um usuário logado
        return !$this->usuarioLogado();
    }
}

?>