<?php
    require_once 'includes/header.php';
?>

<?php
    // LOG OUT
    if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['sessionId']);
        unset($_SESSION['sessionUser']);
        unset($_SESSION['sessionRole']);
        unset($_SESSION['univName']);
        header("location: login.php");
    }

    // CHECK: logged in
    if (!isset($_SESSION['sessionId'])) {
        header("location: login.php?error=notloggedin");
    } else {
    // EVENTS LIST
        $query = "SELECT * from event";
        $result = mysqli_query($db, $query);
        $rows = mysqli_num_rows($result);
        if ($rows > 0) {
            // TABLE: column names
            $display_block = "
            <table cellpadding=3 cellspacing=1 border=1>
            <th>Event</th>
            <th>Location</th>
            <th>Category</th>
            <th>Date</th>
            <th>Time</th>
            <th>Description</th>
            </tr>";

            while($event_info = mysqli_fetch_array($result)) {
                // FETCH: individual event
                $event_id = $event_info['eventId'];
                $event_name= $event_info['eventName'];
                $event_location = $event_info['eventLocation'];
                $event_category = $event_info['eventCategory'];
                $event_date = $event_info['eventDate'];
                $event_time = $event_info['eventTime'];
                $event_privacy = $event_info['eventPrivacy'];
                $event_description = $event_info['eventDescription'];

                // TABLE: fill the table with events, click sends user to an event page
                $display_block .= "
                <tr>
                <td><a href=\"event_page.php?eventId=$event_id\">
                <strong>$event_name</strong></a><br>
                <td><strong>$event_location</strong></a><br>
                <td><strong>$event_category</strong></a><br>
                <td><strong>$event_date</strong></a><br>
                <td><strong>$event_time</strong></a><br>
                <td><strong>$event_description</strong></a><br>
                </tr>";         
            }

            // TABLE: end
            $display_block.= "</table>";
        } else {
            $display_block = "<p><em>No events to display.</em><p>";
        }
    }

?>

<html>
    <head>
        <title> University Events </title>
    </head>
    <body>
        <h1> Events </h1>
        <?php print $display_block; ?>
    </body>
</html>

<?php
    require_once 'includes/footer.php';
?>

