#!/bin/bash

sleep 60s;

while true; do
    /code/bin/console messenger:failed:retry --force
    /code/bin/console messenger:consume collect_user_info --time-limit=3600 -vv

    sleep 60s
done;
