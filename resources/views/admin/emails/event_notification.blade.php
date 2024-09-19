<!DOCTYPE html>
<html>

<head>
    <title>Event Notification</title>
</head>

<body>
    <h1 style="color: #666666; font-size: 40px; font-family: lora, georgia, times new roman, serif;"><em>Reminder!</em></h1>
    <img src="{{ $emailImgpath }}" alt="Event Image" width="300">
    <p style="color: #999999;">“It is the duty of every citizen according to his best capacities to give validity to his convictions in political affairs.” -Albert Einstein</p>
    <p style="color: #999999;"><em>Best regards, {{ $first_name }} {{ $last_name }}</em></p>
    <h2 style="color: #2f464b;">Welcome!</h2>
    <h3 style="color: #2f464b;">We have an event on {{ $event_date }}. Come and visit...</h3>
    <p>Email sent by Egrassrooter</p>
    <p>Copyright © 2019 Egrassrooter.com. All rights reserved</p>
</body>

</html>