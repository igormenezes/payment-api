<?php

foreach(glob('Database' . '/*.php') as $filename) {
    require $filename;
}

foreach(glob('Api/Gateway' . '/*.php') as $filename) {
    require $filename;
}

foreach(glob('Model/Entity' . '/*.php') as $filename) {
    require $filename;
}

foreach(glob('Model/Payment' . '/*.php') as $filename) {
    require $filename;
}

foreach(glob('Model/Validation/Payment' . '/*.php') as $filename) {
    require $filename;
}

foreach(glob('Cronjob/Payment' . '/*.php') as $filename) {
    require $filename;
}

use Cronjob\Payment\Process;

Process::initialize();