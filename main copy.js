const electron = require('electron');
var path = require("path");
var php = require("gulp-connect-php");

// import {enableLiveReload} from 'electron-compile';
// enableLiveReload();
// const ec = require('electron-compile');
// ec.enableLiveReload();

const app = electron.app;  // Module to control application life.
const BrowserWindow = electron.BrowserWindow;  // Module to create native browser window.

if (handleSquirrelEvent()) {
  // squirrel event handled and app will exit in 1000ms, so don't do anything else
  return;
}

//php binaries on platform dependatan

var platform = process.platform;
let phpPath;
let binPath;

if(platform == "win32") {
  phpPath = path.resolve(__dirname) + "/php/win32/php-7.4.7/php";
}
if(platform == "linux") {
  phpPath = path.resolve(__dirname) + "/php"+"/bin"+"/ubuntu"+"/php";
}
if(platform == "darwin") {
  phpPath = path.resolve(__dirname) + "/php"+"/darwin"+"/php-/php";
}

//console.log(path.resolve(__dirname));
let phpServer = new php();
phpServer.server({
    port: 8088,
    base: path.resolve(__dirname) + '/project',
    // this is now pointing to a possible local installation of php, that is best for portability
    // feel free to change with a system-wide installed php, that is dirty & working, but less portable
    bin: phpPath,
    debug: true
    //ini: 'C:/wamp.3.2.0/bin/apache/apache2.4.41/bin/php.ini'
});

// Keep a global reference of the window object, if you don't, the window will
// be closed automatically when the JavaScript object is garbage collected.
let mainWindow;

// Quit when all windows are closed.
app.on('window-all-closed', function () {
  // On OS X it is common for applications and their menu bar
  // to stay active until the user quits explicitly with Cmd + Q
  // if (process.platform != 'darwin') {
  //   app.quit();
  // }
  phpServer.closeServer();
  app.quit();
});

// This method will be called when Electron has finished
// initialization and is ready to create browser windows.
app.on('ready', function() {
    // Create the browser window.
    mainWindow = new BrowserWindow({
        //frame: false,
        width: '100%', 
        height: '100%',
        //titleBarStyle: 'customButtonsOnHover', 
        icon: path.resolve(__dirname) + '/project/css/favicon.ico',
        webPreferences: {
          nodeIntegration: false,
          // preload: './preload.js'
        },
        show: false
    });

    mainWindow.once('ready-to-show', () => {
        mainWindow.show()
    })

    //process.env['ELECTRON_DISABLE_SECURITY_WARNINGS'] = 'true';
    process.env['ELECTRON_ENABLE_LOGGING'] = 1;
    //ELECTRON_ENABLE_LOGGING=1;
    
    mainWindow.setMenu(null);
    // and load the app's front controller. Feel free to change with app_dev.php
    //mainWindow.setFullScreen(true); 
    mainWindow.loadURL("http://127.0.0.1:8088/index.html");

    // Uncomment to open the DevTools.
    mainWindow.webContents.openDevTools();

    // Emitted when the window is closed.
    mainWindow.on('closed', function () {
      // Dereference the window object, usually you would store windows
      // in an array if your app supports multi windows, this is the time
      // when you should delete the corresponding element.
      mainWindow = null;
    });
});

function handleSquirrelEvent() {
  if (process.argv.length === 1) {
    return false;
  }

  const ChildProcess = require('child_process');
  const path = require('path');

  const appFolder = path.resolve(process.execPath, '..');
  const rootAtomFolder = path.resolve(appFolder, '..');
  const updateDotExe = path.resolve(path.join(rootAtomFolder, 'Update.exe'));
  const exeName = path.basename(process.execPath);

  const spawn = function(command, args) {
    let spawnedProcess, error;

    try {
      spawnedProcess = ChildProcess.spawn(command, args, {detached: true});
    } catch (error) {}

    return spawnedProcess;
  };

  const spawnUpdate = function(args) {
    return spawn(updateDotExe, args);
  };

  const squirrelEvent = process.argv[1];
  switch (squirrelEvent) {
    case '--squirrel-install':
    case '--squirrel-updated':
      // Optionally do things such as:
      // - Add your .exe to the PATH
      // - Write to the registry for things like file associations and
      //   explorer context menus

      // Install desktop and start menu shortcuts
      spawnUpdate(['--createShortcut', exeName]);

      setTimeout(app.quit, 1000);
      return true;

    case '--squirrel-uninstall':
      // Undo anything you did in the --squirrel-install and
      // --squirrel-updated handlers

      // Remove desktop and start menu shortcuts
      spawnUpdate(['--removeShortcut', exeName]);

      setTimeout(app.quit, 1000);
      return true;

    case '--squirrel-obsolete':
      // This is called on the outgoing version of your app before
      // we update to the new version - it's the opposite of
      // --squirrel-updated

      app.quit();
      return true;
  }
};
