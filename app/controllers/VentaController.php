<?php
require_once './models/Venta.php';
require_once './models/Usuario.php';
require_once './models/Hortaliza.php';
require_once './service/Pdf.php';

class VentaController extends Venta 
{
    public function CargarUno($request, $response, $args)
    {
      //$parametros = $request->getParsedBody();
      $parametros = $request->getParsedBody()["body"];

      $id_cliente = $parametros['id_cliente'];
      $id_hortaliza = $parametros['id_hortaliza'];
      $cantidad = $parametros['cantidad'];
      $archivo = $request->getUploadedFiles();

      if (!isset($parametros) || !isset($id_cliente) || !isset($id_hortaliza) || !isset($cantidad)) {
        $payload = json_encode(array("error" => "Faltan ingresar datos."));
        $response = $response->withStatus(400);
      } else {

        $usuario = Usuario::obtenerUsuario($id_cliente);
        $hortaliza = HortalizaController::obtenerHortalizasPorId($id_hortaliza);
        $ruta = "";


        if($usuario != null && $hortaliza != null){

            if ($archivo["foto"]) {
                $destino = "./FotoHortalizas/";
                $extension = explode(".",$archivo["foto"]->getClientFileName());
                $extension = array_reverse($extension)[0];
                $urlFoto = $usuario[0]->nombre."-".$hortaliza[0]->nombre."-".date('Y-m-d').".". $extension;
                $archivoFoto = $archivo["foto"];
                $archivoFoto->moveTo($destino . $urlFoto);
                $ruta = $urlFoto;
            }
      
              $venta = new Venta();
              $venta->id_cliente = $id_cliente;
              $venta->id_hortaliza = $id_hortaliza;
              $venta->cantidad = $cantidad;
              $venta->foto = $ruta;
              $venta->crearVenta();
              $payload = json_encode(array("mensaje" => "Venta creada con exito."));
              $response = $response->withStatus(201);
        }else{
            $payload = json_encode(array("mensaje" => "Id Usuario o Id Hortaliza, INEXISTENTES"));
        }
      }
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    }

    public function TreaerVentasHortalizasClimaSeco($request, $response, $args){
      $ventas = Venta::obtenerVentasHortalizasClimaSeco();
      $response->getBody()->write(json_encode(array("Lista Ventas" => $ventas)));
    return $response->withHeader('Content-Type', 'application/json');
    }

    public function TreaerUsuariosQueCompraron($request, $response, $args){
      $hortaliza= $args['UsuariosQueCompraron'];
      $ventas = Venta::usuariosQueCompraron($hortaliza);
      $response->getBody()->write(json_encode(array("Lista Usuarios Que Compraron :".$hortaliza => $ventas)));
    return $response->withHeader('Content-Type', 'application/json');
    }

    public function ObtenerPdf($request, $response, $args)
    {
      ob_clean();
      ob_start();
      $ventas = Venta::obtenerVentas();
      $pdf = new PdfServicio();
      $pdf->SetTitle("Ventas Hortalizas");
      $pdf->AddPage();
      $pdf->Cell(150, 10, 'Ventas Hortalizas: ', 0, 1);
      foreach ($ventas as $v) {
        $pdf->Cell(150, 10, Venta::toString($v));
        $pdf->Ln();
      }
      $pdf->Output('F','PDF_VENTAS.pdf',false); 
      ob_end_flush();
  
      $payload = json_encode(array("mensaje" => "Descargado"));
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/pdf');
    }


}

?>


