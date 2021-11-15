<?php
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Handlers\Strategies\RequestHandler;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/HortalizaController.php';
require_once './controllers/VentaController.php';
require_once './service/Pdf.php';
require_once './db/AccesoDatos.php';
require_once './middlewares/AutenticacionMiddelware.php';
require_once './middlewares/UsuariosMiddleware.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


$app = AppFactory::create();
$app->setBasePath('/parcial2');
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();


$errorMiddleware = $app->addErrorMiddleware(true, true, true);


$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Parcial 2 - Leandro Cabeza");
    return $response;
});

// peticiones

// USUARIOS
$app->group('/autenticacion', function (RouteCollectorProxy $group) {
    $group->post('/login', \UsuarioController::class . ':Login');
  });


// HORTALIZAS
$app->group('/hortalizas', function (RouteCollectorProxy $group) {

// Hortaliza Crear Auth de Vendedor y Token.
  $group->post('/crear', \HortalizaController::class . ':CargarUno')
  ->add(\UsuariosMiddleware::class . ':VerificarAccesoVendedores')
  ->add(\AutenticacionMiddelware::class . ':VerificarToken');
// Hortaliza por Unidad sin AUTH
  $group->get('/tipo/{tipoUnidad}', \HortalizaController::class . ':TraerPorUnidad');
  $group->get('/clima/{clima}', \HortalizaController::class . ':TraerPorClima');
//Hortaliza por ID, cualquier usuario registrado.
  $group->get('/{id}', \HortalizaController::class . ':TraerPorId')
  ->add(\AutenticacionMiddelware::class . ':VerificarToken');
  //Borrar Por por ID, Solo Vendedor.
  $group->delete('/{id}', \HortalizaController::class . ':BorrarHortalizaPorId')
  ->add(\UsuariosMiddleware::class . ':VerificarAccesoVendedores')
  ->add(\AutenticacionMiddelware::class . ':VerificarToken');
  //Modificar Por ID, solo ADMIN
  $group->post('/modificar', \HortalizaController::class . ':ModificarPorId')
  ->add(\UsuariosMiddleware::class . ':VerificarAccesoAdmin')
  ->add(\AutenticacionMiddelware::class . ':VerificarToken');

});

// Venta
$app->group('/ventas', function (RouteCollectorProxy $group) {
  // Hortaliza Crear Auth de Vendedor y Token.
  $group->post('/crear', \VentaController::class . ':CargarUno')
  ->add(\UsuariosMiddleware::class . ':VerificarAccesoProveedoresYVendedores')
  ->add(\AutenticacionMiddelware::class . ':VerificarToken');
  // INFORME, VENTAS HORTALIZAS CLIMA SECO ENTRE FECHAS
  $group->get('/climaSeco', \VentaController::class . ':TreaerVentasHortalizasClimaSeco')
  ->add(\UsuariosMiddleware::class . ':VerificarAccesoVendedores')
  ->add(\AutenticacionMiddelware::class . ':VerificarToken');
  // INFORME, USUARIOS QUE COMPRARON
  $group->get('/UsuariosQueCompraron/{UsuariosQueCompraron}', \VentaController::class . ':TreaerUsuariosQueCompraron')
  ->add(\UsuariosMiddleware::class . ':VerificarAccesoProveedores')
  ->add(\AutenticacionMiddelware::class . ':VerificarToken');
  // INFORME, USUARIOS QUE COMPRARON
  $group->get('/pdf', \VentaController::class . ':ObtenerPdf');

});



// Run app
$app->run();

?>