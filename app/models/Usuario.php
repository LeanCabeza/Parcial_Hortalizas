<?php

class Usuario
{
    public $id;
    public $mail;
    public $tipo;
    public $clave;


    public static function obtenerUsuario($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT *
                                                        FROM usuarios 
                                                        WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuarioPorMail($mail)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT *
                                                        FROM usuarios 
                                                        WHERE mail = :mail");
        $consulta->bindValue(':mail',$mail, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchObject('Usuario');
    }

}