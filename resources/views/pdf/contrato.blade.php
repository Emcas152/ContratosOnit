<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Contrato</title>
    <style>
        * {
            box-sizing: border-box;
            /* font-size: 10px; */
            font-family: sans-serif;
        }

        .contenedor {
            margin: 0;
            width: 100%;
            max-width: 800px;
        }

        .pie {
            /* position: absolute; */
            display: block;
            text-align: right;
            font-size: 11px;
            margin-top: 40px;
        }

        .pie-fin {
            display: block;
            text-align: right;
            font-size: 11px;
            margin-top: 220px;
        }

        .logoImg {
            max-width: 100%;
            height: 45px;
            margin-left: 83%;
        }

        p {
            font-size: 11px;
        }

        .titulo {
            font-size: 12px;
        }

        .negrita {
            font-weight: 700;
        }

        .textoNegrita {
            text-align: center;
            font-weight: 700;
        }

        .texto {
            text-align: justify;
        }

        .servicios {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .tituloServicio {
            text-align: center;
            font-size: 11px;
            padding: 8px 0;
            margin: 0;
            border: 1px solid black;
        }

        .agua {
            width: 40%;
        }

        .electricidad {
            width: 40%;
        }

        .page-break {
            page-break-after: always;
        }

        table {
            margin: 0 auto;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            font-size: 10px;
        }

        td,
        th {
            padding: 3px 10px;
        }

        .sinBorde {
            border: none;
        }
    </style>
</head>

<body>
    <div class="contenedor">
        {{-- <img src="https://lifebackend.swarmdesarrollo.com/storage/logo_onit.png" class="logoImg"> --}}
        <img src="storage/logo_onit.png" class="logoImg">

        <div class="contenedorTexto">

            <p class="textoNegrita">Contrato de prestación de servicios de energía eléctrica y agua</p>
            <p class="texto">
                <span class="negrita">{{ $nombre }}</span>, de <span class="negrita">{{ $edad }} años</span>, 
                @if($sexo == 'MASCULINO')
                    {{$estado_civil}}{{"O"}}{{","}}
                @else 
                    {{$estado_civil}}{{"A"}}{{","}}
                @endif @if($sexo == 'MASCULINO')
                    {{$nacionalidad}}{{"O"}}{{","}}
                @else
                    {{$nacionalidad}}{{"A"}}{{","}}
                @endif de este domicilio,
                identificado con @if ($tipo_documento == 'DPI')
                    Documento Personal de identificación (DPI){{","}}
                @else
                    PASAPORTE{{","}}
                @endif número <span class="negrita">{{ $identificacion }}</span> @if ($tipo_documento == 'DPI') 
                extendida por el Registro Nacional de las Personas –RENAP-{{","}}
                @endif con número de celular <span class="negrita">{{ $celular }}</span> y correo electrónico
                 para facturación <span class="negrita">{{ $email }}</span>; en adelante EL CLIENTE, por este medio contrata los servicios individuales que serán
                suministrados por HOGARES INTELIGENTES, SOCIEDAD ANÓNIMA, adelante el PROVEEDOR, y que se describen a
                continuación:
            </p>
            @if ($tipo_proyecto == 'VIAGGIO')
                <table class="sinBorde">
                    <tr>
                        <th colspan="2">
                            Precios Energía Eléctrica
                        </th>
                    </tr>
                    <tr>
                        <td>Deposito por contador eléctrico</td>
                        <td>Q. 700.00</td>
                    </tr>
                    <tr>
                        <td>Cargo Fijo</td>
                        <td>Q.  10.50</td>
                    </tr>
                    <tr>
                        <td>kWh (este precio varía según CNEE*)</td>
                        <td>Q.   1.41</td>
                    </tr>
                </table>
            @endif

            @if ($tipo_proyecto == 'VIVO 4')
                <table class="sinBorde">
                    <tr>
                        <th colspan="2">
                            Precios Servicio de Agua
                        </th>
                        <th class="sinBorde"></th>
                        <th colspan="2">
                            Precios Energía Eléctrica
                        </th>
                    </tr>
                    <tr>
                        <td>Cargo fijo</td>
                        <td>Q. 50.00</td>
                        <td class="sinBorde"></td>
                        <td>Deposito por contador eléctrico</td>
                        <td>Q. 400.00</td>
                    </tr>
                    <tr>
                        <td>mt3 de consumo</td>
                        <td>Q.  7.50</td>
                        <td class="sinBorde"></td>
                        <td>Cargo Fijo</td>
                        <td>Q. 10.50</td>
                    </tr>
                    <tr>
                        <td class="sinBorde"></td>
                        <td class="sinBorde"></td>
                        <td class="sinBorde"></td>
                        <td colspan="2">Precio varía según tabla CNEE*</td>
                    </tr>
                </table>
            @endif

            <p class="texto">*CNEE(Comisión Nacional de Energía Eléctrica).</p>
            <p class="texto">
                Los cuáles serán instalados en edificio <span class="negrita"> @if ($tipo_proyecto == 'VIVO 4')
                    VIVO 4, Vía 1 1-67 zona 4 Guatemala, Guatemala Torre {{ $torre }}
                @else
                    VIAGGIO, Km 13.8 Carretera Antigua a El Salvador, Muxbal Puerta Parada
                @endif, Apto. {{ $numero_apartamento }}</span> y que serán facturados a nombre, de
                <span class="negrita">{{ $nombre }}</span> con número NIT <span class="negrita">{{ $nit }}</span>
            </p>
            <p class="texto">
                <span class="negrita">Condiciones generales</span><br>
                El presente contrato regula el suministro de energía eléctrica y de agua al CLIENTE, exclusivamente para el inmueble, cuya dirección quedo plasmada al inicio del presente. El CLIENTE se adhiere a las estipulaciones contenidas en el presente contrato.
            </p>
            <p class="texto">
                <span class="negrita">Instalaciones</span><br>
                El CLIENTE hace efectivo el costo del depósito del contador. EL PROVEEDOR proveerá e instalará el o los contadores y/o medidores correspondientes. Corresponde al CLIENTE tener disponible la acometida para la energía eléctrica, así como la instalación domiciliar para el suministro de abastecimiento de agua. Todas las instalaciones a partir del punto de entrega serán efectuadas por cuenta del CLIENTE. EL PROVEEDOR no será responsable de los daños y perjuicios causado por desperfectos derivados del uso indebido de las instalaciones internas, aparatos o equipos que el CLIENTE tuviere en uso. El CLIENTE autoriza el ingreso del personal de EL PROVEEDOR, previa identificación, para inspeccionar las instalaciones, reparar, retirar, cambiar equipo de su propiedad, así como tomar lecturas y/o comprobarlas en los equipos de medición instalados, los cuales deben estar siempre accesibles.
            </p>
            <p class="texto">
                <span class="negrita">Precio, pago y facturación</span><br>
                EL PROVEEDOR de manera mensual enviará a la dirección de facturación señalada por EL CLIENTE la respectiva factura por los servicios prestados. La obligación de pago empieza a correr a partir de la fecha de conexión. EL CLIENTE se obliga a pagar los cargos por los servicios brindados antes del día 25 de cada mes, sin necesidad de cobro ni requerimiento alguno en las oficinas de EL PROVEEDOR o en los lugares y formas que EL PROVEEDOR haya designado por cualquier medio, para hacer pagos. No obstante, lo anterior, la no recepción de la factura no exime a EL CLIENTE del pago mensual correspondiente. En caso de atraso en el pago EL CLIENTE se obliga a pagar a EL PROVEEDOR un interés moratorio equivalente al cinco por ciento (5%) mensual sobre el saldo adeudado hasta el efectivo pago de este. Se cobrará un recargo de ciento cincuenta quetzales exactos (Q 150.00) por cheque rechazado. Los precios actuales pueden ser modificadas por EL PROVEEDOR en cualquier momento sin previo aviso y sin responsabilidad de su parte. EL CLIENTE acepta desde ahora cualquier incremento futuro en la tarifa y/o cargos adicionales que EL PROVEEDOR establezca en el futuro, pero se reserva el derecho a solicitar la cancelación del servicio, en cuyo caso deberá estar solvente en todos sus pagos. Para efectos de este acuerdo, se considerará la aceptación de EL CLIENTE de las nuevas tarifas y/o cargos adicionales, el pago de la primera cuenta por servicios que se haga a partir de la aplicación de la nueva tarifa y/o cargos adicionales.
            </p>
            <p class="texto">
                <span class="negrita">Medición de suministro</span><br>
                EL PROVEEDOR medirá la energía eléctrica y abastecimiento de agua a través de su equipo de medición (medidos, en el punto de entrega designada) en una fecha en el rango entre el día 1 y 15 de cada mes).  El personal designado por EL PROVEEDOR es el único facultado para instalar, remover, sustituir y ajustar los equipos de medición. El CLIENTE es responsable del equipo de medición y le será cobrado el valor de este, por su destrucción parcial o perdida del equipo (En modalidad de depósito anticipado). Al CLIENTE le queda prohibido: a) Tomar energía eléctrica o agua que no haya sido medida; b) Impedir el correcto funcionamiento del equipo de medición; c) Alterar el equipo de medición de cualquier forma; d) Reconectar el servicio por sí mismo o por medio de personas no autorizadas; e) Redistribuir energía eléctrica o agua a otros inmuebles o ramificar la conexión domiciliar sin autorización y/o utilizar los servicios para otros fines que no sean domésticos o comercializarlos;  f) EL PROVEEDOR está facultado a cobrar una multa de hasta diez mil quetzales exactos (Q 10,000.00) en caso el cliente altere de cualquier manera el sistema de medición.
            </p>
            <p class="texto">
                <span class="negrita">Plazo</span><br>
                El presente contrato tiene un plazo de duración indefinido.
            </p>
            <p class="texto">
                <span class="negrita">Suspensión</span><br>
                EL PROVEEDOR tendrá el derecho de suspender el servicio prestado a EL CLIENTE, sin responsabilidad de su parte sin necesidad de aviso previo ni declaración judicial alguna, en los siguientes casos: a) Por falta de pago de la cuota mensual correspondiente durante dos meses consecutivos; b) Por incumplimiento de EL CLIENTE a cualquiera de las prohibiciones contenidas en este contrato. En los casos anteriores EL PROVEEDOR podrá reanudar el servicio contratado una vez EL CLIENTE pague todos los saldos adeudados, subsane los incumplimientos incurridos y pague los gastos de reconexión del servicio por quinientos quetzales exactos (Q 500.00). 
            </p>
            
            <div class="pie">Página 1 de 2</div>
        </div>
    </div>

    <div class="page-break"></div>

    <div class="contenedor">
        {{-- <img src="https://lifebackend.swarmdesarrollo.com/storage/logo_onit.png" class="logoImg"> --}}
        <img src="storage/logo_onit.png" class="logoImg">

        <div class="contenedorTexto">
            <p class="texto">
                <span class="negrita">Terminación</span><br>
                Este contrato finalizará por cualquiera de las siguientes causas: a) Por voluntad del CLIENTE, manifestada por escrito; b) Si dentro de los treinta días calendario después de la suspensión del servicio por falta de pago, el CLIENTE mantiene sin pagar el costo de reconexión o cualquier otro cargo, por reincidencia en el incumplimiento de las prohibiciones establecidas en este contrato. En el caso que EL CLIENTE desee dar por terminado el presente acuerdo de servicios por cualquier motivo deberá previamente enviar aviso escrito a EL PROVEEDOR con treinta días de anticipación y previamente pagar a EL PROVEEDOR el monto adeudado por los meses de prestación del servicio que se encuentran pendiente de pago, siendo necesario e indispensable el cumplimiento de estos dos requisitos para dar por terminado el presente acuerdo. 
            </p>
            <p class="texo">
                <span class="negrita">Otras disposiciones</span><br>
                EL PROVEEDOR se obliga a: a) Proveer del servicio de energía eléctrica y agua de forma continua, las veinticuatro horas del día, todo el año, salvo casos de fuerza mayor y caso fortuito; b) Avisar al CLIENTE con la debida anticipación los cortes del servicio que deban realizarse para efectos de reparaciones o mantenimiento de las redes de distribución; c) Mantener las instalaciones internas en buenas condiciones. El CLIENTE se obliga a: a) Notificar por escrito a EL PROVEEDOR en el caso se requiera un cambio de lugar para la conexión domiciliar y/o desconexión temporal; b) Utilizar la energía eléctrica y agua de forma racional.
            </p>
            <p class="texto">
                <span class="negrita">Cesión y cambio de dirección</span><br>
                El CLIENTE no podrá ceder o traspasar de ninguna manera los derechos y obligaciones que nacen del presente acuerdo, a menos que cuente con autorización previa, expresa y por escrito de EL PROVEEDOR. EL PROVEEDOR podrá ceder total o parcialmente los derechos y obligaciones provenientes de este acuerdo sin necesidad de dar aviso previo ni posterior a EL CLIENTE. Cualquier cambio de la dirección de servicio o dirección de facturación deberá ser informado a EL PROVEEDOR para que esta proceda a realizar los cambios necesarios, en caso no se informe sobre dichos cambios, EL PROVEEDOR tiene el derecho de suspender el servicio de manera inmediata.
            </p>
            <p class="texto">
                <span class="negrita">Efectos procesales</span><br>
                EL CLIENTE acepta desde hoy como buenas y exactas las cuentas que se le presenten con motivo de este acuerdo y como líquido, ejecutivo, de plazo vencido y exigible el saldo que EL PROVEEDOR le reclame como consecuencia de este. Para el efecto EL CLIENTE renuncia al fuero del domicilio que pudiera corresponderle, sometiéndose expresamente a las leyes de la República de Guatemala, del Departamento de Guatemala, sirviéndose como título ejecutivo el presente contrato con firma legalizada y/o el acta notarial en la que conste el saldo que existiere en su contra, de acuerdo con los libros de contabilidad de EL PROVEEDOR. EL CLIENTE señala como lugar para recibir notificaciones la dirección de servicio indicada en el presente contrato.
                Yo, el CLIENTE, declaro, bajo juramento, que todos los documentos presentados son legítimos y todo lo declarado es veraz.
                Guatemala, {{ $fecha_texto }}.
            </p>
            <br>
            <br>
            <p class="texto">
                F. Cliente_________________________
            </p>
            <br>
            <p class="texto">
                En la ciudad de Guatemala el día {{ $fecha_texto }}, como NOTARIO DOY FE, que la firma que
                antecede es autentica por haber sido puesta el día de hoy en mi presencia por <span class="negrita">
                    {{ $nombre }}</span> quien se identifica con<span class="negrita"> @if ($tipo_documento == 'DPI') 
                    DPI 
                @else 
                    PASAPORTE
                @endif
                </span> con número <span class="negrita">
                    {{ $identificacion }}</span> @if ($tipo_documento == 'DPI') 
                    extendida por el registro nacional de las personas de la Republica de Guatemala -RENAP- 
                @endif .
                La firma calza un contrato de servicios de energía eléctrica y agua. El compareciente, firma nuevamente la presente
                acta de legalización, junto con el notario autorizante.
            </p>
            <br>
            <br>
            <p class="texto">
                F. Cliente_________________________
            </p>
            <div class="pie-fin">Página 2 de 2</div>
        </div>
    </div>
</body>

</html>