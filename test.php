<?php
    system("gpio-g mode " . $pinOut . " out"); //Configure the gpio pin
    system("gpio-g write " . $pinOut . " 1") //Set HIGH
    sleep($delay); //Delay
    system("gpio-g write " . $pinOut . " 0") //SET OFF
?>


