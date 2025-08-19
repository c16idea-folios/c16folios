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
    @foreach($extracts as $extract)
            <div style="margin-top: 25px; font-size: 14px; text-align: justify;">
              
                {{ $extract }}  
            </div>
    @endforeach
    </div>
</body>
</html>
