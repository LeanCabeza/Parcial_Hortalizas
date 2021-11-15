<?php
require_once './models/Hortaliza.php';
require_once './interfaces/IApiUsable.php';


class HortalizaController extends Hortaliza
{
    public function CargarUno($request, $response, $args)
    {
      $parametros = $request->getParsedBody()["body"];

      $precio = $parametros['precio'];
      $nombre = $parametros['nombre'];
      $clima = $parametros['clima'];
      $tipoUnidad = $parametros['tipoUnidad'];
      $archivo = $request->getUploadedFiles();

      if (!isset($parametros) || !isset($precio) || !isset($nombre) || !isset($clima)|| !isset($tipoUnidad)) {
        $payload = json_encode(array("error" => "Faltan ingresar datos."));
        $response = $response->withStatus(400);
      } else {

        $ruta = "";

        if ($archivo["foto"]) {
          $destino = "./FotoHortalizas/FOTOS/";
          $extension = explode(".",$archivo["foto"]->getClientFileName());
          $extension = array_reverse($extension)[0];
          $urlFoto = $nombre.".". $extension;
          $archivoFoto = $archivo["foto"];
          $archivoFoto->moveTo($destino . $urlFoto);
          $ruta = $urlFoto;
        }

        $hortaliza = new Hortaliza();
        $hortaliza->precio = $precio;
        $hortaliza->nombre = $nombre;
        $hortaliza->clima = $clima;
        $hortaliza->tipoUnidad = $tipoUnidad;
        $hortaliza->foto = $ruta;
        $hortaliza->crearHortaliza();
        $payload = json_encode(array("mensaje" => "Hortaliza creada con exito."));
        $response = $response->withStatus(201);
      }
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    }


    public function TraerPorUnidad($request, $response, $args)
    {
        $tipoUnidad = $args['tipoUnidad'];
        $lista = Hortaliza::obtenerHortalizasPorTipoUnidad($tipoUnidad);
        $payload = json_encode(array("listaHortalizas" => $lista));
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    } 

    public function TraerPorClima($request, $response, $args)
    {
        $clima = $args['clima'];
        $lista = Hortaliza::obtenerHortalizasPorClima($clima);
        $payload = json_encode(array("listaHortalizas" => $lista));
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPorId($request, $response, $args)
    {
        $id = $args['id'];
        $lista = Hortaliza::obtenerHortalizasPorId($id);
        $payload = json_encode(array("listaHortalizas" => $lista));
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarHortalizaPorId($request, $response, $args)
    {
        $id = $args['id'];
        Hortaliza::borrarPorId($id);

        $payload = json_encode(array("mensaje" => "[BAJA]: Hortaliza ".$id." borrada con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarPorId($request, $response, $args)
    {
      $parametros = $request->getParsedBody()["body"];
        $id = $parametros['id'];
        $precio = $parametros['precio'];
        $nombre = $parametros['nombre'];
        $clima = $parametros['clima'];
        $tipoUnidad = $parametros['tipoUnidad'];
        $archivo = $request->getUploadedFiles();

        if (!isset($parametros) || !isset($id) || !isset($precio) || !isset($nombre) || !isset($clima)|| !isset($tipoUnidad)) {
          $payload = json_encode(array("error" => "Faltan ingresar datos."));
          $response = $response->withStatus(400);
        } else {
  
          $ruta = "";
  
          if ($archivo["foto"]){
            $destino = "./FotoHortalizas/BACKUP/";
            $extension = explode(".",$archivo["foto"]->getClientFileName());
            $extension = array_reverse($extension)[0];
            $urlFoto = $nombre.".". $extension;
            //Si LA FOTO EXISTE, LA GUARDA EN BACKUP
            if(Hortaliza::obtenerHortalizasPorFoto($urlFoto)!= null){
              $archivoFoto = $archivo["foto"];
              $archivoFoto->moveTo($destino . $urlFoto);
              $ruta = $urlFoto;
            }else{
              //Si LA FOTO NO EXISTE, LA GUARDA EN FotoHortalizas
              $destino = "./FotoHortalizas/FOTOS/";
              $archivoFoto = $archivo["foto"];
              $archivoFoto->moveTo($destino . $urlFoto);
              $ruta = $urlFoto;
            }
          }
  
          $hortaliza = new Hortaliza();
          $hortaliza->id = $id;
          $hortaliza->precio = $precio;
          $hortaliza->nombre = $nombre;
          $hortaliza->clima = $clima;
          $hortaliza->tipoUnidad = $tipoUnidad;
          $hortaliza->foto = $ruta;
          $hortaliza->modificarHortaliza();
          $payload = json_encode(array("mensaje" => "Hortaliza modificada con exito."));
          $response = $response->withStatus(201);
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
      }




}