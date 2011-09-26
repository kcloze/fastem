#!/bin/bash
mysqldump --opt --databases fastem > ./bak.sql --user=fastem --password=fastem --host=127.0.0.1
