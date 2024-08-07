<html>

<body>
    <table width="650"
        style="background:#fff; margin:0px auto; font-family: Open Sans, sans-serif; font-size:13px; line-height:19px;border-collapse: collapse;"
        border="0" vspace="0">
        <tr>
            <td height="125" align="center" style="padding:10px 25px 0;">{!! $logo !!}
                <hr style="border-bottom: 2px solid #0ebae8;">
            </td>
        </tr>
        <tr>
            <td style="padding:10px 25px; font-size:13px; line-height:21px;">
                Hi {{ $userEmail }},
                <br>
                You have successfully subscribed to our newsletter. You will receive updates from {{ $siteTitle }}
                team.
                <br>
                <a href="{{ $confirmationLink }}">Confirm your Newsletter Subscription</a> or click on <a
                    href="{{ $unsubscribeLink }}">Unsubscribe</a> to unsubscribe.
            </td>
        </tr>
    </table>
</body>

</html>
