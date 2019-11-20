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
use App\Models\Abono;
use App\Models\Reduce;
use App\Models\RegistrosMora;
use Storage;

use App\Exports\ClientesExport;
use App\Exports\AdicionalesExport;
use App\Exports\ClientesMorososExport;
use App\Exports\VentasExport;
use App\Exports\PagosExport;
use App\Exports\ComprasClienteExport;
use App\Exports\PagosClienteExport;
use App\Exports\MoraClienteExport;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithHeadings;


class InformeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes = DB::table('clientes')->get();
        return view('informes', ['clientes' => $clientes]);
    }

    public function getClientesExcel()
    {
        return Excel::download(new ClientesExport, 'Clientes.xlsx');
    }

    public function getAdicionalesExcel()
    {
        return Excel::download(new AdicionalesExport, 'Adicionales.xlsx');
    }

    public function getClientesMorososExcel()
    {
        return Excel::download(new ClientesMorososExport, 'Clientes Morosos.xlsx');
    }

    public function getVentasExcel(Request $request)
    {
        return Excel::download(new VentasExport([$request->get('fechaVentasInicio'), $request->get('fechaVentasFin')]), 'Ventas.xlsx');
    }

    public function getPagosExcel(Request $request)
    {
        return Excel::download(new PagosExport([$request->get('fechaPagosInicio'), $request->get('fechaPagosFin')]), 'Pagos.xlsx');
    }

    public function getComprasClienteExcel(Request $request)
    {
        $cliente=Cliente::where('rutCliente', $request->get('rutClienteCompra'))->first();

        return Excel::download(new ComprasClienteExport([$request->get('fechaComprasClienteInicio'), $request->get('fechaComprasClienteFinano'), $request->get('rutClienteCompra')]), 'Compras_'.$cliente->nombreCliente.'_'.$cliente->apellidoPatCliente.'_'.$cliente->apellidoMatCliente.'.xlsx');
    }

    public function getPagosClienteExcel(Request $request)
    {
        $cliente=Cliente::where('rutCliente', $request->get('rutClientePago'))->first();

        return Excel::download(new PagosClienteExport([$request->get('fechaPagosClienteInicio'), $request->get('fechaPagosClienteFin'), $request->get('rutClientePago')]), 'Pagos_'.$cliente->nombreCliente.'_'.$cliente->apellidoPatCliente.'_'.$cliente->apellidoMatCliente.'.xlsx');
    }

    public function getMoraClienteExcel(Request $request)
    {
        $cliente=Cliente::where('rutCliente', $request->get('rutClienteMora'))->first();

        return Excel::download(new MoraClienteExport([$cliente->idCliente]), 'Mora_'.$cliente->nombreCliente.'_'.$cliente->apellidoPatCliente.'_'.$cliente->apellidoMatCliente.'.xlsx');
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
