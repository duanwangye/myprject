#!/bin/bash
  
step=5 #间隔的秒数，不能大于60

for (( i = 0; i < 60; i=(i+step) )); do
    $(php '/home/wwwroot/wxapp.dahengdian.com/think' Cang)
    $(php '/home/wwwroot/wxapp.dahengdian.com/think' Send)
    $(php '/home/wwwroot/wxapp.dahengdian.com/think' Subject)
    sleep $step
done

exit 0