<!DOCTYPE html>
<html>
<head>
    <title>Events</title>
</head>
<body>
<h1>Events</h1>
<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Time</th>
        <th>Organiser</th>
        <th>Contact</th>
        <th>Venue</th>
    </tr>
    </thead>
    <tbody>
    @foreach($events as $event)
        <tr>
            <td> {{ $event->name }}</td>
            <td> {{ $event->description }}</td>
            <td> {{ $event->time }}</td>
            <td> {{ $event->organiser_id }}</td>
            <td> {{ $event->contact }}</td>
            <td> {{ $event->venue }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>