# Creative Coding Classroom

## Raspberry Pi Classroom Management System for Creative Coding Classes

"Web App" interface for managing a classroom of Raspberry Pi computers. Simplifies and greatly speeds up the process of running the most common remote management tasks in the classroom (or at least the tasks I found myself running 10 to 20 times per class!):
* Ping
* VNC Remote Desktop
* SSH Remote Shell
* SFTP File Transfer 



## Architecture
### Client
* Python (previously Bash) script set to run every minute as a cronjob
	* Creates JSON file with info about Raspberry Pi client (e.g. hostname, user, MAC address)
	* Runs curl to pass JSON data to PHP script on server


### Server
* Web server PHP script listens for JSON data from Raspberry Pi clients
	* Received data saved to JSON file named according to the MAC address of sender

* Node.js server:
	* Uses Express.js to host PHP "Admin Page" with dynamic table of up-to-date details about Raspberry Pi clients
		* "Admin Page" contains details about clients (e.g. hostname, user, IP address, last time seen) and clickable icons for most common tasks (i.e. ping, ssh, vnc, sftp)
	* Uses Socket.io to listens for socket connections from "admin page" in browser
		* Executes commands on server (e.g. ping, ssh, etc.) in response to data received through socket



## Additional Material
### Creative Coding Video Lesson for Absolute Beginners
<a href="http://www.youtube.com/watch?v=fO8TsDkmXYQ" target="_blank">
	<img src="./img/coding-video-lesson-preview.png" title="Click to watch example Creative Coding video lesson" width="480">
</a>


### Poster Presentation on Teaching Coding using Remote Desktop Technology
<a href="https://raw.githubusercontent.com/coding418/creative-coding-classroom/main/img/ltech-poster.png" target="_blank">
	<img src="./img/ltech-poster.png" title="Poster Presentation on Using Remote Desktop to Teach Coding" width="720">
</a>

