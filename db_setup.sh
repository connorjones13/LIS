#!/bin/bash

MYSQL=`which mysql`

Q1="CREATE DATABASE COMP3700_ECC DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;"
Q2="GRANT ALL ON *.* TO 'comp3700_ecc'@'localhost' IDENTIFIED BY 'needmorecoffee';"
Q3="FLUSH PRIVILEGES;"
SQL="${Q1}${Q2}${Q3}"

$MYSQL -uroot -pneedmorecoffee -e "$SQL"