<?php

function query($string){
    $connection = mysqli_connect('localhost', 'newuser', 'password', 'dastavka');
    $connection->query($string);
}