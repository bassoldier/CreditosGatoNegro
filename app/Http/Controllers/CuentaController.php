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
use App\Models\Abono;
use App\Models\Reduce;
use App\Models\RegistrosMora;
use Storage;

class CuentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $hoy = getdate();
        $fechaActualStr= $hoy["year"]."-".$hoy["mon"]."-".$hoy["mday"];
        //$fechaActualStr= "2019-10-25";

        $clientes = DB::table('clientes')->join('deuda_mensual', 'clientes.idCliente', '=', 'deuda_mensual.idCliente')->selectRaw('clientes.idCliente, rutCliente, nombreCliente, apellidoPatCliente, apellidoMatCliente, telefonoCliente, correoCliente, direccionCliente, rutRecomendadoCliente, fechaPagoCliente, fechaFacturacionCliente, deudaTotalCliente, morosoCliente, bloqueoCliente')->selectRaw('SUM(montoMoraDeudaMensual) as montoMora')->groupBy('clientes.idCliente', 'rutCliente', 'nombreCliente', 'apellidoPatCliente', 'apellidoMatCliente', 'telefonoCliente', 'correoCliente', 'direccionCliente', 'rutRecomendadoCliente', 'fechaPagoCliente', 'fechaFacturacionCliente', 'deudaTotalCliente', 'morosoCliente', 'bloqueoCliente')->get();

        $sugerenciaMora[]=array();
        foreach ($clientes as $cliente) {
            $deuda_mensual = DB::table('deuda_mensual')->selectRaw("idDeudaMensual, MIN(fechaVencimientoDeudaMensual) AS fechaVencimientoDeudaMensual")->groupBy('idDeudaMensual')->where('idCliente', $cliente->idCliente)->where('montoDeudaMensual','>',0)->where('fechaVencimientoDeudaMensual','<',$fechaActualStr)->first();
            if($deuda_mensual){
                $auxConsulta=DB::table('deuda_mensual')->selectRaw("SUM(montoDeudaMensual) AS deudaVencida, SUM(montoMoraDeudaMensual) as montoMoraDeudaMensual")->where('montoDeudaMensual', '>', 0)->where('fechaVencimientoDeudaMensual', '<', $fechaActualStr)->where('idCliente',$cliente->idCliente)->first();
                $deudaVencida=$auxConsulta->deudaVencida;
                $montoMoraOLD=$auxConsulta->montoMoraDeudaMensual;
                
                if($montoMoraOLD >= $deudaVencida){
                    $moraSugerida=0;
                }else{
                    $deudaVencida=$deudaVencida-$montoMoraOLD;
                    $fechaActual =  strtotime($fechaActualStr);
                    $fechaVencimiento = strtotime($deuda_mensual->fechaVencimientoDeudaMensual);
                    $diff = $fechaActual - $fechaVencimiento;
                    $diff = round($diff / 86400);

                    // will output 2 days
                    //echo $diff . ' days ';

                    if((int) $diff <= 0 ){
                        $moraSugerida=0;
                    }
                    if( 0 < (int) $diff && (int) $diff <=  30){
                        $aux=$deudaVencida*0.961;
                        $moraSugerida= round($aux);
                    }
                    if(30 < (int) $diff && (int) $diff <=  60){
                        $aux=$deudaVencida*1.886;
                        $moraSugerida= round($aux);
                    }
                    if(60 < (int) $diff && (int) $diff <=  90){
                        $aux=$deudaVencida*2.775;
                        $moraSugerida= round($aux);
                    }
                    if(90 < (int) $diff && (int) $diff <= 120 ){
                        $aux=$deudaVencida*3.629;
                        $moraSugerida= round($aux);
                    }
                    if( 120 < (int) $diff && (int) $diff <=  150){
                        $aux=$deudaVencida*4.451;
                        $moraSugerida= round($aux);
                    }
                    if(150 < (int) $diff && (int) $diff <=  180){
                        $aux=$deudaVencida*5.240;
                        $moraSugerida= round($aux);
                    }
                    if(180 < (int) $diff && (int) $diff <=  210){
                        $aux=$deudaVencida*6.000;
                        $moraSugerida= round($aux);
                    }
                    if(210 < (int) $diff && (int) $diff <=  240){
                        $aux=$deudaVencida*6.730;
                        $moraSugerida= round($aux);
                    }
                    if(240 < (int) $diff){
                        $aux=$deudaVencida*6.730;
                        $moraSugerida= round($aux);
                    }
                }
                $idDeudaMensual=$deuda_mensual->idDeudaMensual;
            }else{
                $moraSugerida=0;
                $idDeudaMensual=0;
            }


            $componente = array( 
                $cliente->idCliente => array(
                    'idDeudaMensual' => $idDeudaMensual,
                    'moraSugerida'  => $moraSugerida
                )
            );


            $sugerenciaMora=$sugerenciaMora+$componente;
        }


        return view('cuentas', ['clientes' => $clientes, 'sugerenciaMora' => $sugerenciaMora]);
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
            $hoyHoraFecha = date("Y-m-d\TH:i");
            $hoy = getdate();
            $fechaActualStr= $hoy["year"]."-".$hoy["mon"]."-".$hoy["mday"];
            //$fechaActualStr= "2020-03-02";
            $abono = new Abono;
            $abono->montoAbono=$request->get('abono');
            $abono->fechaAbono=$hoyHoraFecha;
            $abono->idCliente =$request->get('auxIdCliente');
            $abono->save();

            $id=$request->get('auxIdCliente');
            $cliente=Cliente::find($id);

            $montoAbono=(int) $request->get('abono');
            $montoAbonoOriginal=(int) $request->get('abono');

            $deudas_mensuales = DB::table('deuda_mensual')->where('idCliente', $id)->where('montoDeudaMensual','>',0)->orderby('mesCorrespondienteDeudaMensual','ASC')->get();  

            foreach ($deudas_mensuales as $deuda_men) {
                $deuda_mensual=DeudaMensual::find($deuda_men->idDeudaMensual);

                if((int)$deuda_mensual->montoDeudaMensual<=$montoAbono){
                    $montoAbono=$montoAbono - (int) $deuda_mensual->montoDeudaMensual;
                    $montoReduccion=(int) $deuda_mensual->montoDeudaMensual;
                    $newMontoDeuda = 0;
                    $deuda_mensual->montoDeudaMensual=$newMontoDeuda;
                    $deuda_mensual->montoMoraDeudaMensual=0;
                }else{
                    $montoReduccion=$montoAbono;
                    $newMontoDeuda= (int) $deuda_mensual->montoDeudaMensual - $montoAbono;
                    $montoAbono=0;
                    $deuda_mensual->montoDeudaMensual=$newMontoDeuda;
                }





                $fecha_actual = strtotime($fechaActualStr);
                $fecha_pago = strtotime($deuda_mensual->fechaVencimientoDeudaMensual);
                //echo($fecha_pago);

                list($anoPag, $mesPag, $diaPag) = explode('-', $deuda_mensual->fechaVencimientoDeudaMensual);

                $mesInt = (int) $mesPag;
                $anoInt = (int) $anoPag;

                $mesInt++;
                    if($mesInt==13){
                        $mesInt=1;
                        $anoInt++;
                    }

                $fechaPagoMesSig= $anoInt."-".$mesInt."-".$diaPag;
                $fecha_pago_mes_sig = strtotime($fechaPagoMesSig);

                if($cliente->morosoCliente==1 && $fecha_pago < $fecha_actual && $fecha_pago_mes_sig > $fecha_actual && $newMontoDeuda <= 0){
                    $cliente->morosoCliente =0;
                    $cliente->bloqueoCliente=0;

                    $registroMora=DB::table('registros_mora')->where('idCliente', $id)->whereNull('fechaSalidaMora')->first();
                    $registroMora=RegistrosMora::find($registroMora->idMora);
                    $registroMora->fechaSalidaMora=$fechaActualStr;
                    $registroMora->save();
                }

                $reduce = new Reduce;
                $reduce->montoReduccion=$montoReduccion;
                $reduce->idDeudaMensual=$deuda_mensual->idDeudaMensual;
                $reduce->idAbono = $abono->idAbono;


                $reduce->save();
                $deuda_mensual->save();
                

                if($montoAbono==0){
                    break;
                }
            }
            
            $cliente->deudaTotalCliente= (int) $cliente->deudaTotalCliente - $montoAbonoOriginal;
            $cliente->save();


            $this->crearPDFAbono($abono->idAbono);
            return redirect()->back()->with('printAbono', 'El pago se ha registrado con éxito. Imprimiendo comprobante');


        }catch (\Illuminate\Database\QueryException $e) {
            report($e);

            return redirect()->back()->with('fail', 'Ha ocurrido un error, el pago no se ha registrado');
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function cargarMora($id, $monto)
    {
        $deuda_mensual=DeudaMensual::find($id);
        
        $deuda_mensual->montoMoraDeudaMensual=(int)$monto + (int)$deuda_mensual->montoMoraDeudaMensual;
        $deuda_mensual->montoDeudaMensual=(int)$monto + (int)$deuda_mensual->montoDeudaMensual;
        

        $cliente=Cliente::find($deuda_mensual->idCliente);
        $cliente->deudaTotalCliente=(int)$monto + (int)$cliente->deudaTotalCliente;

        $cliente->save();
        $deuda_mensual->save();

        return response()->json([
            'nuevaMora'=>$deuda_mensual->montoMoraDeudaMensual       
        ]);
    }

    public function eliminarMora($id)
    {
        $deuda_mensual=DeudaMensual::find($id);

        if((int)$deuda_mensual->montoMoraDeudaMensual >= (int)$deuda_mensual->montoDeudaMensual){
            $resta=$deuda_mensual->montoDeudaMensual;
            $deuda_mensual->montoMoraDeudaMensual=0;
            $deuda_mensual->montoDeudaMensual=0;
        }else{
            $resta=$deuda_mensual->montoMoraDeudaMensual;
            $deuda_mensual->montoDeudaMensual=(int)$deuda_mensual->montoDeudaMensual- (int)$deuda_mensual->montoMoraDeudaMensual;
            $deuda_mensual->montoMoraDeudaMensual=0;
        }
        
        

        $cliente=Cliente::find($deuda_mensual->idCliente);
        $cliente->deudaTotalCliente=(int)$cliente->deudaTotalCliente - (int) $resta;

        $cliente->save();
        $deuda_mensual->save();

        return response()->json([
            'nuevaMora'=>$deuda_mensual->montoMoraDeudaMensual       
        ]);
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showCuentaVentas($id)
    {
        $ventas = DB::table('ventas')->join('vendedores', 'ventas.idVendedor','=','vendedores.idVendedor')->orderBy('fechaHoraVenta', 'DESC')->where('idCliente', $id)->get();   
        return response()->json($ventas);
    }

    public function showDeudaVentas($id)
    {
        $ventas = DB::table('cuotas')->join('ventas', 'cuotas.idVenta', '=', 'ventas.idVenta')->join('vendedores', 'ventas.idVendedor','=', 'vendedores.idVendedor')->selectRaw('ventas.idVenta, fechaHoraVenta, numeroBoletaVenta, montoOriginalVenta, montoPostInteresVenta, montoPieVenta, numeroDeCuotasVenta, valorCuotaVenta, estadoVenta, nombreVendedor, apellidoPatVendedor, apellidoMatVendedor')->groupBy('idVenta', 'ventas.idVenta', 'fechaHoraVenta', 'numeroBoletaVenta', 'montoOriginalVenta', 'montoPostInteresVenta', 'montoPieVenta', 'numeroDeCuotasVenta', 'valorCuotaVenta', 'estadoVenta', 'nombreVendedor', 'apellidoPatVendedor', 'apellidoMatVendedor')->where('idDeudaMensual', $id)->get(); 

          
        return response()->json($ventas);
    }

    public function showAbonoCuenta($id)
    {
        $abonos = DB::table('abonos')->where('idCliente', $id)->orderby('fechaAbono', 'DESC')->get(); 

          
        return response()->json($abonos);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function getClienteJson($id)
    {
        $clientes = DB::table('clientes')->where('idCliente', $id)->get();    
        foreach ($clientes as $cliente) {
            return response()->json([
            'deudaCliente' => $cliente->deudaTotalCliente
        ]);
        }
    }

    public function crearPDFAbono($idPago)
    {
        $abono = Abono::find($idPago);  
        $cliente = Cliente::find($abono->idCliente);
        $idCliente=$abono->idCliente;

        $rutTitular= $cliente->rutCliente;
        $nombreTitular = $cliente->nombreCliente." ".$cliente->apellidoPatCliente." ".$cliente->apellidoMatCliente;
        $deudaTotal = $cliente->deudaTotalCliente;

        $primeraFechaPagoAux = date($cliente->fechaPagoCliente);
        list($primeraAnoPagoAux, $primerMesPagoAux, $primerDiaPagoAux) = explode('-', $primeraFechaPagoAux);
        $diaPago= $primerDiaPagoAux;


        $fechaAbono = date($abono->fechaAbono);
        list($fechaAbono, $horaAbono) = explode(' ', $fechaAbono);
        list($anoAbono, $mesAbono, $diaAbono) = explode('-', $fechaAbono);
        $fechaPago= $diaAbono."-".$mesAbono."-".$anoAbono;


        $montoTotalPagado=$abono->montoAbono;

        $reducciones= DB::table('reduce')->where('idAbono', $idPago)->orderby('idDeudaMensual', 'ASC')->get(); 


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
                                <b id='bNombreCliente'> COMPROBANTE DE PAGO</b>
                            </div>
                        </td>
                        <td style='text-align: right;'>
                            <div>
                                <b>FECHA: </b><span id='spanFechaCompra'>".$fechaPago."</span>
                            </div>
                            <div>
                                <b>HORA: </b><span id='spanFechaCompra'>".$horaAbono."</span>
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
                                        <td id='tdRut'>".$rutTitular."</td>
                                    </tr>
                                    <tr>
                                        <td>TITULAR:</td>
                                        <td id='tdRut'>".$nombreTitular."</td>
                                    </tr>
                                    <tr>
                                        <td>DÍA DE PAGO:</td>
                                        <td id='tdRut'>".$diaPago."</td>
                                    </tr>
                                    
                                </table>
                            </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='2'>
                            <div class='centrar'>
                                <table id='tablaCuotas' cellpadding='2' style='width: 100%;'>
                                <thead>
                                    <tr>
                                      <th>VENCIMIENTO DEUDA</th>
                                      <th>PAGADO</th>
                                      
                                    </tr>
                                </thead>";
                                foreach ($reducciones as $reduccion){
                                    $deuda_mensual = DeudaMensual::find($reduccion->idDeudaMensual);
                                    $fechaFactAux = date($deuda_mensual->fechaVencimientoDeudaMensual);
                                    list($anoFactAux, $mesFactAux, $diaFactAux) = explode('-', $fechaFactAux);
                                    $fechaFact= $diaFactAux."-".$mesFactAux."-".$anoFactAux;



                                    $html.= "<tr>";
                                    $html.= '<td>'.$fechaFact.'</td>';
                                    $html.= '<td style="text-align:right;">$ '.number_format($reduccion->montoReduccion).'</td>';
                                    /*$html.= '<td style="text-align:right;">$ '.number_format($deuda_mensual->montoDeudaMensual).'</td>';*/
                                    $html.= "</tr>";

                                    $idFinalDeuda = $reduccion->idDeudaMensual;
                                }
                                $deuda_mensual = DeudaMensual::where('idCliente', $idCliente)->where('idDeudaMensual','>',$idFinalDeuda)->get();

                                foreach ($deuda_mensual as $deuda){
                                    
                                    $fechaFactAux = date($deuda->fechaVencimientoDeudaMensual);
                                    list($anoFactAux, $mesFactAux, $diaFactAux) = explode('-', $fechaFactAux);
                                    $fechaFact= $diaFactAux."-".$mesFactAux."-".$anoFactAux;



                                    $html.= "<tr>";
                                    $html.= '<td>'.$fechaFact.'</td>';
                                    $html.= '<td style="text-align:right;">$ 0</td>';
                                    /*$html.= '<td style="text-align:right;">$ '.number_format($deuda->montoDeudaMensual).'</td>';*/
                                    $html.= "</tr>";

                                   
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
                                <b>MONTO TOTAL PAGADO: </b><span id='bMontoCompra'>".number_format($montoTotalPagado,0,'','.')."</span>
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
                                    <td class='centrarTexto'>
                                        COMPROBANTE DE PAGO
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



        $ruta=asset('tempPDF/abono.pdf');
        echo $ruta;
        $mpdf=new mPDF(['default_font_size'=> '0', 'format' => 'Letter-L', 'margin_left' => '160', 'margin_right' => '-110', 'margin_top' => '0', 'margin_bottom' => '20', 'margin_header' => '5', 'margin_footer' => '0', 'orientation' => 'L']);
        $mpdf->SetJS('this.print();');
        $mpdf->WriteHTML($html);
        $mpdf->Output('../public/tempPDF/abono.pdf','F');
        return 0; 
        
    }

    public function imprimirPago($idAbono)
    {
        $this->crearPDFAbono($idAbono);
        return redirect()->back()->with('printAbonoDet', 'Imprimiendo Pago');
    }

}
