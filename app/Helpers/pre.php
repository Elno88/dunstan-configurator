<?php

function pre($var, $die = true)
{
    if($die){
        echo '<pre>'.print_r($var,true).'</pre>';
        die();
    } else {
        echo '<pre>'.print_r($var,true).'</pre>';
    }
}
