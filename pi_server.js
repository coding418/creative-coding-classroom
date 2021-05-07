const WebSocket = require('ws');
const wss = new WebSocket.Server({ port: 3000 });

const { exec } = require("child_process");
const fs = require('fs');

let user = 'pi';
let pass = 'school';
let vnc_pass = '00e27f00f65b7585';
let report_path = '/var/www/html/pi/reports/';
 
wss.on('connection', ws => {
    ws.on('message', (data) => {

        let cmd = data.split(":")[0];
        let ip = data.split(":")[1];

        if(cmd == 'vnc' || cmd == 'view' || cmd == 'ssh' || cmd == 'ping' || cmd == 'sftp' || cmd == 'del'){
            console.log(`Executing: ${cmd} ${ip}`);
            run(cmd, ip);
        }
        else{
            console.log(`Received: ${data}`);
        }
    });

    ws.send('You are now connected to Pi Classroom Server');
});

function run(cmd, ip){
    let cmd_string;

    if(cmd == 'vnc' || cmd == 'view'){
        configVNC(cmd, ip);
        cmd_string = `vncviewer -config config.vnc -VerifyID=0`;
    }
    else if(cmd == 'ssh'){
        cmd_string = `gnome-terminal -e "ssh ${user}@${ip}"`;
    }
    else if(cmd == 'ping'){
        cmd_string = `gnome-terminal -e "ping -c 5 -i 1 ${ip}"`;
    }
    else if(cmd == 'sftp'){
        cmd_string = `filezilla sftp://${user}:${pass}@${ip}:22/home/${user}/Desktop`;
    }
    else if(cmd == 'del'){
        cmd_string = `rm ${report_path}${ip}`;
    }
    
    exec(cmd_string, (error, stdout, stderr) => {
        if (error) {
            console.log(`error: ${error.message}`);
            return;
        }
        if (stderr) {
            console.log(`stderr: ${stderr}`);
            return;
        }
        console.log(`Executed: ${cmd_string}\n`);
    });
}

function configVNC(cmd, ip){
    let data = `Host=${ip}\nFriendlyName=${ip}\nUserName=${user}\nPassword=${vnc_pass}\nScaling=AspectFit\nConnMethod=tcp\n`
    
    if(cmd == 'view'){
        let view_only = "SendKeyEvents=0\nSendPointerEvents=0\nServerCutText=0\nShareFiles=0\nClientCutText=0\nEnableChat=0";
        data += view_only;
    }

    fs.writeFile("config.vnc", data, (err) => {
        if (err)
            console.log(err);
        else {
            // console.log("VNC config file written\n");
            // console.log(fs.readFileSync("test.vnc", "utf8"));
        }
    });
}