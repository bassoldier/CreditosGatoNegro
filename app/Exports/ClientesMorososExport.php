<?php

namespace App\Exports;

use App\models\Cliente;
use Maatwebsite\Excel\Concerns\FromCollection;


use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ClientesMorososExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'ID',
            'RUT',
            'NOMBRE',
            'APELLIDO PATERNO',
            'APELLIDO MATERNO',
            'NÚMERO DE TELÉFONO',
            'CORREO ELECTRÓNICO',
            'DOMICILIO',
            'RUT RECOMENDADO',
            'PRIMERA FECHA DE PAGO',
            'PRIMERA FECHA DE FACTURACIÓN',
            'DEUDA TOTAL A LA FECHA',
            'MOROSO',
            'BLOQUEO'

        ];
    }

    public function collection()
    {
        return Cliente::select(array('idCliente', 'rutCliente', 'nombreCliente', 'apellidoPatCliente', 'apellidoMatCliente', 'telefonoCliente', 'correoCliente', 'direccionCliente', 'rutRecomendadoCliente', 'fechaPagoCliente', 'fechaFacturacionCliente', 'deudaTotalCliente', 'morosoCliente', 'bloqueoCliente'))->where('morosoCliente', 1)->get();
    }
}
