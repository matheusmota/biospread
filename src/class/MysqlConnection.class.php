<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PHPClass
 *
 * @author matheus
 */

class ConnectMysql {

    var $conn_access  = null;

    public function getConnection() {
        return $this->conn_access;
    }



    function connectToMysql() {


        $this->conn_access = mysql_connect("", "", "");
        mysql_select_db("");

        if ($this->conn_access) {

        } else {
            echo "<script>alert('Erro na conexão com o banco de dados {MysqlConnection.Class}');</script>";
            die();
        }
    }

    function disconnectFromMysql() {
        mysql_close();
    }

}
?>
