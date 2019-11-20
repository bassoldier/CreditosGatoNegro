<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use App\Models\Cliente;
use Faker\Generator as Faker;

$factory->define(Cliente::class, function (Faker $faker) {
    return [
        'rutCliente' => $faker->unique()->numberBetween($min = 100000000, $max = 999999999),
        'nombreCliente' => $faker->firstName,
        'apellidoPatCliente' => $faker->lastName, 
        'apellidoMatCliente' => $faker->lastName,  
        'telefonoCliente' => $faker->e164PhoneNumber,
        'correoCliente' => $faker->email,
        'direccionCliente' => $faker->address,
        'rutRecomendadoCliente' => $faker->numberBetween($min = 100000000, $max = 999999999),
        'fechaPagoCliente' => $faker->date('Y-m-d'),
        'fechaFacturaciónCliente' => $faker->date('Y-m-d'),
        'deudaTotalCliente' => $faker->randomNumber,
        'morosoCliente' => 0,
        'bloqueoCliente' => 0,
    ];
});
