<!DOCTYPE>
<html>
    <head></head>
    <body>
        <div style="background:#fafafa;margin:0 auto;max-width:380px;color: black;">
            <table style="width:100%;color:#222222;font-family:OpenSans;font-size:14px;line-height:17px;padding:5px 15px;" align="center" border="0" cellpadding="0" cellspacing="0">
                <tbody>
                    <tr>
                        <td>
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div>
                                                <h2 style="margin-top: 0;border-bottom:1px solid;">{{ config('app.name') }}</h2>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>Hi {{ $details->name }}</p>
                                            <p>Your One Time Password (<span class="il">OTP</span>) for Login is:</p>
                                            <p style="text-align: center;"><span style="font-size: 32px;font-weight: bold;">{{ $details->otp }}</span></p>
                                            <p>The password will expire in ten minutes if not used.</p>  
                                            <p>If you have not made this request, you can ignore this email.</p>
                                            <p>Thank You,</p>
                                            <p>{{ config('app.name') }} Tech. Team</p>
                                            <p></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="color:#666666;font-size:11px;padding:15px 5px 5px 5px">
                            Never Share your <span class="il">OTP</span> or Username/Password with anyone. Sharing 
                            these details can lead to unauthorised access to your account.
                        </td>
                    </tr>
                    <tr>
                        <td style="color:#666666;font-size:11px;padding:0px 5px 15px 5px">
                            This is an automatically generated email, please do not reply.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>