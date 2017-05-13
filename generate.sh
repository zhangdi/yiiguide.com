#!/usr/bin/env bash

CODE_DIR="/opt/code"
BASE_DIR="$( cd "$( dirname "$0" )" && pwd )"
YII2_DIR='/opt/code/github.com/yiisoft/yii2/'
TARGET_DIR="${BASE_DIR}/apps/yiiguide"

cd ${YII2_DIR} && \
git pull && \
docker run -it --rm --volume "${CODE_DIR}:${CODE_DIR}" php:7.0 php ${BASE_DIR}/build guide ${YII2_DIR} ${TARGET_DIR} --interactive=1 --overwrite=1
