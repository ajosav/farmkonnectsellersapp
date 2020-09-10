<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>New Order Received.</title>

    <!-- Start Common CSS -->
    <style type="text/css">
        #outlook a {
            padding: 0;

        }

        a {
            color: green;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
            font-family: Helvetica, arial, sans-serif;
        }

        .ExternalClass {
            width: 100%;
        }

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }

        .backgroundTable {
            margin: 0;
            padding: 0;
            width: 100% !important;
            line-height: 100% !important;
        }

        .main-temp table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
            font-family: Helvetica, arial, sans-serif;
        }

        .main-temp table td {
            border-collapse: collapse;
        }
    </style>
    <!-- End Common CSS -->
</head>

<body>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="backgroundTable main-temp"
        style="background-color: #d5d5d5;">
        <tbody>
            <tr>
                <td>
                    <table width="600" align="center" cellpadding="15" cellspacing="0" border="0" class="devicewidth"
                        style="background-color: #ffffff;">
                        <tbody>
                            <!-- Start header Section -->
                            <tr>
                                <td style="padding-top: 30px;">
                                    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0"
                                        class="devicewidthinner"
                                        style="border-bottom: 1px solid #eeeeee; text-align: center;">
                                        <tbody>
                                            <tr>
                                                <td style="padding-bottom: 10px;">
                                                    <a href="https://farmkonnectng.com"><img
                                                            src="{{ asset('images/logo/logo.jpeg') }}"
                                                            alt="FarmKonnect Agribusiness" /></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- End header Section -->

                            <tr>
                                <td style="padding-top: 0;">
                                    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0"
                                        class="devicewidthinner" style="border-bottom: 1px solid #bbbbbb;">
                                        <tbody>
                                            <tr>
                                                <p style="text-align: center;">Hello {{ $order->user->$role->name }}.
                                                    One of your orders on FarmKonnect Sellers App has just been
                                                    confirmed by the vendor. Kindly login to your account to request
                                                    delivery of the product from the orders section.</p>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 0;">
                                    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0"
                                        class="devicewidthinner" style="border-bottom: 1px solid #bbbbbb;">
                                        <tbody>
                                            <tr>
                                                <h4 style="text-align: center;">Order Details.</h4>
                                                <div style="text-align: left;">
                                                    <ul>
                                                        <li>
                                                            {{  $order->quantity_ordered.' '.$order->unit->unit_name.'(s)' }}
                                                            of
                                                            {{ $order->product->name }} from
                                                            {{ $order->product->owner->name }}
                                                            at a total price of
                                                            &#8358;{{ number_format($order->total_price, 2) }}.
                                                        </li>
                                                    </ul>
                                                </div>
                                            </tr>
                                            <small style="font-weight: bold; margin-top: 50px;">Kindly send an email to
                                                <a href="mailto:support@farmkonnectng.com">support@farmkonnectng.com</a>
                                                or call <a href="tel:+2349059102364">+2349059102364</a> for more
                                                information.</small>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
