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
    App\Entities\TagEntity::class => [
        'tag_a' => [
            'name' => 'Tag A',
            'slug' => 'tag-a',
        ],
    ],
    App\Entities\ArticleEntity::class => [
        'article_1' => [
            'tag'           => '@tag_a',
            'user'          => '@user_admin',
            'name (unique)' => 'Article A',
            'slug (unique)' => 'article-a',
            'perex'         => 'Lorem ipsum dolor sit amet...',
            'text'          => 'Lorem ipsum dolor sit amet...',
            'isActive'      => true,
        ],
        'article_2' => [
            'tag'           => '@tag_a',
            'user'          => '@user_admin',
            'name (unique)' => 'Article B',
            'slug (unique)' => 'article-b',
            'perex'         => 'Lorem ipsum dolor sit amet...',
            'text'          => 'Lorem ipsum dolor sit amet...',
            'isActive'      => true,
        ],
        'article_3' => [
            'tag'           => '@tag_a',
            'user'          => '@user_admin',
            'name (unique)' => 'Article C',
            'slug (unique)' => 'article-c',
            'perex'         => 'Lorem ipsum dolor sit amet...',
            'text'          => 'Lorem ipsum dolor sit amet...',
            'isActive'      => true,
        ],
        'article_4' => [
            'tag'           => '@tag_a',
            'user'          => '@user_admin',
            'name (unique)' => 'Article D',
            'slug (unique)' => 'article-d',
            'perex'         => 'Lorem ipsum dolor sit amet...',
            'text'          => 'Lorem ipsum dolor sit amet...',
            'isActive'      => true,
        ],
        'article_5' => [
            'tag'           => '@tag_a',
            'user'          => '@user_admin',
            'name (unique)' => 'Article E',
            'slug (unique)' => 'article-e',
            'perex'         => 'Lorem ipsum dolor sit amet...',
            'text'          => 'Lorem ipsum dolor sit amet...',
            'isActive'      => true,
        ],
    ],
];
