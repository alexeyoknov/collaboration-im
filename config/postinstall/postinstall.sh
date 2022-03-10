#!/bin/bash


function npm_notInstalled() {
    TYPE="$1"
    case "$TYPE" in
        gentoo)
            COMMAND="emerge -aqv1 net-libs/nodejs"
            ;;
        debian)
            COMMAND="apt-get install npm nodejs"
            ;;
        rhel)
            COMMAND="yum install npm nodejs"
            ;;
        *)
            TYPE="Unknown"
            COMMAND="Please install npm and nodejs"
            ;;
    esac
    printf "\n\tnpm not installed!!!"
    printf "\n\n\tFor ${TYPE}-based system:"
    printf "\n\n\t\t${COMMAND}"
    echo
    echo
    exit 1
}

function composer_notInstalled() {
    TYPE="$1"
    case "$TYPE" in
        gentoo)
            COMMAND="emerge -aqv1 dev-php/composer"
            ;;
        debian)
            COMMAND="apt-get install composer"
            ;;
        rhel)
            COMMAND="yum install composer"
            ;;
        *)
            TYPE="Unknown"
            COMMAND="Please install composer with system installer"
            ;;
    esac
    echo 
    printf "\tnpm not installed!!!"
    printf "\n\nFor ${TYPE}-based system:"
    printf "\n\n\t${COMMAND}"
    printf "\n\nor install it using this commands:\n"
    printf "\n\tphp -r \"copy('https://getcomposer.org/installer', 'composer-setup.php');\""
    printf "\n\tphp composer-setup.php --install-dir=/usr/local/bin --filename=composer"
    echo
    echo
    exit 2
}

NPM=$(which npm)
COMPOSER=$(which composer)

SCRIPT_PATH=$(readlink -f $(readlink -f "$(dirname $0)/$(basename $0)"))
SERVER_PATH=$(dirname $(dirname $(dirname "${SCRIPT_PATH}")))

DIST=$(cat /etc/os-release | grep '^ID_LIKE' | head -n1  | sed -e 's/"//g' -e 's/\ .*//' | awk -F=  '{ print $2 }')

cd "${SERVER_PATH}" || exit

if [ -z "${COMPOSER}" ]; then
    COMPOSER=$(which composer.phar)
    if [ -z "${COMPOSER}" ]; then
       echo "composer not installed!!!"
       composer_notInstalled "${DIST}"
    fi
fi

if [ -z "${NPM}" ]; then
    npm_notInstalled "${DIST}"
fi

${COMPOSER} install
./bin/console doctrine:schema:update --force
./bin/console doctrine:migrations:migrate
npm install
npm install file-loader@^6.0.0 --save-dev
npm run build