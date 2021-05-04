# Creative Coding Classroom

## Raspberry Pi Classroom Management System for Creative Coding Classes

"Web App" interface for managing a classroom of Raspberry Pi computers. Simplifies and greatly speeds up the process of running the most common remote management tasks in the classroom - or at least the tasks I found myself running 10 to 20 times per class! It basically acts a launcher for applications and makes it much easier to interact directly with coding students in their IDE. Currently supports:
* Ping
* VNC Remote Desktop (Full Access and View-Only)
* SSH Remote Shell
* SFTP File Transfer 
* Browse public HTML folder

## Screenshots
### VNC Remote Desktop
![VNC Full Icon](./img/02-vnc-full-icon.png)
![VNC Full Preview](./img/02-vnc-preview.png)
![VNC View Icon](./img/01-vnc-view-icon.png)


### SSH Secure Shell
![SSH Icon](./img/03-ssh-icon.png)
![SSH Preview](./img/03-ssh-preview.png)


### Ping
![Ping Icon](./img/04-ping-icon.png)
![Ping Preview](./img/04-ping-preview.png)

### SFTP Client
![SFTP Icon](./img/05-sftp-icon.png)
![SFTP Preview](./img/05-sftp-preview.png)


### Browse public HTML folder of host
![Web Icon](./img/06-web-icon.png)


## Architecture
### Client
* Python (previously Bash) script set to run every minute as a cronjob
	* Creates JSON file with info about Raspberry Pi client (e.g. hostname, user, MAC address)
	* Passes JSON data to PHP script on server


### Server
* Web server PHP script listens for JSON data from Raspberry Pi clients
	* Received data saved to local file named according to the MAC address of sender

* admin.php:
	* Admin Page with dynamic table of up-to-date details about Raspberry Pi clients
	* Contains details (e.g. hostname, user, IP address, last time seen) and clickable icons for common tasks (i.e. ping, ssh, vnc, sftp)

* Node.js server:
	* Uses WebSocket to listen for socket connections from "admin page" in browser
	* Executes commands on server (e.g. ping, ssh, etc.) in response to data received through socket
	* Some tasks (e.g. ping, ssh) launch a new terminal window and run in that window
	* Some tasks (e.g. VNC, Filezilla SFTP) launch their own application window
	* SSH configured for RSA key login
	* RealVNC config file dynamically generated to allow login using stored password


## Additional Material
### Creative Coding Video Lesson for Absolute Beginners
<a href="http://www.youtube.com/watch?v=fO8TsDkmXYQ" target="_blank">
	<img src="./img/coding-video-lesson-preview.png" title="Click to watch example Creative Coding video lesson" width="480">
</a>


### Poster Presentation on Teaching Coding using Remote Desktop Technology
<a href="https://raw.githubusercontent.com/coding418/creative-coding-classroom/main/img/ltech-poster.png" target="_blank">
	<img src="./img/ltech-poster.png" title="Poster Presentation on Using Remote Desktop to Teach Coding" width="720">
</a>

