<?php

namespace App\Exports;

use App\Models\Venta;
use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class VentasExport implements FromCollection, ShouldAutoSize, WithHeadings
{

	protected $invoices;

    public function __construct(array $invoices)
    {
        $this->invoices = $invoices;
    }
	public function headings(): array
    {
    	return [
            'ID VENTA',
            'FECHA VENTA',
            'N° BOLETA',
            'MONTO',
            'PIE',
            'N° CUOTAS',
            'MONTO CUOTA',
            'COMENTARIO',
            'ANULADA',
            'RUT CLIENTE',
            'NOMBRE CLIENTE',
            'APELLIDO PAT CLIENTE',
            'APELLIDO MAT CLIENTE',
            'NOMBRE VENDEDOR',
            'APELLIDO PAT VENDEDOR',
            'APELLIDO MAT VENDEDOR'

        ];
	}
    

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Venta::join('vendedores', 'ventas.idVendedor', '=', 'vendedores.idVendedor')->join('clientes', 'ventas.idCliente', '=', 'clientes.idCliente')->where('fechaHoraVenta','>=', $this->invoices[0])->where('fechaHoraVenta','<=', $this->invoices[1])->selectRaw('idVenta, fechaHoraVenta, numeroBoletaventa, montoPostInteresVenta, montoPieVenta, numeroDeCuotasVenta, valorCuotaVenta, comentarioVenta, notaCreditoVenta, rutCliente, nombreCliente, apellidoPatCliente, apellidoMatCliente, nombreVendedor, apellidoPatVendedor, apellidoMatVendedor')->get();
    }
}
