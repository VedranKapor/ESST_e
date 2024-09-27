const createWindowsInstaller = require('electron-winstaller').createWindowsInstaller
const path = require('path')

getInstallerConfig()
  .then(createWindowsInstaller)
  .catch((error) => {
    console.error(error.message || error)
    process.exit(1)
  })

function getInstallerConfig () {
  console.log('creating windows installer')
  const rootPath = path.join('./')
  const outPath = path.join(rootPath, 'release-builds')

  return Promise.resolve({
    appDirectory: path.join(outPath, 'ESST-win32-x64/'),
    loadingGif: path.join(rootPath, 'project', 'css', 'esst.gif'),
    authors: 'IAEA',
    noMsi: true,
    outputDirectory: path.join(outPath, 'windows-installer'),
    exe: 'esst.exe',
    setupExe: 'ESST.2.7.1.exe',
    //iconUrl:  "https://raw.githubusercontent.com/andrii-iaea/NEST/master/Theme/favicon.ico?token=AhmTzVatpFcPJVQ-B5qff8S55OuCT8iIks5cs4pxwA%3D%3D",
    //iconUrl: "https://raw.githubusercontent.com/andrii-iaea/ESST/master/css/favicon-bar-chart-o.ico?token=AhmTzaUd5HsPO3swqy2-GSwweBs-NzEQks5cfp0lwA%3D%3D",
    setupIcon: path.join(rootPath, 'project', 'css', 'favicon-bar-chart-o.ico' ),
    //iconUrl:  path.join(rootPath, 'project', 'css', 'esst.ico' ),
    iconUrl: "https://github.com/VedranKapor/ESST_e/blob/master/project/css/esst.ico",
    // remoteReleases: 'https://github.com/VedranKapor/ESST_e.git',
    // remoteToken: 'ca44137151d356d42d1626ec992f4c2a936b7aac'
  })
}