<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

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

class PdfController extends Controller
{
    public function crearPDFVenta(Request $request)
    {
    	$cuotas=DB::table('cuotas')->join('deuda_mensual', 'cuotas.idDeudaMensual', '=', 'deuda_mensual.idDeudaMensual')->where('idVenta', $request->get('auxIdVenta'))->orderby('fechaVencimientoDeudaMensual', 'ASC')->get();



    	$datosCliente = Adicional::where('rutAdicional', $request->get('rutVentaDet'))->join('clientes', 'adicionales.idCliente', '=', 'clientes.idCliente')->first();
            
        if($datosCliente){
        	$fono=$datosCliente->telefonoAdicional;
        	$domicilio=$datosCliente->direccionAdicional;
        	$idCliente=$datosCliente->idCliente;

        }
        else{
        	$datosCliente = Cliente::where('rutCliente', $request->get('rutVentaDet'))->first();
        	$fono=$datosCliente->telefonoCliente;
        	$domicilio=$datosCliente->direccionCliente;
        	$idCliente=$datosCliente->idCliente;
        }


    	

    	$inputProducto="textoProducto1";
        $statusProducto = true;
        $i=1;
        $c=1;


    	
		    $html="	<html lang='es'>
				<head>
				<style type='text/css'>

			h1{
				font-size: 20px;
				margin-top: 0px;
				margin-bottom: 0px;
			}

			h2{
				font-size: 14px;
				margin-top: 0px;
				margin-bottom: 0px;
			}

			body{
				/* 
				Sólo para referencia del tamaño de la página.
				border: solid 1px; 
				*/

			}

			.seccion{
				width: 100%;
				float: left;
			}

			#imagenGato{
				float: left;
				display: inline-block;
			}

			#textoTitulo{
				/*float: left;*/
			}

			#datosCliente{
				width: 100%;
				margin-top: 30px;
				float: left;
			}

			#tablaEncabezado tr td{
				font-size: 10px;
			}

			.w100{
				width: 100%;
			}

			.w80{
				width: 80%;
			}

			.w60{
				width: 60%;
			}

			.w40{
				width: 40%;
			}

			.w30{
				width: 30%;
			}

			.centrarTexto{
				text-align: center;
			}

			.izquierda{
				float: left;
			}

			.derecha{
				float: right;
			}

			.centrar{
				margin: auto;
			}

			.margenIzquierdo25{
				margin-left: 25%;
			}

			.margenIzquierdo10{
				margin-left: 10%;
			}

			.margenDerecho10{
				margin-right: 10%;
			}

			.margenArriba25{
				margin-top: 25px;
			}
			.margenArriba70{
				margin-top: 70px;
			}
			.bordeArriba{
				border-top: solid 1px;
			}

			#tablaCuotas{
				border-collapse: collapse;
			}

			#tablaCuotas tr td{
				border: solid 1px;
			}


			#tablaDescripcionProductos{
				border-collapse: collapse;
			}
			
			#tablaCuotas2{
				border-collapse: collapse;
			}
			
			#tablaCuotas2 tr td{
				border: solid 1px;
			}

			</style>
				</head>
		<body >

		<div id='divPrincipal' class='derecha'>
		<!-- Encabezado -->
			<div class='seccion'>
				<table style='width: 100%;'>
				<tr>
				
				<td id='idTd1' style='width:45%;' >
				<table style='width:100%; '>
					<tr>
					<td>
						<div id='imagenGato'>
						<img src='".asset('imgPDFs/imagen_gato.png')."'>
						</div>
					</td>
					<td>
						<div id='textoTitulo'>
					<h2>Tienda</h2>
					<h1>El Gato Negro</h1>
					<table id='tablaEncabezado'>
						<tr>
							<td colspan='2'>VENTA DE LANAS, HILOS, TELAS, ELECTROD</td>
						</tr>
						<tr>
							<td colspan='2'>Y ELECTRONIC, MUEBLES Y OTROS</td>
						</tr>
						<tr>
							<td colspan='2'>SOCIEDAD COMERCIAL CONA Y SUAZO LTDA</td>
						</tr>
						<tr>
							<td>RUT:</td>
							<td>76.312.498-3</td>
						</tr>
						<tr>
							<td>FONO:</td>
							<td>2178791 - 2178792</td>
						</tr>
					</table>
				</div>
					
					</td>
					</tr>
					<tr style='height: 10px;'>
						<td><br></td>
						<td><br></td>
					</tr>
					<tr>
						<td>
							<div class='izquierda centrarTexto'>
								<b id='bNombreCliente'> Cliente: ".$request->get('nombreVentaDet')."</b>
							</div>
						</td>
						<td style='text-align: right;'>
							<div>
								<b>FECHA: </b><span id='spanFechaCompra'>".str_replace("T", " ", $request->get('fechaHoraVentaDet'))."</span>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class='izquierda centrarTexto'>
								<b id='bNombreCliente'> Titular: ".$request->get('nombreTitularVentaDet')."</b>
							</div>
						</td>
					</tr>
					<tr style='height: 10px;'>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan='2' class='centrar'>
							<div class='izquierda w100 margenArriba25'>
							<div class='izquierda margenIzquierdo10'>
								<table>
									<tr>
										<td>RUT:</td>
										<td id='tdRut'>".$request->get('rutVentaDet')."</td>
									</tr>
									<tr>
										<td>FONO:</td>
										<td id='tdFono'>".$fono."</td>
									</tr>
									<tr>
										<td>DOMICILIO:</td>
										<td id='tdDomicilio'>".$domicilio."</td>
									</tr>
									<tr>
										<td>COMENTARIO:</td>
										<td id='tdComentario'>".$request->get('comentarioVentaDet')."</td>
									</tr>
								</table>
							</div>
							</div>
						</td>
					</tr>
					<tr style='height: 10px;'>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan='2'>
							<div class='centrar'>
								<table id='tablaCuotas' cellpadding='2' style='width: 100%;'>";
								foreach ($cuotas as $cuota){
									
										$html.=	"<tr>";
										$html.= '<td>CUOTA '.$c.' : $'.$cuota->montoCuota.'</td>';
										$html.= '<td style="text-align:right;">VENCE : '.$cuota->fechaVencimientoDeudaMensual.'</td>';
										$html.=	"</tr>";
									$c++;
								}

								
				 $html .="</table>
							</div>
						</td>
					</tr>
					<tr style='height: 10px;'>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>
							<div class='izquierda'>
								VENTA POR: <span id='bMontoCompra'>$".$request->get('montoVentaDet')."</span>
							</div>
						</td>
						<td style='text-align: right;'>
							<div class='derecha'>
								<span>BOLETA: </span><span id='spanNumeroBoleta'>".$request->get('nboletaDet')."</span>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class='izquierda w80 centrar'>
								<table>
									<tr>
										<td>PIE:</td>
										<td id='tdPie'>$".$request->get('montoPieVentaDet')."</td>
									</tr>
									<tr>
                                        <td><b>TOTAL FINAL:</b></td>
                                        <td id='tdPie'>$".$request->get('deudaFinalVentaDet')."</td>
                                    </tr>
									<tr style='height: 10px;'>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td colspan='2'></td>
									</tr>
								</table>
								<div id='divDescripcionProductos'>";
								while($request->has($inputProducto)){

			                        $html.= $request->get($inputProducto)."<br>";

			                        $inputProducto = trim($inputProducto, $i);
			                        $i++;
			                        $inputProducto = $inputProducto.$i;
			                        //echo $i;
			                    }
								
								$html .="</div>
							</div>
						</td>
					</tr>
					<tr style='height: 50px;padding: 20px;'>
						<td>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br></td>
						<td>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br></td>
					</tr>
					<tr>
				
						<td style='text-align: center;' colspan='2'>
							<div class='centrar' >
							<table >
								<tr>
									<td class='bordeArriba centrarTexto' id='tdNombreCliente'>".$request->get('nombreVentaDet')."</td>
								</tr>
								<tr>
									<td class='centrarTexto'>
										CLIENTE
									</td>
								</tr>
							</table>
							</div>
						</td>
					</tr>
				</table>
				</td>
				<td id='idTd3' style='width:45%; '></td>
				</td>
				<td id='idTd2' style='width:10%; '>
				</td>
				</tr>
				
				</table>
			
			
			</div>

			</div>
		</body>
		<script type='text/javascript'>
			window.print();
		</script>
		</html>";

        $ruta=asset('tempPDF/venta.pdf');
		echo $ruta;
		$mpdf=new mPDF(['default_font_size'=> '0', 'format' => 'Letter-L', 'margin_left' => '160', 'margin_right' => '-110', 'margin_top' => '0', 'margin_bottom' => '20', 'margin_header' => '5', 'margin_footer' => '0', 'orientation' => 'L']);
		$mpdf->SetJS('this.print();');
		$mpdf->WriteHTML($html);
		$mpdf->Output('../public/tempPDF/venta.pdf','F');
		return redirect()->back()->with('print', 'La venta se está imprimiendo.'); 
		
    }
}


