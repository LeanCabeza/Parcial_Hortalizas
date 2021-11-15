<?php

class Hortaliza
{
    public $id;
    public $precio;
    public $nombre;
    public $foto;
    public $clima;
    public $tipoUnidad;


    public function crearHortaliza()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO hortalizas (precio,nombre,foto,clima,tipoUnidad) 
                                                        VALUES (:precio,:nombre,:foto,:clima,:tipoUnidad)");
        
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->bindValue(':clima', $this->clima, PDO::PARAM_STR);
        $consulta->bindValue(':tipoUnidad', $this->tipoUnidad, PDO::PARAM_STR);
  

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerHortalizasPorTipoUnidad($tipoUnidad)
    { 
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * 
                                                        FROM hortalizas 
                                                        WHERE tipoUnidad = :tipoUnidad");
        $consulta->bindValue(':tipoUnidad', $tipoUnidad, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_OBJ);
    }

    public static function obtenerHortalizasPorClima($clima)
    { 
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * 
                                                        FROM hortalizas 
                                                        WHERE clima = :clima");
        $consulta->bindValue(':clima', $clima, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_OBJ);
    }

    public static function obtenerHortalizasPorId($id)
    { 
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * 
                                                        FROM hortalizas 
                                                        WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_OBJ);
    }

    public static function borrarPorId($id)
    { 
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE 
                                                        FROM hortalizas 
                                                        WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();
    }


    public function modificarHortaliza()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE hortalizas 
                                                        SET precio = :precio,
                                                        nombre = :nombre,
                                                        foto = :foto,
                                                        clima = :clima,
                                                        tipoUnidad = :tipoUnidad
                                                       WHERE id = :id");
        
        $consulta->bindValue(':id', $this->id, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->bindValue(':clima', $this->clima, PDO::PARAM_STR);
        $consulta->bindValue(':tipoUnidad', $this->tipoUnidad, PDO::PARAM_STR);
  

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }


    public static function obtenerHortalizasPorFoto($nombreFoto)
    { 
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * 
                                                        FROM hortalizas 
                                                        WHERE foto = :foto");
        $consulta->bindValue(':foto', $nombreFoto, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_OBJ);
    }

    




}