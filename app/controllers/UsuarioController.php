<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';
require_once './models/AutentificadorJWT.php';


class UsuarioController extends Usuario
{
    
    public function Login($request, $response, $args){

      $parametros = $request->getParsedBody();
      $mail = $parametros['mail'];
      $clave = $parametros['clave'];

      if( $mail == "" || $clave == ""){
        $response->getBody()->write(json_encode(array("error" => "Falta mail o clave.")));
        $response = $response->withStatus(400);
      }else{

        $usuario = Usuario::obtenerUsuarioPorMail($mail);

        if($usuario != null ){
          if(password_verify($clave,$usuario->clave)){
            $datos = json_encode(array("Id" => $usuario->id,"mail"=> $usuario->mail, "tipo" => $usuario->tipo));
            $token = AutentificadorJWT::CrearToken($datos);
            $response->getBody()->write(json_encode(array("token" => $token)));
          }else{
          $response->getBody()->write(json_encode(array("error" => "Ocurrio un error, password incorrecto.")));
          $response = $response->withStatus(400);
        }        
        }else{
          $response->getBody()->write(json_encode(array("error" => "Ocurrio un error al generar el token, Usuario Inexistente.")));
          $response = $response->withStatus(400);
        }
      }
      return $response->withHeader('Content-Type', 'application/json');
    }




}