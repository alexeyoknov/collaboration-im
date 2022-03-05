#!/bin/bash


SCRIPT_PATH=$(readlink -f $(readlink -f "$(dirname $0)/$(basename $0)"))
SERVER_PATH=$(dirname $(dirname $(dirname "${SCRIPT_PATH}")))

ENV_FILE="${SERVER_PATH}"

if [ -f "${SERVER_PATH}/.env.local" ]; then
    ENV_FILE="${SERVER_PATH}/.env.local"
else
    ENV_FILE="${SERVER_PATH}/.env"
fi

INFO=$(cat "${ENV_FILE}" | grep -E "^DATABASE_URL")

if [ -z "${INFO}" ]; then
    echo "DB not configured!"
    exit 1
fi

LOGIN=$(echo "${INFO}" | awk -F: '{print $2}' | sed 's/\///g')
PASS=$(echo "${INFO}" | awk -F: '{print $3}' | sed 's/@.*$//')
DB_NAME=$(echo "${INFO}" | awk -F: '{print $4}' | sed -e 's/[0-9].*\///g' -e 's/?.*$//')

if [ -z "${LOGIN}" ] || [ -z "${DB_NAME}" ]; then
    echo "Login or db_name empty"
    exit 2
fi

echo
echo "Login: ${LOGIN}"
echo "Password: ${PASS}"
echo "DB_NAME: ${DB_NAME}"
echo 

mysql -u${LOGIN} -p${PASS} -h "127.0.0.1" -P 3306 ${DB_NAME} < sql-data.sql