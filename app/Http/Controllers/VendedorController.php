<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Vendedor;
use Storage;

class VendedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendedores = DB::table('vendedores')->get();
        return view('vendedores', ['vendedores' => $vendedores]);
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
            $Vendedor = new Vendedor;
            $Vendedor->rutVendedor = $request->get('rutVendedor');
            $Vendedor->nombreVendedor = $request->get('nombreVendedor');
            $Vendedor->apellidoPatVendedor = $request->get('apellidoPatVendedor');
            $Vendedor->apellidoMatVendedor = $request->get('apellidoMatVendedor');
            $Vendedor->telefonoVendedor = $request->get('telefonoVendedor');
            $Vendedor->correoVendedor = $request->get('correoVendedor');
            $status1=$Vendedor->save();
            if($status1){
                return redirect()->back()->with('success', 'El vendedor ha sido registrado con éxito'); 
            }
            else{
                return redirect()->back()->with('fail', 'Ha ocurrido un error, el vendedor no se ha registrado');
            }

        }catch (\Illuminate\Database\QueryException $e) {
            report($e);

            return redirect()->back()->with('fail', 'Ha ocurrido un error, el vendedor no se ha registrado');
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
        $vendedores = DB::table('vendedores')->where('idVendedor', $id)->get();   
        foreach ($vendedores as $vendedor) {
            return response()->json([
            'rutVendedor'=>$vendedor->rutVendedor,
            'nombreVendedor'=>$vendedor->nombreVendedor,
            'apellidoPatVendedor'=>$vendedor->apellidoPatVendedor,
            'apellidoMatVendedor'=>$vendedor->apellidoMatVendedor,
            'correoVendedor'=>$vendedor->correoVendedor,
            'telefonoVendedor'=>$vendedor->telefonoVendedor,
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
            $Vendedor = Vendedor::find($id);
            $Vendedor->rutVendedor = $request->get('rutVendedorDet');
            $Vendedor->nombreVendedor = $request->get('nombreVendedorDet');
            $Vendedor->apellidoPatVendedor = $request->get('apellidoPatVendedorDet');
            $Vendedor->apellidoMatVendedor = $request->get('apellidoMatVendedorDet');
            $Vendedor->telefonoVendedor = $request->get('telefonoVendedorDet');
            $Vendedor->correoVendedor = $request->get('correoVendedorDet');
            $status1=$Vendedor->save();
            if($status1){
                return redirect()->back()->with('success', 'El vendedor ha sido actualizado con éxito'); 
            }
            else{
                return redirect()->back()->with('fail', 'Ha ocurrido un error, el vendedor no se ha registrado');
            }

        }catch (\Illuminate\Database\QueryException $e) {
            report($e);

            return redirect()->back()->with('fail', 'Ha ocurrido un error, el vendedor no se ha actualizado');
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
}
