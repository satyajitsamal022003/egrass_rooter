<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Confirmation</title>
</head>

<body>
    <table width="650" style="background:#fff; margin:0 auto; font-family: Open Sans, sans-serif; font-size:13px; line-height:19px; border-collapse: collapse;" border="0" vspace="0">
        <tr>
            <td align="center" style="border-bottom:4px solid #1abc9c; padding:15px 0;">
                <table width="96%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <td>{!! $logo !!}</td>
                            <td align="right"><a href="{{ url('/') }}" style="color:#163963;">View in Browser</a></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td><img src="{{ asset('/images/email-conf.png') }}" width="100%" alt="" /></td>
        </tr>
        <tr>
            <td style="padding:10px 25px; font-size:13px; line-height:21px; text-align:center">
                <h1>Email Confirmation</h1>
                <p style="font-size:16px; line-height:24px;">You are almost ready to start using Egrassrooter to manage your political campaign.
                    <br><br>
                    Simply click on the button below to verify your email address to continue your registration.
                </p><br>
                <a href="{{ $activationLink }}" style="display:inline-block; background-color:#01a29b; color:#fff; font-size:16px; text-decoration:none; padding:13px 20px; border-radius:4px;">Verify Email Address</a>
            </td>
        </tr>
        <tr>
            <td style="padding:10px 25px; border-top:2px solid #1abc9c; font-size:14px; line-height:22px; color:#DDDDDD" bgcolor="#15222e" align="center">
                Email sent by Egrassrooter<br>
                Copyright © {{ date('Y') }} Egrassrooter.com. All rights reserved </td>
        </tr>
    </table>
</body>

</html>