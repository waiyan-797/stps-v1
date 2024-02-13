<!DOCTYPE html>
<head>
  <title>Pusher Test</title>
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('ff6d2dc3e07b1864a77d', {
      cluster: 'ap1'
    });

   


    var channel = pusher.subscribe('trip-request-channel');
    channel.bind('trip-request-event', function(data) {
    //   alert(JSON.stringify(data));\

            let trip = JSON.stringify(data)
    console.log(trip);
    });

    // var channel = pusher.subscribe('driver-request-channel');
    // channel.bind('driver-request-event', function(data) {
    // //   alert(JSON.stringify(data));\

    //         let driver = JSON.stringify(data)
    // console.log(driver);
    // });
    // var channel = pusher.subscribe('driver-location-request-channel');
    // channel.bind('driver-location-request-event', function(data) {
    // //   alert(JSON.stringify(data));\

    //         let driver = JSON.stringify(data)
    // console.log(driver);
    // });

    var channel = pusher.subscribe('booking-channel');
    channel.bind('booking-event', function(data) {
    //   alert(JSON.stringify(data));\

            let booking = JSON.stringify(data)
    console.log(booking);
    });
  </script>
</head>
<body>
  <h1>Pusher Test</h1>
  <p>
    Try publishing an event to channel <code>my-channel</code>
    with event name <code>my-event</code>.
  </p>
</body>