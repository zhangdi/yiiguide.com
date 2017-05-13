#!/usr/bin/env bash

BASE_DIR="$( cd "$( dirname "$0" )" && pwd )"
YII2_DIR='/opt/code/github.com/yiisoft/yii2/'
TARGET_DIR="${BASE_DIR}/apps/yiiguide"

php () {
    tty=
    tty -s && tty=--tty
    docker run \
        $tty \
        --rm \
        php:7.0 "$@"
}

cd ${YII2_DIR} && \
git pull && \
php ${BASE_DIR}/build guide ${YII2_DIR} ${TARGET_DIR} --interactive=1 --overwrite=1
