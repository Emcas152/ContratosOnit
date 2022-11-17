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
            right: 50px;
            bottom: 20px;
            position: absolute;
            display: block;
            text-align: right;
            font-size: 11px;
        }

        /* .fin {
                margin-top: 450px;
            } */

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
        <img src="https://lifebackend.swarmdesarrollo.com/storage/logo_onit.png" class="logoImg">

        <div class="contenedorTexto">

            <p class="textoNegrita">Contrato de prestación de servicios de energía eléctrica y agua</p>
            <p class="texto">
                <span class="negrita">{{ $nombre }}</span>, de <span class="negrita">{{ $edad }} años</span>,
                {{ $estado_civil }}, {{ $nacionalidad }}, de este domicilio, identificado
                con Documento Personal de identificación (DPI) número <span class="negrita">{{ $identificacion }}
                </span> extendida por el Registro Nacional de las Personas –RENAP, con numero de celular 
                <span class="negrita">{{ $celular }}</span>
                y correo electrónico <span class="negrita">{{ $email }}</span>;en adelante EL CLIENTE, por este medio
                contrata los servicios individuales que
                serán suministrados por HOGARES INTELIGENTES, SOCIEDAD ANÓNIMA, adelante el PROVEEDOR, y que se
                describen a continuación:
            </p>
            <table class="sinBorde">
                <tr>
                    <th colspan="3">
                        Servicio de Internet Residencial Burst
                    </th>
                    <th class="sinBorde"></th>
                    <th colspan="3">
                        Servicio de Internet Residencial Simétrico
                    </th>
                </tr>
                <tr>
                    <td></td>
                    <td>20 Mbps</td>
                    <td>Q. 299.00</td>
                    <td class="sinBorde"></td>
                    <td></td>
                    <td>10 Mbps</td>
                    <td>Q. 239.00</td>
                </tr>
                <tr>
                    <td></td>
                    <td>30 Mbps</td>
                    <td>Q. 349.00</td>
                    <td class="sinBorde"></td>
                    <td></td>
                    <td>20 Mbps</td>
                    <td>Q. 349.00</td>
                </tr>
                <tr>
                    <td></td>
                    <td>50 Mbps</td>
                    <td>Q. 399.00</td>
                    <td class="sinBorde"></td>
                    <td></td>
                    <td>30 Mbps</td>
                    <td>Q. 399.00</td>
                </tr>
                <tr>
                    <td></td>
                    <td>100 Mbps</td>
                    <td>Q. 499.00</td>
                    <td class="sinBorde"></td>
                    <td></td>
                    <td>50 Mbps</td>
                    <td>Q. 599.00</td>
                </tr>
                <tr>
                    <td></td>
                    <td>200 Mbps</td>
                    <td>Q. 699.00</td>
                    <td class="sinBorde"></td>
                    <td></td>
                    <td>100 Mbps</td>
                    <td>Q. 1,099.00</td>
                </tr>
            </table>

            <p class="texto">
                Los cuáles serán instalados en edificio <span class="negrita">VIAGGIO, Km 13.8 Carretera Antigua a El
                    Salvador, Muxbal Puerta
                    Parada</span> Apto. {{ $numero_apartamento }} y que serán facturados a nombre de <span
                    class="negrita">{{ $nombre }}</span>
                con NIT <span class="negrita">{{ $nit }}</span>
            </p>
            <p class="texto">
                <span class="negrita">Condiciones generales</span><br>
                El presente contrato regula el suministro de servicios de telecomunicaciones al CLIENTE, exclusivamente
                para el inmueble, cuya dirección quedo plasmada al inicio del presente. El CLIENTE se adhiere a las
                estipulaciones contenidas en el presente Contrato.
            </p>
            <p class="texto">
                <span class="negrita">Instalaciones</span><br>
                EL PROVEEDOR proveerá e instalará el o los equipos correspondientes al servicio contratado.
                EL PROVEEDOR cobrará a EL CLIENTE un costo de instalación por los servicios contratados, dicho costo
                será informado a EL CLIENTE previo a que EL PROVEEDOR realice la instalación. En este caso EL PROVEEDOR
                podrá, a su discreción y si así lo considera conveniente, acreditar el costo de instalación a la primera
                factura que sea emitida al CLIENTE
                EL PROVEEDOR no será responsable de los daños y perjuicios causado por desperfectos derivados del uso
                indebido de las instalaciones internas, aparatos o equipos que el CLIENTE tuviere en uso. El CLIENTE
                autoriza el ingreso del personal de EL PROVEEDOR, previa identificación, para inspeccionar las
                instalaciones, reparar, retirar, cambiar equipo de su propiedad, los cuales deben estar siempre
                accesibles.
            </p>
            <p class="texto">
                <span class="negrita">Equipos</span><br>
                Todos los equipos proporcionados e instalados en la dirección en que EL CLIENTE así lo solicite son
                propiedad exclusiva de EL PROVEEDOR, lo cual quedará evidenciado en la nota de entrega de los mismo. En
                el caso de terminación del presente acuerdo los equipos deberán ser inmediatamente devueltos por el
                CLIENTE a EL PROVEEDOR, para lo cual EL CLIENTE desde ya autoriza expresamente a EL PROVEEDOR para que
                por medio de las personas que designe pueda acudir en días y horas hábiles a la dirección en la cual se
                encuentran los equipos instalados para su debida desinstalación y retiro. EL PROVEEDOR no será
                responsable de ninguna manera por la eventual suspensión temporal en los servicios que pudiera derivarse
                por desperfectos técnicos en los equipos. EL CLIENTE declara expresamente que libera a EL PROVEEDOR de
                cualquier responsabilidad por este motivo.
            </p>
            <p class="texto">
                <span class="negrita">Precio, pago y facturación</span><br>
                EL PROVEEDOR de manera mensual enviará a la dirección de facturación señalada por EL CLIENTE la
                respectiva factura por los servicios prestados. La obligación de pago empieza a correr a partir de la
                fecha de conexión. EL CLIENTE se obliga a pagar los cargos por los servicios brindados dentro de los
                primeros 20 días de cada mes, sin necesidad de cobro ni requerimiento alguno en las oficinas de EL
                PROVEEDOR o en los lugares y formas que EL PROVEEDOR haya designado por cualquier medio, para hacer
                pagos. No obstante, lo anterior, la no recepción de la factura no exime a EL CLIENTE del pago mensual
                correspondiente. En caso de atraso en el pago EL CLIENTE se obliga a pagar a EL PROVEEDOR un interés
                moratorio equivalente a los dos puntos cinco por ciento mensual sobre el saldo adeudado hasta el
                efectivo pago de este. Se cobrará un recargo de ciento cincuenta quetzales exactos (Q150.00) por cheque
                rechazado. Los precios actuales pueden ser modificadas por EL PROVEEDOR en cualquier momento sin previo
                aviso y sin responsabilidad de su parte. EL CLIENTE acepta desde ahora cualquier incremento futuro en la
                tarifa y/o cargos adicionales que EL PROVEEDOR establezca en el futuro, pero se reserva el derecho a
                solicitar la cancelación del servicio, en cuyo caso deberá estar solvente en todos sus pagos. Para
                efectos de este acuerdo, se considerará la aceptación de EL CLIENTE de las nuevas tarifas y/o cargos
                adicionales, el pago de la primera cuenta por servicios que se haga a partir de la aplicación de la
                nueva tarifa y/o cargos adicionales.
            </p>
            <p class="texto">
                <span class="negrita">Condiciones especiales de los servicios</span><br>
                El servicio Internet y de Telefonía se rige bajo las siguientes condiciones:
                i) EL CLIENTE libera de cualquier responsabilidad a EL PROVEEDOR por los daños de cualquier tipo
                ocurridos en el domicilio de instalación y en los equipos electrónicos: Incluso debido a descargas
                eléctricas; ii) el servicio será proporcionado de manera continua las veinticuatro horas del día, todos
                los días del año, durante la vigencia de este acuerdo. Sin embargo, podrán presentarse interrupciones en
                el servicio las cuales pueden ser causadas por circunstancias ajenas al control de la Empresa,
                incluyendo, pero no limitando acaso fortuito, cualquier evento o siniestro natural o fuerza mayor. EL
                CLIENTE declara que libera expresamente a EL PROVEEDOR de toda responsabilidad por este motivo. En todo
                caso EL PROVEEDOR se compromete a restablecer el servicio en el menor tiempo posible; iii) La Empresa
                podrá suspender temporalmente los servicios prestados sin responsabilidad de su parte, en caso de
                mantenimiento de cualquier tipo, falta de pago de los servicios, por casos fortuitos y/o fuerza mayor.
            </p>
            <p class="texto">
                En el caso específico del servicio de internet:
            </p>
            <p class="texto">
                EL CLIENTE se obliga a no sub-distribuir y/o retransmitir los servicios proporcionados por EL PROVEEDOR
                y se obliga a no alterar, modificar o remover los equipos y/o la instalación efectuada por EL PROVEEDOR.
                Asimismo, EL CLIENTE se obliga a denunciar estas prácticas cuando terceras personas las realicen, siendo
                responsable de los daños y perjuicios que puede causar a EL PROVEEDOR por incumplimiento de dicha
                obligación. EL PROVEEDOR no se responsabiliza del contenido que sea transmitido por EL CLIENTE o
                terceras personas a través del enlace. EL PROVEEDOR se compromete a mantener un SLA de 85%.
            </p>
            <p class="texto">
                En el caso específico del servicio de telefonía fija:
                i) La terminación del presente acuerdo o sus anexos implica la desactivación del número puesto a
                disposición del CLIENTE; ii) EL servicio comprende: servicio de telefonía fija con derecho a efectuar
                llamadas locales, servicio de transmisión de datos y/o voz y cualquier otro servicio de valor agregado;
                iii) para el caso del servicio de llamadas nacionales EL CLIENTE desde ya declara expresamente que
                conoce y acepta las tarifas que por utilización de dicho servicio aplica EL PROVEEDOR, las cuales
                reconoce el CLIENTE que pueden ser variadas en cualquier momento sin responsabilidad alguna para EL
                PROVEEDOR.
            </p>
            <div class="pie">Página 1 de 2</div>
        </div>
    </div>

    <div class="page-break"></div>

    <div class="contenedor">
        <img src="https://lifebackend.swarmdesarrollo.com/storage/logo_onit.png" class="logoImg">

        <div class="contenedorTexto">
            <p class="texo">
                <span class="negrita">Plazo</span><br>
                El presente contrato tiene un plazo de duración indefinido.
            </p>
            <p class="texto">
                <span class="negrita">Suspensión</span><br>
                EL PROVEEDOR tendrá el derecho de suspender el servicio prestado a EL CLIENTE, sin responsabilidad de su
                parte sin necesidad de aviso previo ni declaración judicial alguna, en los siguientes casos. i) por
                falta de pago de la cuota mensual correspondiente durante dos meses consecutivos. ii) por incumplimiento
                de EL CLIENTE a cualquiera de las prohibiciones contenidas en este contrato. En los casos anteriores EL
                PROVEEDOR podrá reanudar el servicio contratado una vez EL CLIENTE pague todos los saldos adeudados,
                subsane los incumplimientos incurridos y pague los gastos por la reconexión del servicio por un monto de
                Q.200.00.
            </p>
            <p class="texto">
                <span class="negrita">Terminación</span>
                Este contrato terminara por cualquiera de las siguientes causas: a) Por voluntad del CLIENTE,
                manifestada por escrito un mes antes de cancelar el contrato b) si dentro de los treinta días calendario
                después de la suspensión del servicio por falta de pago, el CLIENTE mantiene sin pagar el costo de
                reconexión de Q.200.00 o cualquier otro cargo; por reincidencia en el incumplimiento de las
                prohibiciones establecidas en este contrato. En el caso que EL CLIENTE desee dar por terminado el
                presente acuerdo de servicios por cualquier motivo deberá previamente enviar aviso escrito a EL
                PROVEEDOR con treinta días de anticipación y deberá previamente pagar a EL PROVEEDOR el monto adeudado
                por los meses de prestación del servicio que se encuentran pendiente de pago, siendo necesario e
                indispensable el cumplimiento de estos dos requisitos para dar por terminado el presente Acuerdo.
            </p>
            <p class="texto">
                <span class="negrita">Otras disposiciones</span>
                EL PROVEEDOR se obliga a: a) Proveer del servicio contratado de forma continua, las veinticuatro horas
                del día, todo el año, salvo casos de fuerza mayor y caso fortuito. b) Avisar al CLIENTE con la debida
                anticipación los cortes del servicio que deban realizarse para efectos de reparaciones o mantenimiento
                de las redes de distribución. c) Mantener las instalaciones internas en buenas condiciones; El CLIENTE
                se obliga a: a) Notificar por escrito a EL PROVEEDOR en el caso se requiera un cambio de lugar para la
                conexión domiciliar, y/o desconexión temporal.
            </p>
            <p class="texto">
                <span class="negrita">Cesión y cambio de dirección </span>
                El CLIENTE no podrá ceder o traspasar de ninguna manera los derechos y obligaciones que nacen del
                presente acuerdo, a menos que cuente con autorización previa, expresa y por escrito de EL PROVEEDOR. EL
                PROVEEDOR podrá ceder total o parcialmente los derechos y obligaciones provenientes de este acuerdo sin
                necesidad de dar aviso previo ni posterior a EL CLIENTE. Cualquier cambio de la dirección de servicio o
                dirección de facturación deberá ser informado a EL PROVEEDOR para que esta proceda a realizar los
                cambios necesarios, en caso no se informe sobre dichos cambios, EL PROVEEDOR tiene el derecho de
                suspender el servicio de manera inmediata.
            </p>
            <p class="texto">
                <span class="negrita">Efectos procesales</span>
                EL CLIENTE acepta desde hoy como buenas y exactas las cuentas que se le presenten con motivo de este
                acuerdo y como líquido, ejecutivo, de plazo vencido y exigible el saldo que EL PROVEEDOR le reclame como
                consecuencia de este. Para el efecto EL CLIENTE renuncia al fuero del domicilio que pudiera
                corresponderle, sometiéndose expresamente a las leyes de la República de Guatemala, del Departamento de
                Guatemala, sirviéndose como título ejecutivo el presente contrato con firma legalizada y/o el acta
                notarial en la que conste el saldo que existiere en su contra, de acuerdo con los libros de contabilidad
                de EL PROVEEDOR. EL CLIENTE señala como lugar para recibir notificaciones la dirección de servicio
                indicada en el presente contrato.
                Yo, el CLIENTE, declaro, bajo juramento, que todos los documentos presentados son legítimos y todo lo
                declarado es veraz.
            </p>
            <p class="texto">
                Guatemala, 01 de septiembre de 2022
            </p>
            <br>
            <br>
            <p class="texto">
                F. Cliente_________________________
            </p>
            <br>
            <p class="texto">
                En la ciudad de <span class="negrita">Guatemala el día 01 de septiembre del año 2022</span>, como NOTARIO DOY FE, que la firma que
                antecede es autentica por haber sido puesta el día de hoy en mi presencia de <span class="negrita">{{ $nombre }}</span>
                quien se identifica con <span class="negrita">DPI</span> con número <span class="negrita">3{{ $identificacion }}</span> extendida
                por el Registro Nacional de las personas -RENAP-. La firma calza un contrato de servicios de telecomunicaciones. El compareciente, 
                firma nuevamente la presente acta de legalización, junto con el notario autorizante.
            </p>
            <br>
            <br>
            <p class="texto">
                F. Cliente_________________________
            </p>
            <div class="pie fin">Página 2 de 2</div>
        </div>
    </div>
</body>

</html>