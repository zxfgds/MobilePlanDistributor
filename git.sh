#!/bin/bash

# git pull
git pull

# git add .
git add .

# git commit
datetime=$(date '+%Y-%m-%d %H:%M:%S')
git commit -m "$datetime"

# git push
git push

