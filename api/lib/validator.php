<?php

function isValidUsername($username) {
    return !empty($username);
}

function isValidPassword($password) {
    return !empty($password) && strlen($password) >= 8;
}