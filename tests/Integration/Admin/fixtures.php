<?php

return [
    App\Entities\UserEntity::class => [
        'user_admin' => [
            'username'        => 'johndoe',
            'email'           => 'john.doe@example.com',
            'avatar'          => null,
            'forename'        => 'John',
            'surname'         => 'Doe',
            'password'        => '$2y$10$D7DlW8aCiF0JZfvXCpxdeeMbklNC0nJ7IcvdpwIgQoHtWTLQ1UVK2',
            'salt'            => 'X1QWzJRBy3',
            'role'            => App\Entities\UserEntity::ROLE_ADMINISTRATOR,
            'token'           => null,
            'tokenCreatedAt'  => null,
            'isAuthenticated' => true,
        ],
    ],
];
