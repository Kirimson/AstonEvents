<!DOCTYPE html>
<html>
<head>
    <title>Event {{ $event->id }}</title>
</head>
<body>
<h1>{{ $event->name }} </h1>
<ul>
    <li>description: {{ $event->description }}</li>
    <li>time made: {{ $event->time }}</li>
    <li>organiser id: {{ $event->organiser_id }}</li>
    <li>contact: {{ $event->contact }}</li>
    <li>venue: {{ $event->venue }}</li>
</ul>
</body>
</html>