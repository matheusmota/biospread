<?php
//
//// Verifica se houve POST e se o usu�rio ou a senha �(s�o) vazio(s)
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
//    echo "Erro na conex�o com o banco de dados";
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
//// Mensagem de erro quando os dados s�o inv�lidos e/ou o usu�rio n�o foi encontrado
//    echo "Login inv�lido!"; exit;
//} else {
//// Salva os dados encontados na vari�vel $resultado
//    $sql = "SELECT * FROM user WHERE (username = '". $user ."' AND pwd = '". SHA1($pwd)."' ) ;";
//    $rs=odbc_exec($conn_access,$sql);
//
//    echo odbc_result($rs,"full_name");
//}
?>
<?php

/**
 * Classe para controle de login e permiss�es de usu�rio
 *
 * @author Thiago Belem <contato@thiagobelem.net>
 * @version 1.0
 */
class User {

//connex�o com o BD
    var  $connection = null;

    /**
     * Nome do banco de dados onde est� a tabela de usu�rios
     * @var string
     */
    var $bancoDeDados = 'bp_db';

    /**
     * Nome da tabela de usu�rios
     * @var string
     */

    var $tabelaUsuarios = 'user';

    /**
     * Nomes dos campos onde ficam o usu�rio e a senha de cada usu�rio
     * Formato: tipo => nome_do_campo
     * @var array
     */
    var $campos = array(
    'usuario' => 'username',
    'senha' => 'pwd'
    );

    /**
     * Nomes dos campos que ser�o pegos da tabela de usuarios e salvos na sess�o,
     * caso o valor seja false nenhum dado ser� consultado
     * @var mixed
     */
    var $dados = array('id', 'full_name', 'email', 'active', 'user_level', 'username', 'pwd');

    /**
     * Inicia a sess�o se necess�rio?
     * @var boolean
     */
    var $iniciaSessao = true;

    /**
     * Prefixo das chaves usadas na sess�o
     * @var string
     */
    var $prefixoChaves = 'gbp_';

    /**
     * Usa um cookie para melhorar a seguran�a?
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
     * Usa algum tipo de encripta��o para codificar uma senha
     *
     * M�todo protegido: S� pode ser acessado por dentro da classe
     *
     * @param string $senha - A senha que ser� codificada
     * @return string - A senha j� codificada
     */
    function __codificaSenha($senha) {
    // Altere aqui caso voc� use, por exemplo, o MD5:
        return SHA1($senha);
    //return $senha;
    }

    /**
     * Valida se um usu�rio existe
     *
     * @param string $usuario - O usu�rio que ser� validado
     * @param string $senha - A senha que ser� validada
     * @return boolean - Se o usu�rio existe ou n�o
     */
    function validaUsuario($usuario, $senha) {
        $senha = $this->__codificaSenha($senha);

        // Procura por usu�rios com o mesmo usu�rio e senha
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

            // Limpa a consulta da mem�ria
            mysql_free_result($query);
        } else {
        // A consulta foi mal sucedida, retorna false
            return false;
        }

        // Se houver apenas um usu�rio, retorna true
        return ($total == 1) ? true : false;
    }

    /**
     * Loga um usu�rio no sistema salvando seus dados na sess�o
     *
     * @param string $usuario - O usu�rio que ser� logado
     * @param string $senha - A senha do usu�rio
     * @return boolean - Se o usu�rio foi logado ou n�o
     */
    function logaUsuario($usuario, $senha) {
        if ($this->validaUsuario($usuario, $senha)) {
            if ($this->iniciaSessao AND !isset($_SESSION)) {session_start();}

            // Traz dados da tabela?
            if ($this->dados != false) {
            // Adiciona o campo do usu�rio na lista de dados
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
                    $this->erro = 'A consulta dos dados � inv�lida';
                    return false;
                } else {
                // Traz os dados encontrados para um array
                    $dados = mysql_fetch_array($query);
                    // Limpa a consulta da mem�ria
                    mysql_free_result($query);

                    // Passa os dados para a sess�o
                    foreach ($dados AS $chave=>$valor) {
                        $_SESSION["$this->prefixoChaves$chave"] = $valor;

                    }
                }
            }

            // Usu�rio logado com sucesso
            $_SESSION[$this->prefixoChaves . 'logado'] = true;

            // Define um cookie para maior seguran�a?
            if ($this->cookie) {
            // Monta uma cookie com informa��es gerais sobre o usu�rio: usuario, ip e navegador
                $valor = join('#', array($usuario, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']));

                // Encripta o valor do cookie
                $valor = sha1($valor);

                setcookie($this->prefixoChaves . 'token', $valor, 0, '/');
            }

            // Fim da verifica��o, retorna true
            return true;
        } else {
            $this->erro = 'Usu�rio e/ou senha inv�lido(a)';
            return false;
        }
    }

    /**
     * Verifica se h� um usu�rio logado no sistema
     *
     * @return boolean - Se h� um usu�rio logado ou n�o
     */
    function usuarioLogado() {
    // Inicia a sess�o?
        if ($this->iniciaSessao AND !isset($_SESSION)) {
            session_start();
        }

        // Verifica se n�o existe o valor na sess�o
        if (!isset($_SESSION[$this->prefixoChaves . 'logado']) OR !$_SESSION[$this->prefixoChaves . 'logado']) {

            return false;
        }

        // Faz a verifica��o do cookie?
        if ($this->cookie) {
        // Verifica se o cookie n�o existe
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

        // A sess�o e o cookie foram verificados, h� um usu�rio logado
        return true;
    }

    /**
     * Faz logout do usu�rio logado
     *
     * @return boolean
     */
    function logout() {
    // Inicia a sess�o?
        if ($this->iniciaSessao AND !isset($_SESSION)) {
            session_start();
        }

        // Tamanho do prefixo
        $tamanho = strlen($this->prefixoChaves);

        // Destroi todos os valores da sess�o relativos ao sistema de login
        foreach ($_SESSION AS $chave=>$valor) {
        // Remove apenas valores cujas chaves comecem com o prefixo correto
            if (substr($chave, 0, $tamanho) == $this->prefixoChaves) {
                unset($_SESSION[$chave]);
            }
        }

        // Destr�i asess�o se ela estiver vazia
        if (count($_SESSION) == 0) {
            session_destroy();

            // Remove o cookie da sess�o se ele existir
            if (isset($_COOKIE['PHPSESSID'])) {
                setcookie('PHPSESSID', false, (time() - 3600));
                unset($_COOKIE['PHPSESSID']);
            }
        }

        // Remove o cookie com as informa��es do visitante
        if ($this->cookie AND isset($_COOKIE[$this->prefixoChaves . 'token'])) {
            setcookie($this->prefixoChaves . 'token', false, (time() - 3600), '/');
            unset($_COOKIE[$this->prefixoChaves . 'token']);
        }

        // Retorna SE n�o h� um usu�rio logado
        return !$this->usuarioLogado();
    }
}

?>