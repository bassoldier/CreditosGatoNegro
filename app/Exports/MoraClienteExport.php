<?php

namespace App\Exports;

use App\Models\RegistrosMora;
use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MoraClienteExport implements FromCollection, ShouldAutoSize, WithHeadings
{
	protected $invoices;

    public function __construct(array $invoices)
    {
        $this->invoices = $invoices;
    }
	public function headings(): array
    {
    	return [
            'ID MORA',
            'MONTO VENCIDO MORA',
            'FECHA INGRESO A MORA',
            'FECHA SALIDA DE MORA',
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
        return RegistrosMora::join('clientes', 'registros_mora.idCliente', '=', 'clientes.idCliente')->where('clientes.idCliente', $this->invoices[0])->selectRaw('idMora, montoVencidoMora, fechaIngresoMora, fechaSalidaMora, rutCliente, nombreCliente, apellidoPatCliente, apellidoMatCliente')->get();
    }
}
