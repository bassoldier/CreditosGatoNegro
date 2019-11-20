<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Models\Venta;
use App\Models\Vendedor;
use App\Models\Cliente;
use App\Models\Adicional;
use App\Models\Producto;
use App\Models\Cuota;
use App\Models\DeudaMensual;
use App\Models\Recibe;
use Storage;

class InicioController extends Controller
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
        $finDeAno= $hoy["year"]."-"."12-31";
        $principioDeAno= $hoy["year"]."-"."01-01";

        $deudas=DB::table('deuda_mensual')->selectRaw('SUM(montoDeudaMensual) as deudaTotal')->first();
        $deudaTotal=$deudas->deudaTotal;

        $pagos=DB::table('abonos')->selectRaw('SUM(montoAbono) as abonoTotal')->where('fechaAbono','<=', $finDeAno)->where('fechaAbono','>=', $principioDeAno)->first();
        $abonoTotal=$pagos->abonoTotal;

        $cantidadPagos=DB::table('abonos')->where('fechaAbono','<=', $finDeAno)->where('fechaAbono','>=', $principioDeAno)->count();

        $cantidadClientes=DB::table('clientes')->count();
        $cantidadClientesMorosos=DB::table('clientes')->where('morosoCliente', 1)->count();

        if($cantidadClientes>0){
            $porcentajeClientesMorosos=round(((int) $cantidadClientesMorosos*100)/(int) $cantidadClientes);
        }else{
            $porcentajeClientesMorosos=0;
        }
        


        

        return view('principal', ['deudaTotal' => $deudaTotal, 'abonoTotal' => $abonoTotal, 'cantidadPagos'=>$cantidadPagos, 'porcentajeClientesMorosos'=>$porcentajeClientesMorosos]);
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

    public function datosGraficoIngresos()
    {
        $hoy = getdate();
        $fechaActualStr= $hoy["year"]."-".$hoy["mon"]."-".$hoy["mday"];

        //ENERO


        $inicio= $hoy["year"]."-"."01-01";
        $fin= $hoy["year"]."-"."01-31";
        $enero=0;
        $pagos=DB::table('abonos')->where('fechaAbono','<=', $fin)->where('fechaAbono','>=', $inicio)->get();
        if($pagos->isNotEmpty()){
            foreach ($pagos as $pagos) {
                $enero=$pagos->montoAbono + $enero;
            }
        }
        else{
            $enero=0;
        }


        

        //FEBRERO
        $inicio= $hoy["year"]."-"."02-01";
        $fin= $hoy["year"]."-"."02-29";
        $febrero=0;
        $pagos=DB::table('abonos')->where('fechaAbono','<=', $fin)->where('fechaAbono','>=', $inicio)->get();
        if($pagos->isNotEmpty()){
            foreach ($pagos as $pagos) {
                $febrero=$pagos->montoAbono + $febrero;
            }
            
        }
        else{
            $febrero=0;
        }
        

        //MARZO
        $inicio= $hoy["year"]."-"."03-01";
        $fin= $hoy["year"]."-"."03-31";
        $marzo=0;
        $pagos=DB::table('abonos')->where('fechaAbono','<=', $fin)->where('fechaAbono','>=', $inicio)->get();
        if($pagos->isNotEmpty()){
            foreach ($pagos as $pagos) {
                $marzo=$pagos->montoAbono + $marzo;
            }
        }
        else{
            $marzo=0;
        }

        //ABRIL
        $inicio= $hoy["year"]."-"."04-01";
        $fin= $hoy["year"]."-"."04-30";
        $abril=0;
        $pagos=DB::table('abonos')->where('fechaAbono','<=', $fin)->where('fechaAbono','>=', $inicio)->get();
        if(!$pagos->isEmpty()){
            foreach ($pagos as $pagos) {
                $abril=$pagos->montoAbono + $abril;
            }
        }
        else{
            $abril=0;
        }


        //MAYO
        $inicio= $hoy["year"]."-"."05-01";
        $fin= $hoy["year"]."-"."05-31";
        $mayo=0;
        $pagos=DB::table('abonos')->where('fechaAbono','<=', $fin)->where('fechaAbono','>=', $inicio)->get();
        if($pagos->isNotEmpty()){
            foreach ($pagos as $pagos) {
                $mayo=$pagos->montoAbono + $mayo;
            }
        }
        else{
            $mayo=0;
        }


        //JUNIO
        $inicio= $hoy["year"]."-"."06-01";
        $fin= $hoy["year"]."-"."06-30";
        $junio=0;
        $pagos=DB::table('abonos')->where('fechaAbono','<=', $fin)->where('fechaAbono','>=', $inicio)->get();
        if($pagos->isNotEmpty()){
            foreach ($pagos as $pagos) {
                $junio=$pagos->montoAbono + $junio;
            }
        }
        else{
            $junio=0;
        }

        //JULIO
        $inicio= $hoy["year"]."-"."07-01";
        $fin= $hoy["year"]."-"."07-30";
        $julio=0;
        $pagos=DB::table('abonos')->where('fechaAbono','<=', $fin)->where('fechaAbono','>=', $inicio)->get();
        if($pagos->isNotEmpty()){
            foreach ($pagos as $pagos) {
                $julio=$pagos->montoAbono + $julio;
            }
        }
        else{
            $julio=0;
        }

        //AGOSTO
        $inicio= $hoy["year"]."-"."08-01";
        $fin= $hoy["year"]."-"."08-31";
        $agosto=0;
        $pagos=DB::table('abonos')->where('fechaAbono','<=', $fin)->where('fechaAbono','>=', $inicio)->get();
        if($pagos->isNotEmpty()){
            foreach ($pagos as $pagos) {
                $agosto=$pagos->montoAbono + $agosto;
            }
        }
        else{
            $agosto=0;
        }


        //SEPTIEMBRE
        $inicio= $hoy["year"]."-"."09-01";
        $fin= $hoy["year"]."-"."09-30";
        $septiembre=0;
        $pagos=DB::table('abonos')->where('fechaAbono','<=', $fin)->where('fechaAbono','>=', $inicio)->get();
        if($pagos->isNotEmpty()){
            foreach ($pagos as $pagos) {
                $septiembre=$pagos->montoAbono + $septiembre;
            }
        }
        else{
            $septiembre=0;
        }

        //OCTUBRE
        $inicio= $hoy["year"]."-"."10-01";
        $fin= $hoy["year"]."-"."10-31";
        $octubre=0;
        $pagos=DB::table('abonos')->where('fechaAbono','<=', $fin)->where('fechaAbono','>=', $inicio)->get();
        if($pagos->isNotEmpty()){
            foreach ($pagos as $pagos) {
                $octubre=$pagos->montoAbono + $octubre;
            }
        }
        else{
            $octubre=0;
        }

        //NOVIEMBRE
        $inicio= $hoy["year"]."-"."11-01";
        $fin= $hoy["year"]."-"."11-30";
        $noviembre=0;
        $pagos=DB::table('abonos')->where('fechaAbono','<=', $fin)->where('fechaAbono','>=', $inicio)->get();
        if($pagos->isNotEmpty()){
            foreach ($pagos as $pagos) {
                $noviembre=$pagos->montoAbono + $noviembre;
            }
        }
        else{
            $noviembre=0;
        }

        //DICIEMBRE
        $inicio= $hoy["year"]."-"."12-01";
        $fin= $hoy["year"]."-"."12-31";
        $diciembre=0;
        $pagos=DB::table('abonos')->where('fechaAbono','<=', $fin)->where('fechaAbono','>=', $inicio)->get();
        if($pagos->isNotEmpty()){
            foreach ($pagos as $pagos) {
                $diciembre=$pagos->montoAbono + $diciembre;
            }
        }
        else{
            $diciembre=0;
        }

        return response()->json([
            'ene'=>$enero,
            'feb'=>$febrero,
            'mar'=>$marzo,
            'abr'=>$abril,
            'may'=>$mayo,
            'jun'=>$junio,
            'jul'=>$julio,
            'agos'=>$agosto,
            'sept'=>$septiembre,
            'oct'=>$octubre,
            'nov'=>$noviembre,
            'dic'=>$diciembre
        ]);

    }
}
