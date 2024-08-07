<!DOCTYPE html>
<html>

<head>
    <title>{{ $site_title }}</title>
</head>

<body>
    <table width="100%" style="line-height:20px; font-size:14px">
        <thead>
            <th style="height:80px; font-size:24px; background:#"></th>
        </thead>
    </table>
    <table width="100%" style="line-height:20px; font-size:14px">
        <tr>
            <td>
                <h4 style="font-weight:bold;">Hi {{ $contact_name }},</h4>
            </td>
        </tr>
        <tr>
            <td>
                <h3 style="font-weight:bold;">Thank you for getting in touch!</h3>
            </td>
        </tr>
        <tr>
            <td>We appreciate you contacting us about {{ nl2br(e($subject)) }}. One of our Team Members will get back to
                you shortly.</td>
        </tr>
        <tr>
            <td>Have a great day!</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
    </table>
    <table width="650"
        style="background:#fff; margin:0px auto; font-family: Open Sans, sans-serif; font-size:13px; line-height:19px; border-collapse: collapse;"
        border="0" vspace="0">
        <tr>
            <td height="125" align="center" style="padding:10px 25px 0;">
                <a href="{{ url('/') }}"> <img src="{{ $logo }}"></a>
                <hr style="border-bottom: 2px solid #0ebae8;">
            </td>
        </tr>
        <tr>
            <td style="padding:10px 25px; font-size:13px; line-height:21px;">
                Kind regards,<br><strong>The {{ $site_title }}</strong>
            </td>
        </tr>
    </table>
</body>

</html>
