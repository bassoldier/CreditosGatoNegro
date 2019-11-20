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

class CuentaAdicionalController extends Controller
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

        $clientes = DB::table('adicionales')->join('clientes', 'adicionales.idCliente', '=', 'clientes.idCliente')->join('deuda_mensual', 'clientes.idCliente', '=', 'deuda_mensual.idCliente')->selectRaw('idAdicional, rutAdicional, nombreAdicional, apellidoPatAdicional, apellidoMatAdicional, bloqueoAdicional, clientes.idCliente, rutCliente, nombreCliente, apellidoPatCliente, apellidoMatCliente, telefonoCliente, correoCliente, direccionCliente, rutRecomendadoCliente, fechaPagoCliente, fechaFacturacionCliente, deudaTotalCliente, morosoCliente, bloqueoCliente')->selectRaw('SUM(montoMoraDeudaMensual) as montoMora')->groupBy('idAdicional', 'rutAdicional', 'nombreAdicional', 'apellidoPatAdicional', 'apellidoMatAdicional', 'bloqueoAdicional','clientes.idCliente', 'rutCliente', 'nombreCliente', 'apellidoPatCliente', 'apellidoMatCliente', 'telefonoCliente', 'correoCliente', 'direccionCliente', 'rutRecomendadoCliente', 'fechaPagoCliente', 'fechaFacturacionCliente', 'deudaTotalCliente', 'morosoCliente', 'bloqueoCliente')->get();

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


        return view('cuentasAdicional', ['clientes' => $clientes, 'sugerenciaMora' => $sugerenciaMora]);
    }

    public function showCuentaVentasAdicional($id)
    {
        $ventas = DB::table('recibe')->join('ventas', 'recibe.idVenta','=','ventas.idVenta')->join('vendedores', 'ventas.idVendedor','=','vendedores.idVendedor')->join('adicionales', 'recibe.idAdicional','=','adicionales.idAdicional')->orderBy('fechaHoraVenta', 'DESC')->where('recibe.idAdicional', $id)->get();   
        return response()->json($ventas);
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
        //
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
}
