<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <title></title>
    <!--[if !mso]><!- -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--<![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">  #outlook a { padding: 0; }  .ReadMsgBody { width: 100%; }  .ExternalClass { width: 100%; }  .ExternalClass * { line-height:100%; }  body { margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }  table, td { border-collapse:collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }  img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }  p { display: block; margin: 13px 0; }</style>
    <!--[if !mso]><!-->
    <style type="text/css">  @media only screen and (max-width:480px) {    @-ms-viewport { width:320px; }    @viewport { width:320px; }  }</style>
    <!--<![endif]--><!--[if mso]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    <![endif]--><!--[if lte mso 11]>
    <style type="text/css">  .outlook-group-fix {    width:100% !important;  }</style>
    <![endif]--><!--[if !mso]><!-->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,500,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet" type="text/css">
    <style type="text/css">        @import url(https://fonts.googleapis.com/css?family=Lato:300,400,500,700);  @import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);    </style>
    <!--<![endif]-->
    <style type="text/css">  @media only screen and (min-width:480px) {    .mj-column-per-100 { width:100%!important; }  }</style>
</head>
<body style="background: #009688;">
<div class="mj-container" style="background-color:#009688;">
    <!--[if mso | IE]>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">
        <tr>
            <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
    <![endif]-->
    <div style="margin:0px auto;max-width:600px;background:#FFFFFF;">
        <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:#FFFFFF;" align="center" border="0">
            <tbody>
            <tr>
                <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:0px 0px 0px 0px;">
                    <!--[if mso | IE]>
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="vertical-align:top;width:600px;">
                    <![endif]-->
                    <div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                        <table role="presentation" cellpadding="0" cellspacing="0" style="vertical-align:top;" width="100%" border="0">
                            <tbody>
                            <tr>
                                <td style="word-wrap:break-word;font-size:0px;padding:11px 49px 10px 51px;" align="center">
                                    <table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="center" border="0">
                                        <tbody>
                                        <tr>
                                            <td style="width:500px;"><a href="https://via.placeholder.com/350x150?text=Logo" target="_blank" style="color: #009688;"><img alt="" title="" height="auto" src="https://via.placeholder.com/350x150?text=Logo" style="border:none;border-radius:0px;display:block;font-size:13px;outline:none;text-decoration:none;width:100%;height:auto;" width="500"></a></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="word-wrap:break-word;font-size:0px;padding:0px 15px 0px 15px;" align="left">
                                    <div style="cursor:auto;color:#000000;font-family:Lato, Tahoma, sans-serif;font-size:11px;line-height:1.5;text-align:left;">
                                        <h1 style="font-family: Arial, sans-serif; line-height: 100%;">Welcome, {{ $user['name'] }}</h1>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="word-wrap:break-word;font-size:0px;padding:0px 28px 0px 24px;" align="left">
                                    <div style="cursor:auto;color:#000000;font-family:Lato, Tahoma, sans-serif;font-size:11px;line-height:1.5;text-align:left;">
                                        <h2 style="line-height: 100%;"><span style="font-size:14px;">A password reset request was received for your user account. If this wasn&apos;t you, you can safely ignore this email else if this was requested by you click on the below button to start resetting your password.</span></h2>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="word-wrap:break-word;font-size:0px;padding:20px 20px 20px 20px;" align="center">
                                    <table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:separate;width:auto;" align="center" border="0">
                                        <tbody>
                                        <tr>
                                            <td style="background-color:#009688;border:0px solid #000;border-radius:4px;color:#fff;cursor:auto;padding:13px 38px;" align="center" valign="middle" bgcolor="#007bff"><a href="{{ route('api.user.auth.forgot-password.reset-password.form', $user['token']) }}" style="text-decoration: none; background: #009688; color: #fff; font-family: Ubuntu, Helvetica, Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 19px; font-weight: normal; line-height: 120%; text-transform: none; margin: 0px;" target="_blank">Click Here To Reset Password</a></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="word-wrap:break-word;font-size:0px;padding:0px 24px 0px 25px;" align="left">
                                    <div style="cursor:auto;color:#000000;font-family:Lato, Tahoma, sans-serif;font-size:11px;line-height:1.5;text-align:left;">
                                        <h2 style="line-height: 100%;"><span style="font-size:14px;">If the above button does not work, copy and paste the below link into your browser's address bar.</span></h2>
                                        <p><span style="font-size:14px;"><a href="{{ route('api.user.auth.forgot-password.reset-password.form', $user['token']) }}" style="color: #009688;">{{ route('api.user.auth.forgot-password.reset-password.form', $user['token']) }}</a></span></p>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--[if mso | IE]>
                    </td>
                    </tr>
                    </table>
                    <![endif]-->
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <!--[if mso | IE]>
    </td>
    </tr>
    </table>
    <![endif]-->
</div>
</body>
</html>
