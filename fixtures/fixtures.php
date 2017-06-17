<?php

return [
    App\Entities\UserEntity::class => [
        'user_with_role_user' => [
            'username'        => 'jakedoe',
            'email'           => 'jake.doe@example.com',
            'avatar'          => null,
            'forename'        => 'Jake',
            'surname'         => 'Doe',
            'password'        => '$2y$10$MayaJ6bYeXfbeE.jZCZRV.7ZLrldlAUl0nB9re9hlsyi2dnluNhhW',
            'salt'            => 'YpEeJhODZO',
            'role'            => App\Entities\UserEntity::ROLE_USER,
            'token'           => null,
            'tokenCreatedAt'  => null,
            'isAuthenticated' => true,
        ],
        'user_with_role_moderator' => [
            'username'        => 'janedoe',
            'email'           => 'jane.doe@example.com',
            'avatar'          => null,
            'forename'        => 'Jane',
            'surname'         => 'Doe',
            'password'        => '$2y$10$r.DcvGlflt.iJ384uvuvL.qqhPUo5zM9mSr/fvvcnGg.v/Lelu0uu',
            'salt'            => 'f3nViQAQ3y',
            'role'            => App\Entities\UserEntity::ROLE_MODERATOR,
            'token'           => null,
            'tokenCreatedAt'  => null,
            'isAuthenticated' => true,
        ],
        'user_with_role_administrator' => [
            'username'        => 'johndoe',
            'email'           => 'john.doe@example.com',
            'avatar'          => null,
            'forename'        => 'John',
            'surname'         => 'Doe',
            'password'        => '$2y$10$dKzLYfuCnf/Vu8RLpb/RS.awomYcdjm1hCuhBHZRu.1cNFbMjkwqi',
            'salt'            => 'LS3OHTwjYt',
            'role'            => App\Entities\UserEntity::ROLE_ADMINISTRATOR,
            'token'           => null,
            'tokenCreatedAt'  => null,
            'isAuthenticated' => true,
        ],
        'user_{1..47}' => [
            'username (unique)' => '<username()><current()>',
            'email (unique)'    => '<username()>@example.com',
            'avatar'            => null,
            'forename'          => '<firstName()>',
            'surname'           => '<lastName()>',
            'password'          => null,
            'salt'              => null,
            'role'              => App\Entities\UserEntity::ROLE_USER,
            'token'             => null,
            'tokenCreatedAt'    => null,
            'isAuthenticated'   => true,
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
            'tag'           => '90%? @tag_article* : @tag_nezaraditelne',
            'user'          => '@user*',
            'name (unique)' => '<sentence(3)>',
            'slug (unique)' => '<slug()>',
            'perex'         => '<sentences(4, true)>',
            'text'          => file_get_contents(__DIR__ . '/article.html'),
            'isActive'      => '80%? 1 : 0',
        ],
    ],
    App\Entities\FileEntity::class => [
        'file_base (template)' => [
            'year'      => '1970',
            'month'     => '01',
            'extension' => 'jpg',
            'joints'    => 10,
        ],
        'file_1 (extends file_base)' => [
            'name'     => 'sh_2006_screenshot_1',
            'checksum' => '36439734ba0b0262c56136821bf8ab1da0753e25',
        ],
        'file_2 (extends file_base)' => [
            'name'     => 'sh_2006_screenshot_2',
            'checksum' => '551db3e906b95fcbce16a76d10cd472ff42c70c4',
        ],
        'file_3 (extends file_base)' => [
            'name'     => 'sh_2006_screenshot_3',
            'checksum' => '0e3fe2a54f5c69cd5fc4910fcbec1b3d38635368',
        ],
        'file_4 (extends file_base)' => [
            'name'     => 'sh_2006_screenshot_4',
            'checksum' => 'ba1c2656c9023804c194d5cff915364c0da3c0ae',
        ],
        'file_5 (extends file_base)' => [
            'name'     => 'sh_2006_screenshot_5',
            'checksum' => 'da8636762c21af23088fb148184c41184485f2b4',
        ],
        'file_6 (extends file_base)' => [
            'name'     => 'sh_2006_screenshot_6',
            'checksum' => 'b219a926ede3171e1097d4862130d983d6a4dc44',
        ],
        'file_7 (extends file_base)' => [
            'name'     => 'sh2_screenshot_1',
            'checksum' => 'e976c6888f31fd646e924acd49fd71090fcd8541',
        ],
        'file_8 (extends file_base)' => [
            'name'     => 'sh2_screenshot_2',
            'checksum' => '0d10e4f63560c17b0ac1ea0858a38d67770ebb42',
        ],
        'file_9 (extends file_base)' => [
            'name'     => 'sh2_screenshot_3',
            'checksum' => 'ab6612c8dc85bcb7861f74325209b5f759c9fa53',
        ],
        'file_10 (extends file_base)' => [
            'name'     => 'sh2_screenshot_4',
            'checksum' => 'a655afa17777a883244a788e5bb4788f9682af96',
        ],
        'file_11 (extends file_base)' => [
            'name'     => 'sh2_screenshot_5',
            'checksum' => '6aa224049b3b602d591e41f1878e668a359d6c26',
        ],
        'file_12 (extends file_base)' => [
            'name'     => 'sh2_screenshot_6',
            'checksum' => '81fb44c4d9bd8a7322dd0f4e97a09b83a401810e',
        ],
        'file_13 (extends file_base)' => [
            'name'     => 'sh3_screenshot_1',
            'checksum' => '2eafbc1da221982b0f3a5402b736f01382e116b6',
        ],
        'file_14 (extends file_base)' => [
            'name'     => 'sh3_screenshot_2',
            'checksum' => 'b1f8b80d515e747424d2a86e9e83f473db338b6f',
        ],
        'file_15 (extends file_base)' => [
            'name'     => 'sh3_screenshot_3',
            'checksum' => '23c7b778318d6632759eacd7d2f6c7897abf1afe',
        ],
        'file_16 (extends file_base)' => [
            'name'     => 'sh3_screenshot_4',
            'checksum' => '8791d64a77898bdf90c250047417922acb14ff9f',
        ],
        'file_17 (extends file_base)' => [
            'name'     => 'sh3_screenshot_5',
            'checksum' => 'ee879c58e84f90d366428bcee6c1b9b67c36c703',
        ],
        'file_18 (extends file_base)' => [
            'name'     => 'sh3_screenshot_6',
            'checksum' => '6035916da42dca3f66a5891c17c2e464e1df643a',
        ],
        'file_19 (extends file_base)' => [
            'name'     => 'sh3_screenshot_7',
            'checksum' => '8c8e84eb1c0e8e23847831a6fe2a4d6b11afdcda',
        ],
        'file_20 (extends file_base)' => [
            'name'     => 'sh3_screenshot_8',
            'checksum' => '2f006b814dd6370c95ff57055c1eab26b94d3445',
        ],
        'file_21 (extends file_base)' => [
            'name'     => 'sh3_screenshot_9',
            'checksum' => 'b5570cf4fab6320edf168a6a566ade0e59f0d22c',
        ],
        'file_22 (extends file_base)' => [
            'name'     => 'sh3_screenshot_10',
            'checksum' => '5aedd3e6f863dfba12649103e5bf8fd22df88b08',
        ],
        'file_23 (extends file_base)' => [
            'name'     => 'sh4_screenshot_1',
            'checksum' => 'd2ee2923957e5e36f356c1128b3f8c7221df9baa',
        ],
        'file_24 (extends file_base)' => [
            'name'     => 'sh4_screenshot_2',
            'checksum' => 'd58dc3e56bd59d6956f6d9ec57cfd703ab4d954d',
        ],
        'file_25 (extends file_base)' => [
            'name'     => 'sh4_screenshot_3',
            'checksum' => '2975eafe38765582804de63fe6a27365b59d73d3',
        ],
        'file_26 (extends file_base)' => [
            'name'     => 'sh4_screenshot_4',
            'checksum' => '1745dab849f4081bf648c4277bfea060561a457b',
        ],
        'file_27 (extends file_base)' => [
            'name'     => 'sh4_screenshot_5',
            'checksum' => 'e90dad62b53951f06ae5d1c90820c693b32be251',
        ],
        'file_28 (extends file_base)' => [
            'name'     => 'sh_downpour_screenshot_1',
            'checksum' => '1f6fa948b0c04e6819afdc97aa4819d664ae780d',
        ],
        'file_29 (extends file_base)' => [
            'name'     => 'sh_downpour_screenshot_2',
            'checksum' => 'f0b3759b3e700c96f476d02dc8a4ef25b3b5573c',
        ],
        'file_30 (extends file_base)' => [
            'name'     => 'sh_downpour_screenshot_3',
            'checksum' => '7f135169db8c641391c6fdd858f034e081f8e936',
        ],
        'file_31 (extends file_base)' => [
            'name'     => 'sh_downpour_screenshot_4',
            'checksum' => 'adac3e9cf938c92a1458e8883f0d515ee208cd52',
        ],
        'file_32 (extends file_base)' => [
            'name'     => 'sh_downpour_screenshot_5',
            'checksum' => '88f870853dba669ec7d784f4029e71a84bae9fab',
        ],
        'file_33 (extends file_base)' => [
            'name'     => 'sh_downpour_screenshot_6',
            'checksum' => '44335cf42154b5f9956ecbcf5c0ad620fcc18216',
        ],
        'file_34 (extends file_base)' => [
            'name'     => 'sh_downpour_screenshot_7',
            'checksum' => '38fc5146b11c7b70e41cc5bef676f9200802e962',
        ],
        'file_35 (extends file_base)' => [
            'name'     => 'sh_downpour_screenshot_8',
            'checksum' => '47eef436ac4e2c7834a0bfa3ec29663a633ed82a',
        ],
    ],
    App\Entities\ImageEntity::class => [
        'image_{1..350}' => [
            'tag'           => '90%? @tag_silent* : @tag_nezaraditelne',
            'user'          => '@user*',
            'name (unique)' => '50%? <sentence(3)>',
            'file'          => '@file_*',
            'isActive'      => '80%? 1 : 0',
        ],
    ],
    App\Entities\VideoEntity::class => [
        'video_base (template)' => [
            'tag'           => '90%? @tag_silent* : @tag_nezaraditelne',
            'user'          => '@user*',
            'name (unique)' => '<sentence(3)>',
            'slug (unique)' => '<slug()>',
            'isActive'      => '80%? 1 : 0',
            'createdAt'     => '<dateTimeBetween(\'2000-01-01 00:00:01\', \'2000-06-30 23:59:59\')>',
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
        'wiki_game_base (template)' => [
            'tag'           => '@tag_silent*',
            'related'       => '80%? <numberBetween(1, 5)>x @wiki_game_*',
            'contributors'  => '80%? <numberBetween(5, 25)>x @user*',
            'drafts'        => '<numberBetween(5, 25)>x @wiki_draft_game_*',
            'createdBy'     => '@user*',
            'lastUpdatedBy' => '@user*',
            'name (unique)' => '<sentence(3)>',
            'slug (unique)' => '<slug()>',
            'perex'         => '<sentences(4, true)>',
            'text'          => file_get_contents(__DIR__ . '/article.html'),
            'type'          => App\Entities\WikiEntity::TYPE_GAME,
            'createdAt'     => '<dateTimeBetween(\'2000-01-01 00:00:01\', \'2000-06-30 23:59:59\')>',
            'updatedAt'     => '80%? <dateTimeBetween($createdAt, \'2000-06-30 23:59:59\')> : <dateTime($createdAt)>',
        ],
        'wiki_game_active_{1..80} (extends wiki_game_base)' => [
            'isActive' => true,
        ],
        'wiki_game_not_active_{1..20} (extends wiki_game_base)' => [
            'isActive' => false,
        ],
        'wiki_movie_base (template)' => [
            'tag'           => '50%? @tag_silent_hill : @tag_movie_silent_hill_revelation',
            'related'       => '80%? <numberBetween(1, 5)>x @wiki_movie_*',
            'contributors'  => '80%? <numberBetween(5, 25)>x @user*',
            'drafts'        => '<numberBetween(5, 25)>x @wiki_draft_movie_*',
            'createdBy'     => '@user*',
            'lastUpdatedBy' => '@user*',
            'name (unique)' => '<sentence(3)>',
            'slug (unique)' => '<slug()>',
            'perex'         => '<sentences(4, true)>',
            'text'          => file_get_contents(__DIR__ . '/article.html'),
            'type'          => App\Entities\WikiEntity::TYPE_MOVIE,
            'createdAt'     => '<dateTimeBetween(\'2000-01-01 00:00:01\', \'2000-06-30 23:59:59\')>',
            'updatedAt'     => '80%? <dateTimeBetween($createdAt, \'2000-06-30 23:59:59\')> : <dateTime($createdAt)>',
        ],
        'wiki_movie_active_{1..80} (extends wiki_movie_base)' => [
            'isActive' => true,
        ],
        'wiki_movie_not_active_{1..20} (extends wiki_movie_base)' => [
            'isActive' => false,
        ],
        'wiki_book_base (template)' => [
            'tag'           => '@tag_book_*',
            'related'       => '80%? <numberBetween(1, 5)>x @wiki_book_*',
            'contributors'  => '80%? <numberBetween(5, 25)>x @user*',
            'drafts'        => '<numberBetween(5, 25)>x @wiki_draft_book_*',
            'createdBy'     => '@user*',
            'lastUpdatedBy' => '@user*',
            'name (unique)' => '<sentence(3)>',
            'slug (unique)' => '<slug()>',
            'perex'         => '<sentences(4, true)>',
            'text'          => file_get_contents(__DIR__ . '/article.html'),
            'type'          => App\Entities\WikiEntity::TYPE_BOOK,
            'createdAt'     => '<dateTimeBetween(\'2000-01-01 00:00:01\', \'2000-06-30 23:59:59\')>',
            'updatedAt'     => '80%? <dateTimeBetween($createdAt, \'2000-06-30 23:59:59\')> : <dateTime($createdAt)>',
        ],
        'wiki_book_active_{1..80} (extends wiki_book_base)' => [
            'isActive' => true,
        ],
        'wiki_book_not_active_{1..20} (extends wiki_book_base)' => [
            'isActive' => false,
        ],
    ],
    App\Entities\WikiDraftEntity::class => [
        'wiki_draft_game_{1..50}' => [
            'wiki'      => '@wiki_game_active_*',
            'user'      => '@user*',
            'perex'     => '<sentences(4, true)>',
            'text'      => file_get_contents(__DIR__ . '/article.html'),
            'createdAt' => '<dateTimeBetween(\'2000-07-01 00:00:01\', \'2000-12-31 23:59:59\')>',
        ],
        'wiki_draft_movie_{1..50}' => [
            'wiki'      => '@wiki_movie_active_*',
            'user'      => '@user*',
            'perex'     => '<sentences(4, true)>',
            'text'      => file_get_contents(__DIR__ . '/article.html'),
            'createdAt' => '<dateTimeBetween(\'2000-07-01 00:00:01\', \'2000-12-31 23:59:59\')>',
        ],
        'wiki_draft_book_{1..50}' => [
            'wiki'      => '@wiki_book_active_*',
            'user'      => '@user*',
            'perex'     => '<sentences(4, true)>',
            'text'      => file_get_contents(__DIR__ . '/article.html'),
            'createdAt' => '<dateTimeBetween(\'2000-07-01 00:00:01\', \'2000-12-31 23:59:59\')>',
        ],
    ],
];
