<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Documento;
use App\Models\Adicional;
use App\Models\DeudaMensual;
use Storage;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes = DB::table('clientes')->get();
        return view('clientes', ['clientes' => $clientes]);
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
            $adicional = Adicional::where('rutAdicional', $request->get('rutCliente'))->get();
            if ($adicional->isEmpty()) {
                $cliente = new Cliente;
                $cliente->rutCliente = $request->get('rutCliente');
                $cliente->nombreCliente = $request->get('nombreCliente');
                $cliente->apellidoPatCliente = $request->get('apellidoPatCliente');
                $cliente->apellidoMatCliente = $request->get('apellidoMatCliente');
                $cliente->telefonoCliente = $request->get('telefonoCliente');
                $cliente->correoCliente = $request->get('correoCliente');
                $cliente->direccionCliente = $request->get('direccionCliente');
                $cliente->rutRecomendadoCliente = $request->get('recomendadoPor');
                $cliente->fechaPagoCliente = $request->get('fechaPagoCliente');
                $cliente->fechaFacturacionCliente = $request->get('fechaFacturacionCliente');
                $cliente->deudaTotalCliente = 0;
                $cliente->morosoCliente = 0;
                $cliente->bloqueoCliente = 0;
                $status1=$cliente->save();

                $inputFile="documentoCliente1";
                $status2 = true;
                $i=1;
                if($status1){        
                    while($request->hasFile($inputFile) and $status2){
                        $doc = $request->File($inputFile);
                        $nombreDoc= time().'_'.$doc->getClientOriginalName(); 

                        Storage::disk('local')->put($nombreDoc, file_get_contents( $doc->getRealPath() ));

                        $documento = new Documento;
                        $documento->nombreDocumento=$nombreDoc;
                        $documento->idCliente=DB::table('clientes')->where('rutCliente', $request->get('rutCliente'))->value('idCliente');
                        $status2=$documento->save();
                        
                        if(!$status2){
                            return redirect()->back()->with('fail', 'Uno o varios archivos no se han subido, compruebe si los documentos se encuentran en los detalles del cliente.'); 
                        }

                        $inputFile = trim($inputFile, $i);
                        $i++;
                        $inputFile = $inputFile.$i;
                        echo $i;
                    }
                    if($status2){
                        $deudaMensual= new DeudaMensual;
                        $deudaMensual->montoDeudaMensual = 0;
                        $deudaMensual->fechaVencimientoDeudaMensual = $request->get('fechaPagoCliente');
                        $deudaMensual->fechaActualizacionDeudaMensual = NULL;
                        $deudaMensual->mesCorrespondienteDeudaMensual = $request->get('fechaFacturacionCliente');
                        $deudaMensual->montoMoraDeudaMensual = 0;
                        $deudaMensual->idCliente = $cliente->idCliente;

                        $status3=$deudaMensual->save();

                        if($status3){
                            return redirect()->back()->with('success', 'El cliente ha sido registrado con éxito');
                        }
                        else{
                            return redirect()->back()->with('fail', 'Ha ocurrido un error, la deuda del cliente no se ha registrado');
                        }
                    }
                    else{
                        return redirect()->back()->with('fail', 'Ha ocurrido un error, el cliente no se ha registrado');
                    }
                }
                else{
                    return redirect()->back()->with('fail', 'Ha ocurrido un error, el cliente no se ha registrado');
                }
            }
            else{
                return redirect()->back()->with('fail', 'El cliente no se ha registrado, el rut pertenece a una cuenta adicional');
            }
        }catch (\Illuminate\Database\QueryException $e) {
            report($e);

            return redirect()->back()->with('fail', 'Ha ocurrido un error, el cliente no se ha registrado');
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
        $clientes = DB::table('clientes')->where('idCliente', $id)->get();
        $deudaMensual = DB::table('deuda_mensual')->where('montoDeudaMensual','>',0)->where('idCliente', $id)->get();
        if($deudaMensual->isEmpty()){
            $classFecha = "editableDet";
            $classLabel = "Not";
        }else{
            $classFecha = "Not";
            $classLabel = "avisador";
        }
        foreach ($clientes as $cliente) {
            return response()->json([
            'rutCliente'=>$cliente->rutCliente,
            'nombreCliente'=>$cliente->nombreCliente,
            'apellidoPatCliente'=>$cliente->apellidoPatCliente,
            'apellidoMatCliente'=>$cliente->apellidoMatCliente,
            'correoCliente'=>$cliente->correoCliente,
            'telefonoCliente'=>$cliente->telefonoCliente,
            'direccionCliente'=>$cliente->direccionCliente,
            'recomendadoPorCliente'=>$cliente->rutRecomendadoCliente,
            'fechaPagoCliente'=>$cliente->fechaPagoCliente,
            'fechaFacturacionCliente'=>$cliente->fechaFacturacionCliente,
            'deudaCliente'=>$cliente->deudaTotalCliente,
            'classFecha' => $classFecha,
            'classLabel' => $classLabel
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
        $adicional = Adicional::where('rutAdicional', $request->get('rutClienteDet'))->get();
        if ($adicional->isEmpty()) {
            $cliente = Cliente::find($id);
            $cliente->rutCliente = $request->get('rutClienteDet');
            $cliente->nombreCliente = $request->get('nombreClienteDet');
            $cliente->apellidoPatCliente = $request->get('apellidoPatClienteDet');
            $cliente->apellidoMatCliente = $request->get('apellidoMatClienteDet');
            $cliente->telefonoCliente = $request->get('telefonoClienteDet');
            $cliente->correoCliente = $request->get('correoClienteDet');
            $cliente->direccionCliente = $request->get('direccionClienteDet');
            $cliente->rutRecomendadoCliente = $request->get('recomendadoPorDet');

            if($request->filled('fechaPagoClienteDet') && $cliente->fechaPagoCliente != $request->get('fechaPagoClienteDet')){

                //OBTENEMOS LA FECHA DE HOY
                $hoy = getdate();
                $fechaActualStr= $hoy["year"]."-".$hoy["mon"]."-".$hoy["mday"];
                $fechaPagoModStr=$request->get('fechaPagoClienteDet');
                $fechaFacturacionModStr=$request->get('fechaFacturacionClienteDet');

                $deudaMensual = DeudaMensual::where('fechaVencimientoDeudaMensual','>=', $fechaActualStr)->where('idCliente', $id)->orderBy('fechaVencimientoDeudaMensual', 'ASC')->get();
                foreach ($deudaMensual as $deuda) {
                    $deuda->fechaVencimientoDeudaMensual=$fechaPagoModStr;
                    $deuda->mesCorrespondienteDeudaMensual=$fechaFacturacionModStr;
                    $deuda->save();

                    list($primeraAnoPagoAux, $primerMesPagoAux, $primerDiaPagoAux) = explode('-', $fechaPagoModStr);
                    $mesInt2 = (int) $primerMesPagoAux;
                    $anoInt2 = (int) $primeraAnoPagoAux;

                    $mesInt2++;
                    if($mesInt2==13){
                        $mesInt2=1;
                        $anoInt2++;
                    }

                    $fechaPagoModStr=$anoInt2."-".$mesInt2."-".$primerDiaPagoAux;

                    
                    list($primeraAnoAux, $primerMesAux, $primerDiaAux) = explode('-', $fechaFacturacionModStr);
                    $mesInt1 = (int) $primerMesAux;
                    $anoInt1 = (int) $primeraAnoAux;

                    $mesInt1++;
                    if($mesInt1==13){
                        $mesInt1=1;
                        $anoInt1++;
                    }

                    $fechaFacturacionModStr=$anoInt1."-".$mesInt1."-".$primerDiaAux;
                }
                $cliente->fechaPagoCliente = $request->get('fechaPagoClienteDet');
                $cliente->fechaFacturacionCliente = $request->get('fechaFacturacionClienteDet');
            }

            $status1=$cliente->save();


            



            




            $inputFile="documentoClienteDet1";
            $status2 = true;
            $i=1;


            if($status1){  

                while($request->hasFile($inputFile) and $status2){
                    echo $i; 
                    $doc = $request->File($inputFile);
                    $nombreDoc= time().'_'.$doc->getClientOriginalName(); 

                    Storage::disk('local')->put($nombreDoc, file_get_contents( $doc->getRealPath() ));

                    $documento = new Documento;
                    $documento->nombreDocumento=$nombreDoc;
                    $documento->idCliente=$id;
                    $status2=$documento->save();
                    
                    if(!$status2){
                        return redirect()->back()->with('fail', 'Uno o varios archivos no se han subido, compruebe si los documentos se encuentran en los detalles del cliente.'); 
                    }

                    $inputFile = trim($inputFile, $i);
                    $i++;
                    $inputFile = $inputFile.$i;
                    
                }
                if($status2){
                     return redirect()->back()->with('success', 'El cliente ha sido actualizado con éxito'); 
                }
                else{
                    return redirect()->back()->with('fail', 'Ha ocurrido un error, el cliente no se ha actualizado');
                }
            }
            else{
                return redirect()->back()->with('fail', 'Ha ocurrido un error, el cliente no se ha actualizado');
            }
        }
        else{
            return redirect()->back()->with('fail', 'El cliente no se ha actualizado, el rut pertenece a una cuenta adicional');
        }
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

    public function cargaNombreCliente($rut)
    {
        $clientes = DB::table('clientes')->where('rutCliente', $rut)->get();   
        foreach ($clientes as $cliente) {
            return response()->json([
            'nombreCliente'=>$cliente->nombreCliente." ".$cliente->apellidoPatCliente." ".$cliente->apellidoMatCliente
        ]);
        }
    }

    public function cargaDatosClienteVenta($rut)
    {
        $clientes = DB::table('clientes')->where('rutCliente', $rut)->get();
        if ($clientes->isEmpty()) {
            $adicionales = DB::table('adicionales')->join('clientes', 'adicionales.idCliente', '=', 'clientes.idCliente')->where('rutAdicional', $rut)->get();

            foreach ($adicionales as $adicional) {

                return response()->json([
                    'idCliente'=>$adicional->idAdicional,
                    'nombreCliente'=>$adicional->nombreAdicional." ".$adicional->apellidoPatAdicional." ".$adicional->apellidoMatAdicional,
                    'rutTitular'=>$adicional->rutCliente,
                    'nombreTitular'=>$adicional->nombreCliente." ".$adicional->apellidoPatCliente." ".$adicional->apellidoMatCliente,
                    'fechadePago'=>$adicional->fechaPagoCliente,
                    'fechadeFacturacion'=>$adicional->fechaFacturacionCliente,
                    'modalCorrespondiente'=>'#detallesAdicionalModal',
                    'class'=>'botonDetalleAdicional',
                    'morosoCliente'=>$adicional->morosoCliente,
                    'bloqueoCliente'=>$adicional->bloqueoCliente,
                    'bloqueoAdicional'=>$adicional->bloqueoAdicional
                ]);
            } 
        }
        else{
            foreach ($clientes as $cliente) {
                return response()->json([
                    'idCliente'=>$cliente->idCliente,
                    'nombreCliente'=>$cliente->nombreCliente." ".$cliente->apellidoPatCliente." ".$cliente->apellidoMatCliente,
                    'rutTitular'=>$cliente->rutCliente,
                    'nombreTitular'=>$cliente->nombreCliente." ".$cliente->apellidoPatCliente." ".$cliente->apellidoMatCliente,
                    'fechadePago'=>$cliente->fechaPagoCliente,
                    'fechadeFacturacion'=>$cliente->fechaFacturacionCliente,
                    'modalCorrespondiente'=>'#detallesClienteModal',
                    'class'=>'botonDetalleCliente',
                    'morosoCliente'=>$cliente->morosoCliente,
                    'bloqueoCliente'=>$cliente->bloqueoCliente,
                    'bloqueoAdicional'=>0
                ]);
            }            
        } 

    }

    public function cambiarBloqueoCliente($id, $bloqueo)
    {
        $cliente = Cliente::find($id);
        $cliente->bloqueoCliente=$bloqueo;
        $cliente->save();
        return response()->json([
            'result'=>'nice'        
        ]);
    }
}
