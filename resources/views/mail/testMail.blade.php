<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "https://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
    <meta name="viewport" content="target-densitydpi=device-dpi,width=device-width,maximum-scale=1.0,user-scalable=yes">
    <meta http-equiv="Content-Type" content="text/html; charset=iso-2022-jp" />
    <meta http-equiv="Content-Language" content="en">
    <title>C.C.Medico Thank you for your order.</title>
    <style type="text/css">
        html {
            width: 100%;
            height: 100%;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        th,
        td .line {
            border: solid 1px #333;
            /* 線の種類 太さ 色 */
        }

        .footer_area {
  position: fixed; /* 固定 */
  bottom: 0; /* 一番下に表示 */
  width: 100%; /* 中央に表示 */
}
    </style>
</head>

<body style="width:100%;height:100%;margin:0;padding:0;">


    <!-- 全体の背景色を指定するためのtable -->
    <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#ededed"
        style="width:100%;height:100%;background-color:#ededed;">
        <td valign="top">
            <!-- 横幅を指定するためのtable -->
            <table width="600" align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff"
                style="width:600px;background-color:#ffffff;">
                <!-- コンテンツを記述するためのtable -->
                <table width="100%" border="0" cellpadding="20" cellspacing="0">
                    <tr>
                        <td valign="top" align="left">

                            <!-- ヘッダーエリア -->
                            <table width="100%" border="0" cellpadding="20" cellspacing="0">
                                <tr>
                                    <td class="responsive-td" valign="top" align="left"
                                        style="background-color:#000000;">
                                        <img src="https://www.ccmedico.com/fedex/storage/ccm.jpg" alt="ccmedico logo"
                                            width="120" height="38" style="max-width:100%;color:#ffffff;">
                                    </td>
                                </tr>
                            </table>
                            <!-- コンテンツエリア -->
                            <br><br>
                            <table width="100%" border="0" cellpadding="10" cellspacing="0">
                                <tr>
                                    <td class="responsive-td" valign="top" align="center">
                                        Dear {{ $name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="responsive-td" valign="top" align="center">
                                        <?= html_entity_decode(nl2br(e($content))); ?>
                                    </td>
                                </tr>
                            </table>








                            <!-- フッターエリア -->
                            <div class="footer_area">
                            <table width="100%" border="0" cellpadding="20" cellspacing="0" footer>
                                <tr>
                                    <td class="responsive-td" valign="top" align="left"
                                        style="background-color:#000000;">
                                        <font size="2" color="#ffffff" style="font-size:14px;color:#ffffff;">
                                            C.C.Medico Co.,Ltd.<br>
                                            1-12-1 sibuy markcity W22 JP, Dogenzaka,, Shibuya Ku,, Tokyo,, 150-0043
                                            Japan<br>
                                            phone：<a href="tel:+81-3-6897-4086"
                                                style="color:#ffffff;">+81-3-6897-4086</a><br>
                                            <br>
                                            Copyright c 2022 C.C. Medico Co.,Ltd. All Rights Reserved.
                                        </font>
                                    </td>
                                </tr>
                            </table>
                            </div>

                        </td>
                    </tr>
                </table>
            </table>
        </td>
    </table>

</body>

</html>
