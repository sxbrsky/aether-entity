<?php

use Nulldark\Tests\Stubs\User;

require_once __DIR__ . '/vendor/autoload.php';

    $manager = new \Nulldark\DBAL\ConnectionManager();
    $manager->addConnection([
        'driver' => 'mysql', 'dsn' => 'mysql:host=127.0.0.1;dbname=foo', 'username' => 'root', 'password' => 'root']);

    $em = new \Nulldark\ORM\EntityManager($manager->connection('default'));
    $users = $em->getRepository(User::class)
        ->findBy(['name' => 'bar']);

    var_dump($users);