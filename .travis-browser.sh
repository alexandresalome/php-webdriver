#!/bin/bash
set -ex

BROWSER="$1"

if [ "$BROWSER" != "firefox" -a "$BROWSER" != "chrome" ]; then
    echo "Usage: ./install-browser [firefox|chrome]"
    echo ""
    echo "This command installs the browser on a Debian platform and the proper driver for Selenium"

    exit 1
fi

if [ "$WD_BROWSER" = "firefox" ]; then
    sudo apt-get install -y --force-yes firefox
fi

if [ "$WD_BROWSER" = "chrome" ]; then
    # ChromeDriver
    DRIVER_FILE="chromedriver_linux64.zip"
    DRIVER_URL="http://chromedriver.storage.googleapis.com/2.9/$DRIVER_FILE"
    wget "$DRIVER_URL"
    unzip "$DRIVER_FILE"
    sudo mv chromedriver /usr/bin

    # Chrome, from https://github.com/travis-ci/travis-ci/issues/938#issuecomment-31114013
    sudo apt-get remove chromium-browser
    echo ttf-mscorefonts-installer msttcorefonts/accepted-mscorefonts-eula select true | sudo debconf-set-selections
    sudo apt-get install ttf-mscorefonts-installer
    sudo apt-get install x-ttcidfont-conf
    sudo mkfontdir
    sudo apt-get install defoma libgl1-mesa-dri xfonts-100dpi xfonts-75dpi xfonts-scalable xfonts-cyrillic
    wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb
    sudo mkdir -p /usr/share/desktop-directories
    sudo dpkg -i google-chrome-stable_current_amd64.deb
    sudo apt-get install -f
    sudo dpkg -i google-chrome-stable_current_amd64.deb
    export CHROME_SANDBOX=/opt/google/chrome/chrome-sandbox
    sudo rm -f $CHROME_SANDBOX
    sudo wget https://googledrive.com/host/0B5VlNZ_Rvdw6NTJoZDBSVy1ZdkE -O $CHROME_SANDBOX
    sudo chown root:root $CHROME_SANDBOX; sudo chmod 4755 $CHROME_SANDBOX
    sudo md5sum $CHROME_SANDBOX
    sudo chmod 1777 /dev/shm
fi
