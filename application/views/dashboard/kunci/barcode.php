<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loker</title>
</head>
<style>
    body {
        margin: 0;
        font-size: 18px;
        background-color: #fff;
        font-weight: bold;
        padding-left: 5mm;
        text-align: center;
    }

    td {
        font-size: 20px;
        text-align: center;
    }

    .sheet {
        margin: 0;
        overflow: hidden;
        position: relative;
        box-sizing: border-box;

    }

    /** Paper sizes **/
    body.struk .sheet {
        width: 70mm;
        height: 100mm;
    }

    body.struk .sheet {
        padding: 2mm;
    }

    .img_barcode {
        width: auto;
        text-align: center;

    }

    /** For screen preview **/
    @media screen {
        .sheet {
            background: white;
            box-shadow: 0 .5mm 2mm rgba(0, 0, 0, .3);
            margin: 5mm;
        }
    }

    /** Fix for Chrome issue #273306 **/
    @media print {

        body.struk {
            width: 140mm;
        }

        body.struk .sheet {
            padding: 2mm;
        }

        .txt-left {
            text-align: left;
        }

        .txt-center {
            text-align: center;
        }

        .txt-right {
            text-align: right;
        }
    }
</style>

<body class="struk">
    <section class="sheet">
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td class="img_barcode">
                    <img src="<?php echo base_url() . $image_barcode ?>" class="center" style="width: 170px;height:45px;text-align:center">
                    <span style ="font-size:10px"> <?php echo $no_barcode ?></span>
                </td>
            </tr>
        </table>
    </section>
</body>

</html>