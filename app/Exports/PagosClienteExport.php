<?php

namespace App\Exports;

use App\Models\Abono;
use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PagosClienteExport implements FromCollection, ShouldAutoSize, WithHeadings
{
	protected $invoices;

    public function __construct(array $invoices)
    {
        $this->invoices = $invoices;
    }
	public function headings(): array
    {
    	return [
            'ID ABONO',
            'FECHA ABONO',
            'MONTO ABONO',
            'RUT CLIENTE',
            'NOMBRE CLIENTE',
            'APELLIDO PAT CLIENTE',
            'APELLIDO MAT CLIENTE'

        ];
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Abono::join('clientes', 'abonos.idCliente', '=', 'clientes.idCliente')->where('fechaAbono','>=', $this->invoices[0])->where('fechaAbono','<=', $this->invoices[1])->where('rutCliente', $this->invoices[2])->selectRaw('idAbono, fechaAbono, montoAbono,rutCliente, nombreCliente, apellidoPatCliente, apellidoMatCliente')->get();
    }
}
