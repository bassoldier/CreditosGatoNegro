<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

use App\Models\Venta;
use App\Models\Vendedor;
use App\Models\Cliente;
use App\Models\Adicional;
use App\Models\Producto;
use App\Models\Cuota;
use App\Models\DeudaMensual;
use App\Models\Recibe;
use Storage;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ventas = DB::table('ventas')->join('clientes', 'ventas.idCliente', '=', 'clientes.idCliente')->join('vendedores', 'ventas.idvendedor', '=', 'vendedores.idvendedor')->orderby('fechaHoraVenta', 'ASC')->get();
        $vendedores = DB::table('vendedores')->get();
        $clientes = DB::table('clientes')->get();
        $adicionales = DB::table('adicionales')->get();
        return view('ventas', ['ventas' => $ventas, 'vendedores'=> $vendedores, 'clientes'=> $clientes, 'adicionales'=> $adicionales]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            try {
                $idCliente=null;
                $idVendedor=null;

                $boleta = DB::table('ventas')->where('numeroBoletaVenta', $request->get('nboleta'))->first();
                if($boleta){
                    return redirect()->back()->with('fail', 'La venta no se procesó. El número de boleta ya se encuentra registrado.'); 
                }

                //INGRESAMOS UNA NUEVA VENTA
                $venta = new Venta;
                $venta->fechaHoraVenta = $request->get('fechaHoraVenta');
                $venta->numeroBoletaVenta = $request->get('nboleta');
                $venta->montoOriginalVenta = $request->get('montoVenta');
                $venta->montoPostInteresVenta = $request->get('deudaFinalVenta');
                $venta->montoPieVenta = $request->get('montoPieVenta');
                $venta->numeroDeCuotasVenta = $request->get('nCuotasVenta');
                $venta->factorInteresVenta = $request->get('interesVenta');
                $venta->valorCuotaVenta = $request->get('valorCuotaVenta');
                $venta->comentarioVenta = $request->get('comentarioVenta');
                $venta->estadoVenta = 0;
                
                //RESCATAMOS ALGUNOS DATOS NECESARIOS DE LA TABLA CLIENTES
                $clientes = Cliente::where('rutCliente', $request->get('rutTitularVenta'))->get();
                foreach ($clientes as $cliente) {
                    $idCliente=$cliente->idCliente;
                    $primeraFechaFacturacionCliente = $cliente->fechaFacturacionCliente;
                    $primeraFechaPagoCliente = $cliente->fechaPagoCliente;
                }

                $venta->idCliente = $idCliente;

                //RESCATAMOS ALGUNOS DATOS NECESARIOS DE LA TABLA VENDEDORES
                $vendedores = Vendedor::where('rutVendedor', $request->get('vendedorVenta'))->get();
                foreach ($vendedores as $vendedor) {
                    $idVendedor=$vendedor->idVendedor;
                }
                $venta->idVendedor = $idVendedor;

                //GUARDAMOS LA VENTA
                $statusVenta=$venta->save();

                //GUARDAMOS TODOS LOS PRODUCTOS QUE SE INGRESARON EN LA VENTA
                $inputProducto="producto1";
                $statusProducto = true;
                $i=1;

                if($statusVenta){   
                    while($request->filled($inputProducto) and $statusProducto){
                        $producto = new Producto;

                        $producto->nombreProducto = $request->get($inputProducto);
                        $producto->idVenta = $venta->idVenta;
                        $statusProducto=$producto->save();
                        
                        if(!$statusProducto){
                            return redirect()->back()->with('fail', 'Uno o varios productos no se han registrado, compruebe si los productos se encuentran en los detalles de la venta.'); 
                        }

                        $inputProducto = trim($inputProducto, $i);
                        $i++;
                        $inputProducto = $inputProducto.$i;
                        echo $i;
                    }
                }

                //-----COMENZAMOS A OBTENER ALGUNAS FECHAS NECESARIAS PARA COMENZAR A INGRESAR Y/O CREAR LAS DEUDAS Y CUOTAS ASOCIADAS AL CLIENTE------//

                //OBTENEMOS LA FECHA DE HOY
                $hoy = getdate();
                $fechaActualStr= $hoy["year"]."-".$hoy["mon"]."-".$hoy["mday"];

                //OBTENEMOS LA FECHA DE FACTURACIÓN ACTUALIZADA AL MES ACTUAL (POR ESO MOD, PORQUE ES LA FECHA DE FAC. MODIFICADA)
                $primeraFechaFacturacionAux = date($primeraFechaFacturacionCliente);
                list($primeraAnoAux, $primerMesAux, $primerDiaAux) = explode('-', $primeraFechaFacturacionAux);
                $fechaFacturacionModStr= $hoy["year"]."-".$hoy["mon"]."-".$primerDiaAux;

                //OBTENEMOS LA FCHA DE PAGO ACTUALIZADA AL MES ACTUAL, COMO EN LA SECCIÓN ANTERIOR
                $primeraFechaPagoAux = date($primeraFechaPagoCliente);
                list($primeraAnoPagoAux, $primerMesPagoAux, $primerDiaPagoAux) = explode('-', $primeraFechaPagoAux);
                $fechaPagoModStr= $hoy["year"]."-".$hoy["mon"]."-".$primerDiaPagoAux;


                //CONVERTIMOS TODAS LAS FECHAS OBTENIDAS A TIME PARA PODER COMPARARLAS
                $fecha_actual = strtotime($fechaActualStr);
                $fecha_facturacion_mod = strtotime($fechaFacturacionModStr);
                $fecha_pago_mod = strtotime($fechaPagoModStr);

                //COMPARAMOS LA FECHA ACTUAL A LA FECHA DE FACTURACION MODIFICADA, SI LA FECHA ACTUAL ES MAYOR, ENTONCES SE VA A FACTURAR EL PRÓXIMO MES
                $mesInt1 = (int) $hoy["mon"];
                $anoInt1 = (int) $hoy["year"];

                if($fecha_actual > $fecha_facturacion_mod){


                    $mesInt1++;
                    if($mesInt1==13){
                        $mesInt1=1;
                        $anoInt1++;
                    }

                    $fechaFacturacionModStr=$anoInt1."-".$mesInt1."-".$primerDiaAux;
                }

                //VOLVEMOS A OBTEER TIME DE FECHA DE FACTURACION MODIFICADA, ESTO EN CASO DE QUE SE HAYA CAMBIADO EN EL IF ANTERIOR
                $fecha_facturacion_mod = strtotime($fechaFacturacionModStr);

                //COMPARAMOS LA FECHA DE FACTURACION MODIFICADA CON LA FECHA DE PAGO MODIFICADA, LA FECHA DE PAGO DEBE SER MAYOR A LA FECHA DE FAC, SI ESTO NO ES ASÍ, ENTONCES SE MODIFICA KA FECHA DE PAGO PARA QUE EL QUEDE DESPUÉS DE LA FECHA DE FACTURACIÓN
                $mesInt2 = (int) $hoy["mon"];
                $anoInt2 = (int) $hoy["year"];

                if($fecha_facturacion_mod > $fecha_pago_mod){

                    $mesInt2++;
                    if($mesInt2==13){
                        $mesInt2=1;
                        $anoInt2++;
                    }

                    $fechaPagoModStr=$anoInt2."-".$mesInt2."-".$primerDiaPagoAux;
                }

                //COMENZAMOS A CREAR Y/O ACTUALZAR LAS DEUDAS Y CUOTAS ASOCIADAS AL CLIENTE

                for($i=1; $i <= (int) $request->get('nCuotasVenta'); $i++){
                    $deudaMensual = DeudaMensual::where('idCliente', $idCliente)->where('mesCorrespondienteDeudaMensual', $fechaFacturacionModStr)->first();
                    if(!$deudaMensual){
                        $deudaMensual = new DeudaMensual;
                        $deudaMensual->montoDeudaMensual = 0;
                        $deudaMensual->fechaVencimientoDeudaMensual = $fechaPagoModStr;
                        $deudaMensual->fechaActualizacionDeudaMensual = NULL;
                        $deudaMensual->mesCorrespondienteDeudaMensual = $fechaFacturacionModStr;
                        $deudaMensual->montoMoraDeudaMensual = 0;
                        $deudaMensual->idCliente = $idCliente;
                        $deudaMensual->save();
                    }

                    $cuota = new Cuota;
                    $cuota->montoCuota = (int) $request->get('valorCuotaVenta');
                    $cuota->mesCorrespondenciaCuota = $fechaPagoModStr;
                    $cuota->idVenta = $venta->idVenta;
                    $cuota->idDeudaMensual = $deudaMensual->idDeudaMensual;
                    $cuota->save();

                    $idDeuda=$deudaMensual->idDeudaMensual;
                    $deudaMensual->montoDeudaMensual= (int) $deudaMensual->montoDeudaMensual + (int) $request->get('valorCuotaVenta');
                    $deudaMensual->save();

                    /*echo "La cuota número: ".$i." se va a facturar el: ".$fechaFacturacionModStr." y su fecha de pago es: ".$fechaPagoModStr. " la deuda correspondiente es el que posee la id: ".$idDeuda. "con la deuda ahora de: ".$deudaMensual->montoDeudaMensual;*/

                    $mesInt1++;
                    if($mesInt1==13){
                        $mesInt1=1;
                        $anoInt1++;
                    }

                    $fechaFacturacionModStr=$anoInt1."-".$mesInt1."-".$primerDiaAux;

                    $mesInt2++;
                    if($mesInt2==13){
                        $mesInt2=1;
                        $anoInt2++;
                    }

                    $fechaPagoModStr=$anoInt2."-".$mesInt2."-".$primerDiaPagoAux;
                }

                //SI LA COMPRA FUE HECHA POR UN ADICIONAL SE LLENA LA TABLA RECIBE

                $adicional = Adicional::where('rutAdicional', $request->get('rutVenta'))->first();
            
                if($adicional){
                    $recibe = new Recibe;
                    $recibe->idVenta = $venta->idVenta;
                    $recibe->idAdicional = $adicional->idAdicional;
                    $recibe->save();
                }

                //ACTUALIZAMOS LA DEUDA TOTAL DEL CLIENTE
                $deudatotal=0;
                $deudaMensual = DeudaMensual::where('idCliente', $idCliente)->get();
                foreach ($deudaMensual as $deuda) {
                    $deudatotal=$deudatotal + (int) $deuda->montoDeudaMensual;
                }
                $cliente = Cliente::find($idCliente);
                $cliente->deudaTotalCliente = $deudatotal;
                $cliente->save();

                $this->crearPDFVenta($request, $venta->idVenta);

                return redirect()->back()->with('printSuccess', 'La venta ha sido registrada con éxito. Imprimiendo boleta'); 

        }catch (\Illuminate\Database\QueryException $e) {
            report($e);

            return redirect()->back()->with('fail', 'Ha ocurrido un error de base de datos, la venta no se ha registrado');
        }
        catch (\Exception $e) {
            report($e);

            return redirect()->back()->with('fail', 'Ha ocurrido un error, la venta no se ha registrado');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ventas = DB::table('ventas')->join('clientes', 'ventas.idCliente', '=', 'clientes.idCliente')->join('vendedores', 'ventas.idvendedor', '=', 'vendedores.idvendedor')->where('idVenta',$id)->get();
        $recibe = DB::table('recibe')->join('adicionales', 'recibe.idAdicional', '=', 'adicionales.idAdicional')->where('idVenta', $id)->first();
        if($recibe){
            $rutCliente= $recibe->rutAdicional;
            $nombreCliente=$recibe->nombreAdicional." ".$recibe->apellidoPatAdicional." ".$recibe->apellidoMatAdicional;
            
        }else{
            foreach ($ventas as $venta) {
                $rutCliente=$venta->rutCliente;
                $nombreCliente=$venta->nombreCliente." ".$venta->apellidoPatCliente." ".$venta->apellidoMatCliente;
            }
        }

        foreach ($ventas as $venta) {
            return response()->json([
                'fechaHoraVenta'=>$venta->fechaHoraVenta,
                'numeroBoletaVenta'=>$venta->numeroBoletaVenta,
                
                'deudaFinalVenta'=>$venta->montoPostInteresVenta,
                'rutVendedor'=>$venta->rutVendedor,

                'rutCliente'=>$rutCliente,
                'nombreCliente'=>$nombreCliente,
                'rutTitular'=>$venta->rutCliente,
                'nombreTitular'=>$venta->nombreCliente." ".$venta->apellidoPatCliente." ".$venta->apellidoMatCliente,

                'comentarioVenta'=>$venta->comentarioVenta,
                'montoOriginalVenta'=>$venta->montoOriginalVenta,
                'montoPieVenta'=>$venta->montoPieVenta,
                'nCuotasVenta'=>$venta->numeroDeCuotasVenta,
                'factorInteresVenta'=>$venta->factorInteresVenta,
                'valorCuotaVenta'=>$venta->valorCuotaVenta,
                'fechadePago'=>$venta->fechaPagoCliente,
                'fechadeFacturacion'=>$venta->fechaFacturacionCliente,
                'estadoVenta' =>$venta->estadoVenta,
                'notaCredito' =>$venta->notaCreditoVenta
        ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       /* $venta = Venta::where('idVenta', $id)->first();
        
        //GUARDAMOS LOS DANTOS ANTIGUOS (OLD)
        $montoOriginalVentaOLD = $venta->montoOriginalVenta;
        $montoPostInteresVentaOLD = $venta->montoPostInteresVenta;
        $numeroDeCuotasVentaOLD = $venta->numeroDeCuotasVenta;
        $factorInteresVentaOLD = $venta->factorInteresVenta;
        $valorCuotaVentaOLD = $venta->valorCuotaVenta;
        $idClienteOLD = $venta->idCliente;
        $idvendedorOLD = $venta->idvendedor;

        $hoy = getdate();
        $fechaActualStr= $hoy["year"]."-".$hoy["mon"]."-".$hoy["mday"];
        $fecha_actual = strtotime($fechaActualStr);

        $cuotas = Cuota::where('idVenta', $id)->get();
        foreach ($cuotas as $cuota) {
            $deudaMensual = deudaMensual::where('idDeudaMensual', $cuota->idDeudaMensual)->first();
            $fecha_pago = strtotime($deudaMensual->fechaVencimientoDeudaMensual);
            if($fecha_actual>$fecha_pago){
                return redirect()->back()->with('fail', 'Imposible actualizar venta. Una o varias cuotas asociadas ya se encuentran vencidas');
            }
        }


        /*if((int) $request->get('valorCuotaVentaDet') < (int) $valorCuotaVentaOLD){
            $diferenciaCuota = (int) $valorCuotaVentaOLD - (int) $request->get('valorCuotaVentaDet');
            $flag = 0; //la diferencia se debe restar

        }else{
            $diferenciaCuota = (int) $request->get('valorCuotaVentaDet') - (int) $valorCuotaVentaOLD;
            $flag = 1; //la diferencia se debe sumar
        }

        if()





        //ACTUALIZAMOS LO QUE SEA IRRELEVANTE A NIVEL DE CAMBIOS
        //$venta->numeroBoletaVenta=$request->get('nboletaDet');
        $vendedores = Vendedor::where('rutVendedor', $request->get('vendedorVentaDet'))->get();
        foreach ($vendedores as $vendedor) {
            $idVendedor=$vendedor->idVendedor;
        }
        $venta->idVendedor = $idVendedor;
        $venta->montoOriginalVenta = $request->get('montoVentaDet');
        $venta->montoPostInteresVenta = $request->get('deudaFinalVentaDet');
        
        $venta->numeroDeCuotasVenta = $request->get('nCuotasVentaDet');
        $venta->factorInteresVenta = $request->get('interesVentaDet');    
        $venta->valorCuotaVenta = $request->get('valorCuotaVentaDet');     
        $venta->comentarioVenta = $request->get('comentarioVentaDet');
        $venta->save();*/         
    }

    /**
     * Remove the specified resource from storage.
     *@param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $montoAux=0;
        $montoTotal=0;
        $venta = Venta::where('idVenta', $id)->first();
        $cuotas = Cuota::where('idVenta', $id)->get();
        $cliente = Cliente::where('idCliente', $venta->idCliente)->first();

        //COMPROBAMOS QUE NO HAYAN OTRAS ANUALCIONE SCON LA MISMA NOTA DE CRÉDITO
        $ventaNotaCredito = Venta::where('notaCreditoVenta', $request->get('numeroNotaCredito'))->first();
        if($ventaNotaCredito){
           return redirect()->back()->with('fail', 'El número de nota de crédtio ya existe, la venta no fue anulada'); 
        }

        foreach ($cuotas as $cuota) {
            $deudaMensual = DeudaMensual::where('idDeudaMensual', $cuota->idDeudaMensual)->first();
            if((int) $cuota->montoCuota > (int) $deudaMensual->montoDeudaMensual){
                $deudaMensual->montoDeudaMensual= 0;
                $montoTotal= $montoTotal + (int) $cuota->montoCuota - (int) $deudaMensual->montoDeudaMensual;
                
            }else{
                $montoAux=(int) $deudaMensual->montoDeudaMensual - (int) $cuota->montoCuota;
                $deudaMensual->montoDeudaMensual=$montoAux;
                $montoTotal= $montoTotal + (int) $cuota->montoCuota;
                
            } 
            $deudaMensual->save();
            $cuota->delete();
        }
        $venta->estadoVenta=1;
        $cliente->deudaTotalCliente = (int) $cliente->deudaTotalCliente - $montoTotal;
        $cliente->save();

        $venta->notaCreditoVenta=$request->get('numeroNotaCredito');

        $venta->save();
        return redirect()->back()->with('success', 'La venta ha sido anulada con éxito'); 
    }





    public function revisionBloqueo($id)
    {
        $venta = DB::table('ventas')->join('clientes', 'ventas.idCliente', '=', 'clientes.idCliente')->where('idVenta', $id)->first();
        return response()->json([
                'mora'=>$venta->morosoCliente,
                'bloqueo'=>$venta->bloqueoCliente
        ]);
    }

    public function revisionBoleta($nBoleta)
    {
        $venta = DB::table('ventas')->where('numeroBoletaVenta', $nBoleta)->first();
        if($venta){
            return response()->json([
                'flag'=>1
            ]); 
        }else{
            return response()->json([
                'flag'=>0
            ]); 
        }
    }

    public function crearPDFVenta(Request $request, $idVenta)
    {
        $cuotas=DB::table('cuotas')->join('deuda_mensual', 'cuotas.idDeudaMensual', '=', 'deuda_mensual.idDeudaMensual')->where('idVenta', $idVenta)->orderby('fechaVencimientoDeudaMensual', 'ASC')->get();

        $datosCliente = Adicional::where('rutAdicional', $request->get('rutVenta'))->first();
            
        if($datosCliente){
            $fono=$datosCliente->telefonoAdicional;
            $domicilio=$datosCliente->direccionAdicional;

        }
        else{
            $datosCliente = Cliente::where('rutCliente', $request->get('rutVenta'))->first();
            $fono=$datosCliente->telefonoCliente;
            $domicilio=$datosCliente->direccionCliente;
        }

        

        $inputProducto="producto1";
        $statusProducto = true;
        $i=1;
        $c=1;


        
            $html=" <html lang='es'>
                <head>
                <style type='text/css'>

            h1{
                font-size: 20px;
                margin-top: 0px;
                margin-bottom: 0px;
            }

            h2{
                font-size: 14px;
                margin-top: 0px;
                margin-bottom: 0px;
            }

            body{
                /* 
                Sólo para referencia del tamaño de la página.
                border: solid 1px; 
                */

            }

            .seccion{
                width: 100%;
                float: left;
            }

            #imagenGato{
                float: left;
                display: inline-block;
            }

            #textoTitulo{
                /*float: left;*/
            }

            #datosCliente{
                width: 100%;
                margin-top: 30px;
                float: left;
            }

            #tablaEncabezado tr td{
                font-size: 10px;
            }

            .w100{
                width: 100%;
            }

            .w80{
                width: 80%;
            }

            .w60{
                width: 60%;
            }

            .w40{
                width: 40%;
            }

            .w30{
                width: 30%;
            }

            .centrarTexto{
                text-align: center;
            }

            .izquierda{
                float: left;
            }

            .derecha{
                float: right;
            }

            .centrar{
                margin: auto;
            }

            .margenIzquierdo25{
                margin-left: 25%;
            }

            .margenIzquierdo10{
                margin-left: 10%;
            }

            .margenDerecho10{
                margin-right: 10%;
            }

            .margenArriba25{
                margin-top: 25px;
            }
            .margenArriba70{
                margin-top: 70px;
            }
            .bordeArriba{
                border-top: solid 1px;
            }

            #tablaCuotas{
                border-collapse: collapse;
            }

            #tablaCuotas tr td{
                border: solid 1px;
            }


            #tablaDescripcionProductos{
                border-collapse: collapse;
            }
            
            #tablaCuotas2{
                border-collapse: collapse;
            }
            
            #tablaCuotas2 tr td{
                border: solid 1px;
            }

            </style>
                </head>
        <body>

        <div id='divPrincipal'>
        <!-- Encabezado -->
            <div class='seccion'>
                <table style='width: 100%;'>
                <tr>
                
                <td id='idTd1' style='width:45%;' >
                <table style='width:100%; '>
                    <tr>
                    <td>
                        <div id='imagenGato'>
                        <img src='".asset('imgPDFs/imagen_gato.png')."'>
                        </div>
                    </td>
                    <td>
                        <div id='textoTitulo'>
                    <h2>Tienda</h2>
                    <h1>El Gato Negro</h1>
                    <table id='tablaEncabezado'>
                        <tr>
                            <td colspan='2'>VENTA DE LANAS, HILOS, TELAS, ELECTROD</td>
                        </tr>
                        <tr>
                            <td colspan='2'>Y ELECTRONIC, MUEBLES Y OTROS</td>
                        </tr>
                        <tr>
                            <td colspan='2'>SOCIEDAD COMERCIAL CONA Y SUAZO LTDA</td>
                        </tr>
                        <tr>
                            <td>RUT:</td>
                            <td>76.312.498-3</td>
                        </tr>
                        <tr>
                            <td>FONO:</td>
                            <td>2178791 - 2178792</td>
                        </tr>
                    </table>
                </div>
                    
                    </td>
                    </tr>
                    <tr style='height: 10px;'>
                        <td><br></td>
                        <td><br></td>
                    </tr>
                    <tr>
                        <td>
                            <div class='izquierda centrarTexto'>
                                <b id='bNombreCliente'> Cliente: ".$request->get('nombreVenta')."</b>
                            </div>
                        </td>
                        <td style='text-align: right;'>
                            <div>
                                <b>FECHA: </b><span id='spanFechaCompra'>".str_replace("T", " ", $request->get('fechaHoraVenta'))."</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class='izquierda centrarTexto'>
                                <b id='bNombreCliente'> Titular: ".$request->get('nombreTitularVenta')."</b>
                            </div>
                        </td>
                    </tr>
                    <tr style='height: 10px;'>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan='2' class='centrar'>
                            <div class='izquierda w100 margenArriba25'>
                            <div class='izquierda margenIzquierdo10'>
                                <table>
                                    <tr>
                                        <td>RUT:</td>
                                        <td id='tdRut'>".$request->get('rutVenta')."</td>
                                    </tr>
                                    <tr>
                                        <td>FONO:</td>
                                        <td id='tdFono'>".$fono."</td>
                                    </tr>
                                    <tr>
                                        <td>DOMICILIO:</td>
                                        <td id='tdDomicilio'>".$domicilio."</td>
                                    </tr>
                                    <tr>
                                        <td>COMENTARIO:</td>
                                        <td id='tdComentario'>".$request->get('comentarioVenta')."</td>
                                    </tr>
                                </table>
                            </div>
                            </div>
                        </td>
                    </tr>
                    <tr style='height: 10px;'>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan='2'>
                            <div class='centrar'>
                                <table id='tablaCuotas' cellpadding='2' style='width: 100%;'>";
                                foreach ($cuotas as $cuota){
                                    
                                        $html.= "<tr>";
                                        $html.= '<td>CUOTA '.$c.' : $'.$cuota->montoCuota.'</td>';
                                        $html.= '<td style="text-align:right;">VENCE : '.$cuota->fechaVencimientoDeudaMensual.'</td>';
                                        $html.= "</tr>";
                                    $c++;
                                }

                                
                 $html .="</table>
                            </div>
                        </td>
                    </tr>
                    <tr style='height: 10px;'>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <div class='izquierda'>
                                VENTA POR: <span id='bMontoCompra'>".$request->get('montoVenta')."</span>
                            </div>
                        </td>
                        <td style='text-align: right;'>
                            <div class='derecha'>
                                <span>BOLETA: </span><span id='spanNumeroBoleta'>".$request->get('nboleta')."</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class='izquierda w80 centrar'>
                                <table>
                                    <tr>
                                        <td>PIE</td>
                                        <td id='tdPie'>".$request->get('montoPieVenta')."</td>
                                    </tr>
                                    <tr>
                                        <td><b>TOTAL FINAL</b></td>
                                        <td id='tdPie'>".$request->get('deudaFinalVenta')."</td>
                                    </tr>
                                    <tr style='height: 10px;'>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan='2'></td>
                                    </tr>
                                </table>
                                <div id='divDescripcionProductos'>";
                                while($request->has($inputProducto)){

                                    $html.= $request->get($inputProducto)."<br>";

                                    $inputProducto = trim($inputProducto, $i);
                                    $i++;
                                    $inputProducto = $inputProducto.$i;
                                    //echo $i;
                                }
                                
                                $html .="</div>
                            </div>
                        </td>
                    </tr>
                    <tr style='height: 50px;padding: 20px;'>
                        <td>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br></td>
                        <td>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br></td>
                    </tr>
                    <tr>
                
                        <td style='text-align: center;' colspan='2'>
                            <div class='centrar' >
                            <table >
                                <tr>
                                    <td class='bordeArriba centrarTexto' id='tdNombreCliente'>".$request->get('nombreVenta')."</td>
                                </tr>
                                <tr>
                                    <td class='centrarTexto'>
                                        CLIENTE
                                    </td>
                                </tr>
                            </table>
                            </div>
                        </td>
                    </tr>
                </table>
                </td>
                <td id='idTd3' style='width:45%; '></td>
                </td>
                <td id='idTd2' style='width:10%; '>
                </td>
                </tr>
                
                </table>
            
            
            </div>

            </div>
        </body>
        <script type='text/javascript'>
            window.print();
        </script>
        </html>";

        $ruta=asset('tempPDF/venta.pdf');
        echo $ruta;
        $mpdf=new mPDF(['default_font_size'=> '0', 'format' => 'Letter-L', 'margin_left' => '160', 'margin_right' => '-110', 'margin_top' => '0', 'margin_bottom' => '20', 'margin_header' => '5', 'margin_footer' => '0', 'orientation' => 'L']);
        $mpdf->SetJS('this.print();');
        $mpdf->WriteHTML($html);
        $mpdf->Output('../public/tempPDF/venta.pdf','F');
        return 0; 
        
    }


}
