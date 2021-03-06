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
        'tag_novinky' => [
            'name' => 'Novinky',
            'slug' => 'novinky',
        ],
    ],
    App\Entities\ArticleEntity::class => [
        'article_1' => [
            'tag'      => '@tag_novinky',
            'user'     => '@user_admin',
            'name'     => 'Lorem ipsum',
            'slug'     => 'lorem-ipsum',
            'perex'    => '',
            'text'     => '',
            'isActive' => true,
        ],
    ],
    App\Entities\FileEntity::class => [
        'file_1' => [
            'year'      => '1970',
            'month'     => '01',
            'extension' => 'jpg',
            'joints'    => 10,
            'name'      => 'sh_2006_screenshot_1',
            'checksum'  => '36439734ba0b0262c56136821bf8ab1da0753e25',
        ],
    ],
    App\Entities\ImageEntity::class => [
        'image_1' => [
            'tag'      => '@tag_novinky',
            'user'     => '@user_admin',
            'name'     => 'Lorem ipsum',
            'file'     => '@file_1',
            'isActive' => true,
        ],
    ],
    App\Entities\VideoEntity::class => [
        'video_1' => [
            'tag'       => '@tag_novinky',
            'user'      => '@user_admin',
            'name'      => 'Lorem ipsum',
            'slug'      => 'lorem-ipsum',
            'isActive'  => true,
            'createdAt' => '<dateTimeBetween(\'2000-01-01 00:00:01\', \'2000-06-30 23:59:59\')>',
            'type'      => App\Entities\VideoEntity::TYPE_YOUTUBE,
            'url'       => 'https://www.youtube.com/watch?v=HP4VMvKnCds',
            'src'       => 'https://www.youtube.com/embed/HP4VMvKnCds',
        ],
    ],
    App\Entities\WikiEntity::class => [
        'wiki_game_1' => [
            'tag'           => '@tag_novinky',
            'related'       => [],
            'contributors'  => ['@user_admin'],
            'drafts'        => ['@wiki_draft_game_1'],
            'createdBy'     => '@user_admin',
            'lastUpdatedBy' => '@user_admin',
            'name'          => 'Lorem ipsum GAME',
            'slug'          => 'lorem-ipsum-game',
            'perex'         => '',
            'text'          => '',
            'type'          => App\Entities\WikiEntity::TYPE_GAME,
            'createdAt'     => '<dateTimeBetween(\'2000-01-01 00:00:01\', \'2000-06-30 23:59:59\')>',
            'updatedAt'     => '80%? <dateTimeBetween($createdAt, \'2000-06-30 23:59:59\')> : <dateTime($createdAt)>',
            'isActive'      => true,
        ],
        'wiki_movie_1' => [
            'tag'           => '@tag_novinky',
            'related'       => [],
            'contributors'  => ['@user_admin'],
            'drafts'        => ['@wiki_draft_movie_1'],
            'createdBy'     => '@user_admin',
            'lastUpdatedBy' => '@user_admin',
            'name'          => 'Lorem ipsum MOVIE',
            'slug'          => 'lorem-ipsum-movie',
            'perex'         => '',
            'text'          => '',
            'type'          => App\Entities\WikiEntity::TYPE_MOVIE,
            'createdAt'     => '<dateTimeBetween(\'2000-01-01 00:00:01\', \'2000-06-30 23:59:59\')>',
            'updatedAt'     => '80%? <dateTimeBetween($createdAt, \'2000-06-30 23:59:59\')> : <dateTime($createdAt)>',
            'isActive'      => true,
        ],
        'wiki_book_1' => [
            'tag'           => '@tag_novinky',
            'related'       => [],
            'contributors'  => ['@user_admin'],
            'drafts'        => ['@wiki_draft_book_1'],
            'createdBy'     => '@user_admin',
            'lastUpdatedBy' => '@user_admin',
            'name'          => 'Lorem ipsum BOOK',
            'slug'          => 'lorem-ipsum-book',
            'perex'         => '',
            'text'          => '',
            'type'          => App\Entities\WikiEntity::TYPE_BOOK,
            'createdAt'     => '<dateTimeBetween(\'2000-01-01 00:00:01\', \'2000-06-30 23:59:59\')>',
            'updatedAt'     => '80%? <dateTimeBetween($createdAt, \'2000-06-30 23:59:59\')> : <dateTime($createdAt)>',
            'isActive'      => true,
        ],
    ],
    App\Entities\WikiDraftEntity::class => [
        'wiki_draft_game_1' => [
            'wiki'      => '@wiki_game_1',
            'user'      => '@user_admin',
            'perex'     => '',
            'text'      => '',
            'createdAt' => '<dateTimeBetween(\'2000-07-01 00:00:01\', \'2000-12-31 23:59:59\')>',
        ],
        'wiki_draft_movie_1' => [
            'wiki'      => '@wiki_movie_1',
            'user'      => '@user_admin',
            'perex'     => '',
            'text'      => '',
            'createdAt' => '<dateTimeBetween(\'2000-07-01 00:00:01\', \'2000-12-31 23:59:59\')>',
        ],
        'wiki_draft_book_1' => [
            'wiki'      => '@wiki_book_1',
            'user'      => '@user_admin',
            'perex'     => '',
            'text'      => '',
            'createdAt' => '<dateTimeBetween(\'2000-07-01 00:00:01\', \'2000-12-31 23:59:59\')>',
        ],
    ],
];
