const electron = require('electron');
var path = require("path");
var php = require("gulp-connect-php");
const log = require('electron-log');

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
let iniPath;

if(platform == "win32") {
  phpPath = path.resolve(__dirname) + "/php/win32/php-7.4.7/php";
  iniPath = path.resolve(__dirname) + "/php/win32/php-7.4.7/php.ini";
}
if(platform == "linux") {
  phpPath = path.resolve(__dirname) + "/php"+"/bin"+"/ubuntu"+"/php-7.4.3"+"/php7.4";
  iniPath = path.resolve(__dirname) + "/php"+"/bin"+"/ubuntu"+"/php-7.4.3"+"/php.ini";
}
if(platform == "darwin") {
  phpPath = path.resolve(__dirname) + "/php"+"/darwin"+"/php-/php";
  iniPath = path.resolve(__dirname) + "/php"+"/darwin"+"/php-/php.ini";
}

log.info('phpPath ', phpPath);

//console.log(path.resolve(__dirname));
let phpServer = new php();
phpServer.server({
    port: 8088,
    base: path.resolve(__dirname) + '/project',
    // this is now pointing to a possible local installation of php, that is best for portability
    // feel free to change with a system-wide installed php, that is dirty & working, but less portable
    bin: phpPath,
    ini: iniPath,
    debug: false
    //ini: 'C:/wamp.3.2.0/bin/apache/apache2.4.41/bin/php.ini'
});

log.info('base path ', path.resolve(__dirname) + '/project',);
log.info('php server passed!');

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

    setTimeout(load, 1000);
    function load(){
      mainWindow.loadURL("http://127.0.0.1:8088/index.html");

      // Uncomment to open the DevTools.
      //mainWindow.webContents.openDevTools();
    } 

    mainWindow.webContents.on('dom-ready', function() {
      log.info('dom-ready ')
    });

    mainWindow.webContents.on('did-finish-load', function(event, code, desc, url, isMainFrame) {
      log.info('load success http://127.0.0.1:8088/index.html')
    });
    
    mainWindow.webContents.on('did-fail-load', function(event, code, desc, url, isMainFrame) {
      log.error('did-fail-load event', event);
      log.error('did-fail-load code', code, );
      log.error('did-fail-load desc', desc);
      log.error('did-fail-load url', url);
      log.error('did-fail-load isMainFrame', isMainFrame)
    });

    // Emitted when the window is closed.
    mainWindow.on('closed', function () {
      // Dereference the window object, usually you would store windows
      // in an array if your app supports multi windows, this is the time
      // when you should delete the corresponding element.
      mainWindow = null;
    });
});

const exec = require('child_process').exec;

function execute(command, callback) {
    exec(command, (error, stdout, stderr) => { 
        callback(stdout); 
    });
};

execute('node -v', (output) => {
  log.info('node version: ', output);
});

execute('cd ' +path.resolve(__dirname) + "/php/win32/php-7.4.7" + ' && php -v', (output) => {
  log.info('php version: ', output);
});

log.catchErrors({
  showDialog: false,
  onError(error, versions, submitIssue) {
    electron.dialog.showMessageBox({
      title: 'An error occurred',
      message: error.message,
      detail: error.stack,
      type: 'error',
      buttons: ['Ignore', 'Report', 'Exit'],
    })
      .then((result) => {
        if (result.response === 1) {
          submitIssue('https://github.com/andrii-iaea/ESST/issues/new', {
            title: `Error report for ${versions.app}`,
            body: 'Error:\n```' + error.stack + '\n```\n' + `OS: ${versions.os}`
          });
          return;
        }
      
        if (result.response === 2) {
          electron.app.quit();
        }
      });
  }
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
