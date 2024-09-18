<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Manager</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F5F7F9;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 20px;
            max-width: 400px;
            margin: 0 auto;
        }

        .header {
            font-size: 18px;
            font-weight: bold;
            color: #2C3A4B;
            text-align: center;
            margin-bottom: 20px;
        }

        .dropdown {
            background-color: #EDEFF2;
            border-radius: 8px;
            padding: 12px;
            color: #6B778D;
            cursor: pointer;
            margin-bottom: 20px;
            position: relative;
        }

        .dropdown select {
            width: 100%;
            border: none;
            background: none;
            appearance: none;
            color: #6B778D;
            font-size: 16px;
        }

        .dropdown::after {
            content: '▼';
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            font-size: 12px;
            color: #6B778D;
        }

        .event-details {
            background-color: white;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 20px;
        }

        .event-details img {
            width: 100%;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .event-title {
            font-size: 18px;
            font-weight: bold;
            color: #2C3A4B;
            margin: 0;
        }

        .event-subtitle {
            font-size: 14px;
            color: #6B778D;
            margin: 5px 0;
        }

        .event-organizer {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }

        .organizer-image {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }

        .organizer-name {
            font-size: 16px;
            font-weight: bold;
            color: #2C3A4B;
        }

        .organizer-role {
            font-size: 14px;
            color: #6B778D;
        }

        .button {
            background-color: #2C3A4B;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 8px;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .button:hover {
            background-color: #3B4A5A;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">Event Manager</div>

        <div class="dropdown">
            <select onchange="handleChangeEvent(this.value)">
                <option value="">Choose an event</option>
                @foreach ($events as $item)
                <option value="{{$item['event_id']}}">{{$item['event_name']}}</option>
                @endforeach
            </select>
        </div>

        <div class="event-details" id="eventDetails" style="display: none">
            <img id="eventImage" src="https://via.placeholder.com/150" alt="Event Image">
            <div class="event-title" id="eventTitle">Event Title</div>
            <div class="event-subtitle" id="eventSubtitle">Event Subtitle</div>
        </div>

        <div class="button" id="viewDetails" style="text-decoration: none;">View Details</div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        function handleChangeEvent(event_id) {
            $.ajax({
                url: `{{url('/event/${event_id}/ajax')}}`,
                type: "GET",
                dataType: "json",
                success: function(event) {
                    document.getElementById('eventTitle').textContent = event.event_name;
                    document.getElementById('eventSubtitle').textContent = event.event_date;
                    document.getElementById('eventImage').src = `https://placehold.co/200x200?text=${event.event_name}&font=oswald`;

                    // Show the event details
                    document.getElementById('eventDetails').style.display = 'block';
                    document.getElementById('viewDetails').style.display = 'block';
                    document.getElementById('viewDetails').innerHTML = `<a style="text-decoration: none; color: white;" href="{{url('/event/${event_id}')}}">View Details</a>`;
                },
                error: function(xhr, status, error) {
                    document.getElementById('eventDetails').style.display = 'none';
                    document.getElementById('viewDetails').style.display = 'none';
                }
            });
        }
    </script>
</body>

</html>