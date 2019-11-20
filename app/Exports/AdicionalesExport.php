<?php

namespace App\Exports;

use App\Models\Adicional;
use App\Models\Cliente;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AdicionalesExport implements FromCollection, WithHeadings, ShouldAutoSize
{
	public function headings(): array
    {
        return [
            'ID',
            'RUT',
            'NOMBRE',
            'APELLIDO PATERNO',
            'APELLIDO MATERNO',
            'RUT TITULAR',
            'NOMBRE TITULAR',
            'APELLIDO PATERNO TITULAR',
            'APELLIDO MATERNO TITULAR',
            'NÚMERO DE TELÉFONO',
            'CORREO ELECTRÓNICO',
            'DOMICILIO',
            'PRIMERA FECHA DE PAGO TITULAR',
            'PRIMERA FECHA DE FACTURACIÓN TITULAR',
            'DEUDA TOTAL TITULAR',
            'MOROSO TITULAR',
            'BLOQUEO TITULAR',
            'BLOQUEO ADICIONAL'

        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Adicional::join('clientes', 'adicionales.idCliente', 'clientes.idCliente')->select(array('idAdicional', 'rutAdicional', 'nombreAdicional', 'apellidoPatAdicional', 'apellidoMatAdicional', 'rutCliente', 'nombreCliente', 'apellidoPatCliente', 'apellidoMatCliente', 'telefonoAdicional', 'correoAdicional', 'direccionAdicional', 'fechaPagoCliente', 'fechaFacturacionCliente', 'deudaTotalCliente', 'morosoCliente', 'bloqueoCliente', 'bloqueoAdicional'))->get();
    }
}
