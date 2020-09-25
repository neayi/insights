<?php

function init($data, $default)
{
    return isset($data) ? $data : $default;
}
