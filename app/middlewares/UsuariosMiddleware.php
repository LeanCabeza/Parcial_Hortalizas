<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class UsuariosMiddleware{


    public function VerificarAccesoVendedores(Request $request, RequestHandler $handler): Response{
        $dataToken = json_decode($request->getParsedBody()["dataToken"], true);
        $tipo_usuario = $dataToken['tipo'];
        $response = new Response();

        if(!isset($dataToken) || !isset($tipo_usuario)){
           $response->getBody()->write(json_encode(array("error" => "Error en los datos del Token")));
           $response = $response->withStatus(400);
        }else{
           if($tipo_usuario == "vendedor"){
              $response = $handler->handle($request);
           }else{
              $response->getBody()->write(json_encode(array("error" => "No tienes accesos, solo para vendedores.")));
              $response = $response->withStatus(401);
           }
        }
       return $response->withHeader('Content-Type', 'application/json');
   }

   public function VerificarAccesoProveedoresYVendedores(Request $request, RequestHandler $handler): Response{
      $dataToken = json_decode($request->getParsedBody()["dataToken"], true);
      $tipo_usuario = $dataToken['tipo'];
      $response = new Response();

      if(!isset($dataToken) || !isset($tipo_usuario)){
         $response->getBody()->write(json_encode(array("error" => "Error en los datos del Token")));
         $response = $response->withStatus(400);
      }else{
         if($tipo_usuario == "vendedor" || $tipo_usuario == "proveedor"){
            $response = $handler->handle($request);
         }else{
            $response->getBody()->write(json_encode(array("error" => "No tienes accesos, solo para vendedores.")));
            $response = $response->withStatus(401);
         }
      }
     return $response->withHeader('Content-Type', 'application/json');
 }

 public function VerificarAccesoProveedores(Request $request, RequestHandler $handler): Response{
   $dataToken = json_decode($request->getParsedBody()["dataToken"], true);
   $tipo_usuario = $dataToken['tipo'];
   $response = new Response();

   if(!isset($dataToken) || !isset($tipo_usuario)){
      $response->getBody()->write(json_encode(array("error" => "Error en los datos del Token")));
      $response = $response->withStatus(400);
   }else{
      if($tipo_usuario == "proveedor"){
         $response = $handler->handle($request);
      }else{
         $response->getBody()->write(json_encode(array("error" => "No tienes accesos, solo para vendedores.")));
         $response = $response->withStatus(401);
      }
   }
  return $response->withHeader('Content-Type', 'application/json');
}

public function VerificarAccesoAdmin(Request $request, RequestHandler $handler): Response{
   $dataToken = json_decode($request->getParsedBody()["dataToken"], true);
   $tipo_usuario = $dataToken['tipo'];
   $response = new Response();

   if(!isset($dataToken) || !isset($tipo_usuario)){
      $response->getBody()->write(json_encode(array("error" => "Error en los datos del Token")));
      $response = $response->withStatus(400);
   }else{
      if($tipo_usuario == "admin"){
         $response = $handler->handle($request);
      }else{
         $response->getBody()->write(json_encode(array("error" => "No tienes accesos, solo para vendedores.")));
         $response = $response->withStatus(401);
      }
   }
  return $response->withHeader('Content-Type', 'application/json');
}

}
?>