<?php declare(strict_types=1);

function routes(?string $page): Controller {
    $className = ucfirst($page) . "Controller";
    $className = class_exists($className) ? $className : 'HomeController';

    if (class_exists($className)) {
        return new $className();
    } else {
        throw new Exception('404 - Controller not found');
    }
}
