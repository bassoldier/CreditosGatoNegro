<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Adicional;
use App\Models\Cliente;
use App\Models\Recibe;
use Storage;

class AdicionalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $adicionales = DB::table('adicionales')->join('clientes', 'adicionales.idCliente', '=', 'clientes.idCliente')->get();
        $clientes = Cliente::all();
        return view('adicionales', ['adicionales' => $adicionales, 'clientes'=> $clientes]);
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
            $cliente = Cliente::where('rutCliente', $request->get('rutAdicional'))->get();
            if ($cliente->isEmpty()) {
                $adicional = new Adicional;
                $adicional->idCliente=DB::table('clientes')->where('rutCliente', $request->get('rutTitularAdicional'))->value('idCliente');
                $adicional->rutAdicional = $request->get('rutAdicional');
                $adicional->nombreAdicional = $request->get('nombreAdicional');
                $adicional->apellidoPatAdicional = $request->get('apellidoPatAdicional');
                $adicional->apellidoMatAdicional = $request->get('apellidoMatAdicional');
                $adicional->telefonoAdicional = $request->get('telefonoAdicional');
                $adicional->correoAdicional = $request->get('correoAdicional');
                $adicional->direccionAdicional = $request->get('direccionAdicional');
                $adicional->bloqueoAdicional = 0;
                $status1=$adicional->save();
                if($status1){
                    return redirect()->back()->with('success', 'El adicional ha sido registrado con éxito'); 
                }
                else{
                    return redirect()->back()->with('fail', 'Ha ocurrido un error, el adicional no se ha registrado');
                }
            }else{
                return redirect()->back()->with('fail', 'El adicional no se ha registrado, el usuario ya posee una cuenta titular');
            }

        }catch (\Illuminate\Database\QueryException $e) {
            report($e);

            return redirect()->back()->with('fail', 'Ha ocurrido un error, el adicional no se ha registrado');
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
        $adicionales = DB::table('adicionales')->join('clientes', 'adicionales.idCliente', '=', 'clientes.idCliente')->where('idAdicional', $id)->get();
        foreach ($adicionales as $adicional) {
            return response()->json([
            'rutCliente' => $adicional->rutCliente,
            'nombreCliente'=>$adicional->nombreCliente." ".$adicional->apellidoPatCliente." ".$adicional->apellidoPatCliente,
            'rutAdicional'=>$adicional->rutAdicional,
            'nombreAdicional'=>$adicional->nombreAdicional,
            'apellidoPatAdicional'=>$adicional->apellidoPatAdicional,
            'apellidoMatAdicional'=>$adicional->apellidoMatAdicional,
            'correoAdicional'=>$adicional->correoAdicional,
            'telefonoAdicional'=>$adicional->telefonoAdicional,
            'direccionAdicional'=>$adicional->direccionAdicional
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
        try {
            $cliente = Cliente::where('rutCliente', $request->get('rutAdicionalDet'))->get();
            if ($cliente->isEmpty()) {
                $adicional = Adicional::find($id);

                $cliente = Cliente::where('rutCliente', $request->get('rutTitularAdicionalDet'))->get();

                $adicional->idCliente = $cliente[0]->idCliente;
                $adicional->rutAdicional = $request->get('rutAdicionalDet');
                $adicional->nombreAdicional = $request->get('nombreAdicionalDet');
                $adicional->apellidoPatAdicional = $request->get('apellidoPatAdicionalDet');
                $adicional->apellidoMatAdicional = $request->get('apellidoMatAdicionalDet');
                $adicional->telefonoAdicional = $request->get('telefonoAdicionalDet');
                $adicional->correoAdicional = $request->get('correoAdicionalDet');
                $adicional->direccionAdicional = $request->get('direccionAdicionalDet');

                $status1=$adicional->save();
                if($status1){
                    return redirect()->back()->with('success', 'El Adicional ha sido actualizado con éxito'); 
                }
                else{
                    return redirect()->back()->with('fail', 'Ha ocurrido un error, el Adicional no se ha registrado');
                }
            }
            else{
                return redirect()->back()->with('fail', 'El adicional no se ha actualizado, el usuario ya posee una cuenta titular');
            }

        }catch (\Illuminate\Database\QueryException $e) {
            report($e);

            return redirect()->back()->with('fail', 'Ha ocurrido un error, el Adicional no se ha actualizado');
        }
        catch (\Exception $e) {
            report($e);

            return redirect()->back()->with('fail', 'Ha ocurrido un error, el Adicional no se ha actualizado');
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
        Recibe::where('idAdicional', $id)->delete();
        Adicional::destroy($id);
        return redirect()->back()->with('success', 'El Adicional ha sido eliminado con éxito'); 
    }

    public function cambiarBloqueoAdicional($id, $bloqueo)
    {
        $adicional = Adicional::find($id);
        $adicional->bloqueoAdicional=$bloqueo;
        $adicional->save();
        return response()->json([
            'result'=>'nice'        
        ]);
    }


}
