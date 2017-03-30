<?php

return [
    App\Entities\UserEntity::class => [
        'user_admin' => [
            'username' => 'johndoe',
            'email' => 'john.doe@example.com',
            'avatar' => null,
            'forename' => 'John',
            'surname' => 'Doe',
            'password' => '$2y$10$D7DlW8aCiF0JZfvXCpxdeeMbklNC0nJ7IcvdpwIgQoHtWTLQ1UVK2',
            'salt' => 'X1QWzJRBy3',
            'role' => 99,
            'token' => null,
            'tokenCreatedAt' => null,
            'isAuthenticated' => true,
        ],
        'user_{1..50}' => [
            'username (unique)' => '<username()><current()>',
            'email (unique)' => '<username()>@example.com',
            'avatar' => null,
            'forename' => '<firstName()>',
            'surname' => '<lastName()>',
            'password' => null,
            'salt' => null,
            'role' => 1,
            'token' => null,
            'tokenCreatedAt' => null,
            'isAuthenticated' => true,
        ],
    ],
    App\Entities\TagEntity::class => [
        'tag_article_novinky' => [
            'name' => 'Novinky',
            'slug' => 'novinky',
        ],
        'tag_article_povidky' => [
            'name' => 'Povídky',
            'slug' => 'povidky',
        ],
        'tag_article_recenze' => [
            'name' => 'Recenze',
            'slug' => 'recenze',
        ],
        'tag_article_rozhovory' => [
            'name' => 'Rozhovory',
            'slug' => 'rozhovory',
        ],
        'tag_article_navody' => [
            'name' => 'Návody',
            'slug' => 'navody',
        ],
        'tag_movie_silent_hill_revelation' => [
            'name' => 'Silent Hill Revelation',
            'slug' => 'silent-hill-revelation',
        ],
        'tag_book_silent_hill_v_nitru_hynouci' => [
            'name' => 'Silent Hill V Nitru Hynoucí',
            'slug' => 'silent-hill-v-nitru-hynouci',
        ],
        'tag_book_silent_hill_among_the_damned' => [
            'name' => 'Silent Hill Among The Damned',
            'slug' => 'silent-hill-among-the-damned',
        ],
        'tag_book_silent_hill_paint_it_black' => [
            'name' => 'Silent Hill Paint It Black',
            'slug' => 'silent-hill-paint-it-black',
        ],
        'tag_book_silent_hill_the_grinning_man' => [
            'name' => 'Silent Hill The Grinning Man',
            'slug' => 'silent-hill-the-grinning-man',
        ],
        'tag_book_silent_hill_dead_alive' => [
            'name' => 'Silent Hill Dead/Alive',
            'slug' => 'silent-hill-dead-alive',
        ],
        'tag_book_silent_hill_hunger' => [
            'name' => 'Silent Hill Hunger',
            'slug' => 'silent-hill-hunger',
        ],
        'tag_book_silent_hill_sinners_reward' => [
            'name' => 'Silent Hill Sinner\'s Reward',
            'slug' => 'silent-hill-sinners-reward',
        ],
        'tag_book_silent_hill_past_life' => [
            'name' => 'Silent Hill Past Life',
            'slug' => 'silent-hill-past-life',
        ],
        'tag_book_silent_hill_downpour_annes_story' => [
            'name' => 'Silent Hill Downpour Anne\'s Story',
            'slug' => 'silent-hill-downpour-annes-story',
        ],
        'tag_silent_hill' => [
            'name' => 'Silent Hill',
            'slug' => 'silent-hill',
        ],
        'tag_silent_hill_2' => [
            'name' => 'Silent Hill 2',
            'slug' => 'silent-hill-2',
        ],
        'tag_silent_hill_3' => [
            'name' => 'Silent Hill 3',
            'slug' => 'silent-hill-3',
        ],
        'tag_silent_hill_4_the_room' => [
            'name' => 'Silent Hill 4 The Room',
            'slug' => 'silent-hill-4-the-room',
        ],
        'tag_silent_hill_origins' => [
            'name' => 'Silent Hill Origins',
            'slug' => 'silent-hill-origins',
        ],
        'tag_silent_hill_homecoming' => [
            'name' => 'Silent Hill Homecoming',
            'slug' => 'silent-hill-homecoming',
        ],
        'tag_silent_hill_shattered_memories' => [
            'name' => 'Silent Hill Shattered Memories',
            'slug' => 'silent-hill-shattered-memories',
        ],
        'tag_silent_hill_downpour' => [
            'name' => 'Silent Hill Downpour',
            'slug' => 'silent-hill-downpour',
        ],
        'tag_silent_arcade' => [
            'name' => 'Silent Hill The Arcade',
            'slug' => 'silent-hill-the-arcade',
        ],
        'tag_silent_orphan' => [
            'name' => 'Silent Hill Orphan',
            'slug' => 'silent-hill-orphan',
        ],
        'tag_nezaraditelne' => [
            'name' => 'Nezařaditelné',
            'slug' => 'nezaraditelne',
        ],
    ],
    App\Entities\ArticleEntity::class => [
        'article_{1..100}' => [
            'tag' => '90%? @tag_article* : @tag_nezaraditelne',
            'user' => '@user*',
            'name (unique)' => '<sentence(3)>',
            'slug (unique)' => '<slug()>',
            'perex' => '<sentences(5, true)>',
            'text' => file_get_contents(__DIR__ . '/article.html'),
            'isActive' => '80%? 1 : 0',
        ],
    ],
    App\Entities\FileEntity::class => [
        'file_halo_of_the_sun' => [
            'year' => '1970',
            'month' => '01',
            'name' => 'halo_of_the_sun',
            'extension' => 'jpg',
            'checksum' => '4b843dc5f600e9448cc33d8022ef0937a6e1508c',
            'joints' => 250,
        ],
    ],
    App\Entities\ImageEntity::class => [
        'image_{1..250}' => [
            'tag' => '90%? @tag_silent* : @tag_nezaraditelne',
            'user' => '@user*',
            'name (unique)' => '50%? <sentence(3)>',
            'file' => '@file_halo_of_the_sun',
            'isActive' => '80%? 1 : 0',
        ],
    ],
    App\Entities\VideoEntity::class => [
        'video_base (template)' => [
            'tag' => '90%? @tag_silent* : @tag_nezaraditelne',
            'user' => '@user*',
            'name (unique)' => '<sentence(3)>',
            'slug (unique)' => '<slug()>',
            'isActive' => '80%? 1 : 0',
            'createdAt' => '<dateTimeBetween(\'2000-01-01 00:00:01\', \'2000-06-30 23:59:59\')>',
        ],
        'video_youtube_base (template, extends video_base)' => [
            'type' => App\Entities\VideoEntity::TYPE_YOUTUBE,
        ],
        'video_vimeo_base (template, extends video_base)' => [
            'type' => App\Entities\VideoEntity::TYPE_VIMEO,
        ],
        'video_youtube_1_{1..10} (extends video_youtube_base)' => [
            'url' => 'https://www.youtube.com/watch?v=HP4VMvKnCds',
            'src' => 'https://www.youtube.com/embed/HP4VMvKnCds',
        ],
        'video_youtube_2_{1..10} (extends video_youtube_base)' => [
            'url' => 'https://www.youtube.com/watch?v=dk7JkSArEdQ',
            'src' => 'https://www.youtube.com/embed/dk7JkSArEdQ',
        ],
        'video_youtube_3_{1..10} (extends video_youtube_base)' => [
            'url' => 'https://www.youtube.com/watch?v=E1VKvED76WQ',
            'src' => 'https://www.youtube.com/embed/E1VKvED76WQ',
        ],
        'video_youtube_4_{1..10} (extends video_youtube_base)' => [
            'url' => 'https://www.youtube.com/watch?v=irl9FCHpXV0',
            'src' => 'https://www.youtube.com/embed/irl9FCHpXV0',
        ],
        'video_youtube_5_{1..10} (extends video_youtube_base)' => [
            'url' => 'https://www.youtube.com/watch?v=8cJbo88NE3Y',
            'src' => 'https://www.youtube.com/embed/8cJbo88NE3Y',
        ],
        'video_youtube_6_{1..10} (extends video_youtube_base)' => [
            'url' => 'https://www.youtube.com/watch?v=GncqhAQD3gI',
            'src' => 'https://www.youtube.com/embed/GncqhAQD3gI',
        ],
        'video_youtube_7_{1..10} (extends video_youtube_base)' => [
            'url' => 'https://www.youtube.com/watch?v=WWMGZe6iucw',
            'src' => 'https://www.youtube.com/embed/WWMGZe6iucw',
        ],
        'video_youtube_8_{1..10} (extends video_youtube_base)' => [
            'url' => 'https://www.youtube.com/watch?v=IK13ntirEzE',
            'src' => 'https://www.youtube.com/embed/IK13ntirEzE',
        ],
        'video_youtube_9_{1..10} (extends video_youtube_base)' => [
            'url' => 'https://www.youtube.com/watch?v=R0vPhc63rk8',
            'src' => 'https://www.youtube.com/embed/R0vPhc63rk8',
        ],
        'video_youtube_10_{1..10} (extends video_youtube_base)' => [
            'url' => 'https://www.youtube.com/watch?v=Ck8_rccTBXg',
            'src' => 'https://www.youtube.com/embed/Ck8_rccTBXg',
        ],
        'video_vimeo_1_{1..10} (extends video_vimeo_base)' => [
            'url' => 'https://vimeo.com/20712083',
            'src' => 'https://player.vimeo.com/video/20712083',
        ],
        'video_vimeo_2_{1..10} (extends video_vimeo_base)' => [
            'url' => 'https://vimeo.com/22626857',
            'src' => 'https://player.vimeo.com/video/22626857',
        ],
        'video_vimeo_3_{1..10} (extends video_vimeo_base)' => [
            'url' => 'https://vimeo.com/61586233',
            'src' => 'https://player.vimeo.com/video/61586233',
        ],
        'video_vimeo_4_{1..10} (extends video_vimeo_base)' => [
            'url' => 'https://vimeo.com/108484726',
            'src' => 'https://player.vimeo.com/video/108484726',
        ],
        'video_vimeo_5_{1..10} (extends video_vimeo_base)' => [
            'url' => 'https://vimeo.com/2094684',
            'src' => 'https://player.vimeo.com/video/2094684',
        ],
        'video_vimeo_6_{1..10} (extends video_vimeo_base)' => [
            'url' => 'https://vimeo.com/13995605',
            'src' => 'https://player.vimeo.com/video/13995605',
        ],
        'video_vimeo_7_{1..10} (extends video_vimeo_base)' => [
            'url' => 'https://vimeo.com/1527726',
            'src' => 'https://player.vimeo.com/video/1527726',
        ],
        'video_vimeo_8_{1..10} (extends video_vimeo_base)' => [
            'url' => 'https://vimeo.com/27463649',
            'src' => 'https://player.vimeo.com/video/27463649',
        ],
        'video_vimeo_9_{1..10} (extends video_vimeo_base)' => [
            'url' => 'https://vimeo.com/78179',
            'src' => 'https://player.vimeo.com/video/78179',
        ],
        'video_vimeo_10_{1..10} (extends video_vimeo_base)' => [
            'url' => 'https://vimeo.com/31841180',
            'src' => 'https://player.vimeo.com/video/31841180',
        ],
    ],
    App\Entities\WikiEntity::class => [
        'wiki_game_{1..100}' => [
            'tag' => '@tag_silent*',
            'related' => '80%? <numberBetween(1, 5)>x @wiki_game_*',
            'contributors' => '80%? <numberBetween(5, 25)>x @user*',
            'drafts' => '<numberBetween(5, 25)>x @wiki_draft_game_*',
            'createdBy' => '@user*',
            'lastUpdatedBy' => '@user*',
            'name (unique)' => '<sentence(3)>',
            'slug (unique)' => '<slug()>',
            'perex' => '<sentences(5, true)>',
            'text' => file_get_contents(__DIR__ . '/article.html'),
            'type' => 1,
            'isActive' => '80%? 1 : 0',
            'createdAt' => '<dateTimeBetween(\'2000-01-01 00:00:01\', \'2000-06-30 23:59:59\')>',
            'updatedAt' => '80%? <dateTimeBetween($createdAt, \'2000-06-30 23:59:59\')> : <dateTime($createdAt)>',
        ],
        'wiki_movie_{1..100}' => [
            'tag' => '50%? @tag_silent_hill : @tag_movie_silent_hill_revelation',
            'related' => '80%? <numberBetween(1, 5)>x @wiki_movie_*',
            'contributors' => '80%? <numberBetween(5, 25)>x @user*',
            'drafts' => '<numberBetween(5, 25)>x @wiki_draft_movie_*',
            'createdBy' => '@user*',
            'lastUpdatedBy' => '@user*',
            'name (unique)' => '<sentence(3)>',
            'slug (unique)' => '<slug()>',
            'perex' => '<sentences(5, true)>',
            'text' => file_get_contents(__DIR__ . '/article.html'),
            'type' => 2,
            'isActive' => '80%? 1 : 0',
            'createdAt' => '<dateTimeBetween(\'2000-01-01 00:00:01\', \'2000-06-30 23:59:59\')>',
            'updatedAt' => '80%? <dateTimeBetween($createdAt, \'2000-06-30 23:59:59\')> : <dateTime($createdAt)>',
        ],
        'wiki_book_{1..100}' => [
            'tag' => '@tag_book_*',
            'related' => '80%? <numberBetween(1, 5)>x @wiki_book_*',
            'contributors' => '80%? <numberBetween(5, 25)>x @user*',
            'drafts' => '<numberBetween(5, 25)>x @wiki_draft_book_*',
            'createdBy' => '@user*',
            'lastUpdatedBy' => '@user*',
            'name (unique)' => '<sentence(3)>',
            'slug (unique)' => '<slug()>',
            'perex' => '<sentences(5, true)>',
            'text' => file_get_contents(__DIR__ . '/article.html'),
            'type' => 3,
            'isActive' => '80%? 1 : 0',
            'createdAt' => '<dateTimeBetween(\'2000-01-01 00:00:01\', \'2000-06-30 23:59:59\')>',
            'updatedAt' => '80%? <dateTimeBetween($createdAt, \'2000-06-30 23:59:59\')> : <dateTime($createdAt)>',
        ],
    ],
    App\Entities\WikiDraftEntity::class => [
        'wiki_draft_game_{1..50}' => [
            'wiki' => '@wiki_game_*',
            'user' => '@user*',
            'perex' => '<sentences(5, true)>',
            'text' => file_get_contents(__DIR__ . '/article.html'),
            'createdAt' => '<dateTimeBetween(\'2000-07-01 00:00:01\', \'2000-12-31 23:59:59\')>',
        ],
        'wiki_draft_movie_{1..50}' => [
            'wiki' => '@wiki_movie_*',
            'user' => '@user*',
            'perex' => '<sentences(5, true)>',
            'text' => file_get_contents(__DIR__ . '/article.html'),
            'createdAt' => '<dateTimeBetween(\'2000-07-01 00:00:01\', \'2000-12-31 23:59:59\')>',
        ],
        'wiki_draft_book_{1..50}' => [
            'wiki' => '@wiki_book_*',
            'user' => '@user*',
            'perex' => '<sentences(5, true)>',
            'text' => file_get_contents(__DIR__ . '/article.html'),
            'createdAt' => '<dateTimeBetween(\'2000-07-01 00:00:01\', \'2000-12-31 23:59:59\')>',
        ],
    ],
];
