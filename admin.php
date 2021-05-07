<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta http-equiv="refresh" content="10">

    <link rel="icon" href="icons/pi.png" type="image/png" sizes="16x16">

    <link rel="preconnect" href="https://fonts.gstatic.com">

    <link href="https://fonts.googleapis.com/css2?family=Source+Code+Pro&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="./styles.css">

    <title>Pi Classroom</title>

</head>

<body>

<?php 

    // Path to reports
    $dir = "/var/www/html/pi/reports/";

    // Get list of files
    $files = scandir($dir);

    // Get num files in dir
    $numfiles = count($files);

    // Set num lines in report
    $numlines = 5;

    // remove stupid hardcoded magick numbers, just skip specific file/dirs

    // for all files in dir (except . and ..)
    for($x = 2; $x < $numfiles; $x++) {
        // Set filename to array element at index $x
        $filename = $files[$x];

        if($filename == '.trash')
        {
            // continue;
            echo "found trash";
        }

        $seen = filemtime($dir.$filename);

        // Open file in read-only mode
        $myfile = fopen($dir.$filename, "r") or die("Unable to open file!");

        // For each line in file
        for($line = 0; $line < $numlines; $line++) {
            $readline = fgets($myfile);
            
            $details[$line] = $readline;

            if($line == $numlines - 1) {
                // echo "last";
                $details[$numlines] = $seen;
                $reports[$x-2] = $details;
            }
        }

        // Close file
        fclose($myfile);    
    }

    // echo "<br>";

?>

    <img src="./icons/pi.png" id="pi-icon">
    <h2>Classroom Manager</h2>

    <table id="piTable">

        <tr class='toprow'>
            <th>&nbsp;</th>
            <th onclick='setSort(1)'>Hostname</th>
            <th onclick='setSort(2)'>IPv4 Address</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>Last Seen</th>
            <th>&nbsp;</th>
        </tr>

    <?php

    // for every report
    for($i = 0; $i < count($reports); $i++) {
        $hostname   = trim($reports[$i][0]);
        $user       = trim($reports[$i][1]);
        $adapter    = trim($reports[$i][2]);
        $ip         = trim($reports[$i][3]);
        $mac        = trim($reports[$i][4]);
        $lastseen   = trim($reports[$i][5]);

        echo "<tr>";

        echo "<td></td>";

        echo "<td title='User: ".$user."'>".$hostname."</td>";

        echo "<td title='".$mac." (".$adapter.")'>".$ip."</td>";

        echo "<td><img title='VNC View-Only' class='icon' src='./icons/view.png' id='".$ip."' onclick='run(\"view\", this.id)'/></td>";

        echo "<td><img title='VNC Full Access' class='icon' src='./icons/vnc.png' id='".$ip."' onclick='run(\"vnc\", this.id)'/></td>";

        echo "<td><img title='Secure Shell' class='icon' src='./icons/ssh.png' id='".$ip."' onclick='run(\"ssh\", this.id)'/></td>";

        echo "<td><img title='Ping Host' class='icon' src='./icons/ping.png' id='".$ip."' onclick='run(\"ping\", this.id)'/></td>";

        echo "<td><img title='SFTP Client' class='icon' src='./icons/sftp.png' id='".$ip."' onclick='run(\"sftp\", this.id)'/></td>";

        echo "<td><a href='http://".$ip."' target='_blank'><img title='Public HTML' class='icon' src='./icons/www.png'></a></td>";

        echo "<td title=".date("d-F-Y",$lastseen).">".date("H:i:s",$lastseen)."</td>";

        echo "<td><img title='Delete Host Info'  class='icon' src='./icons/del.png' id='".str_replace(":", "", $mac)."' onclick='run(\"del\", this.id)'></td>";

        echo "</tr>";
    }

    ?>

    </table>

    <script src="https://cdn.socket.io/3.1.3/socket.io.min.js" integrity="sha384-cPwlPLvBTa3sKAgddT6krw0cJat7egBga3DJepJyrLl4Q9/5WLra3rrnMcyTyOnh" crossorigin="anonymous"></script>


    <script>

        function setSort(n) {
            var sorting = n;
            console.log(sorting)
            sortTable(sorting);
        }

        let socket = new WebSocket("ws://localhost:3000");

        console.log(socket);

        socket.onopen = function(e) {
            // alert("[open] Connection established");
            // alert("Sending to server");
            // socket.send("My name is John");
        };

        socket.onmessage = function(event) {
            console.log(`${event.data}`)
            // alert(`[message] Data received from server: ${event.data}`);
        };

        socket.onclose = function(event) {
            if (event.wasClean) {
                // alert(`[close] Connection closed cleanly, code=${event.code} reason=${event.reason}`);
            } else {
                // e.g. server process killed or network down
                // event.code is usually 1006 in this case
                // alert('[close] Connection died');
            }
        };

        socket.onerror = function(error) {
            // alert(`[error] ${error.message}`);
        };

        function run(cmd, ip) {
          let socket_data = cmd.toLowerCase() + ":" + ip;
          console.log(socket_data);
        	socket.send(socket_data);
        }

        function sortTable(n) {
          var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
          table = document.getElementById("piTable");
          switching = true;
          // Set the sorting direction to ascending:
          dir = "asc";
          /* Make a loop that will continue until
          no switching has been done: */
          while (switching) {
            // Start by saying: no switching is done:
            switching = false;
            rows = table.rows;
            /* Loop through all table rows (except the
            first, which contains table headers): */
            for (i = 1; i < (rows.length - 1); i++) {
              // Start by saying there should be no switching:
              shouldSwitch = false;
              /* Get the two elements you want to compare,
              one from current row and one from the next: */
              x = rows[i].getElementsByTagName("TD")[n];
              y = rows[i + 1].getElementsByTagName("TD")[n];
              /* Check if the two rows should switch place,
              based on the direction, asc or desc: */
              if (dir == "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                  // If so, mark as a switch and break the loop:
                  shouldSwitch = true;
                  break;
                }
              } else if (dir == "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                  // If so, mark as a switch and break the loop:
                  shouldSwitch = true;
                  break;
                }
              }
            }
            if (shouldSwitch) {
              /* If a switch has been marked, make the switch
              and mark that a switch has been done: */
              rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
              switching = true;
              // Each time a switch is done, increase this count by 1:
              switchcount ++;
            } else {
              /* If no switching has been done AND the direction is "asc",
              set the direction to "desc" and run the while loop again. */
              if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
              }
            }
          }
        }

        // sortTable(2);
        // console.log(sorting)
    </script>

</body>

</html>