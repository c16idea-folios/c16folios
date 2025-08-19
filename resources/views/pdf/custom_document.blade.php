<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo</title>
    <style>
        html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #000;
            width: 100%;
            height: 100%;
        }


        .container {
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
            padding: 40px;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            position: relative;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
            margin-top: 20px;
        }
        .header p {
            margin: 5px 0;
            font-size: 16px;
        }
        .header .number {
            position: absolute;
            top: -20px;
            right: 0;
            font-weight: bold;
            font-size: 19px;
            color:rgb(216, 0, 54);
        }
        .content {
            margin-top: 20px;
            font-size: 16px;
            line-height: 1.5;
        }
        .content ul {
            list-style-type: disc;
            padding-left: 50px;
        }
        .content ul li {
            margin: 5px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
        }
        .signature {
            text-align: center;
            margin-top: 40px;
            font-weight: 18px;
        }
        .signature .line {
            margin-top: 60px;
            border-top: 1px solid #000;
            width: 300px;
            margin-left: auto;
            margin-right: auto;
        }
        .signature p:last-of-type {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>LIC. RENE GURMILAN SÁNCHEZ</h1>
            <p>Corredor Público Número 16 del Estado de México<br>
            Viveros de Coyoacán 210, Colonia Viveros de la Loma<br>
            Tlalnepantla de Baz, Estado de México CP. 54080<br>
            <a href="http://www.correduria16.com">www.correduria16.com</a><br>
            Tel. 536 - 63 - 10</p>
            <p class="number">{{$num}}</p>
        </div>
        <div class="content">
            <p style="text-align: right;">{{$formatted_date}}</p>
            <br>
            <p>Recibimos de: {{$received_from}}</p>
            <p>La cantidad de {{$amount_paid_text}} por concepto de:</p>
            <br>
      

            <ul>
                <li>{{$act}}</li>
                <li>{{$client}}</li>
                <li>Póliza {{$policy_id}}</li>
            </ul>
            <br>
            <br>
            <p>Si requiere factura, se agregará el 16% del total, y enviar datos fiscales, ya que solo se facturarán al mes corriente.</p>
       
          
        </div>
        <div class="signature">
            <p>ATENTAMENTE</p>
            <br><br> <br><br> <br>
            <div class="line"></div>
            <p style="font-weight: bold;">CORREDURÍA PÚBLICA 16<br>PLAZA ESTADO DE MEXICO</p>
        </div>
    </div>
</body>
</html>
