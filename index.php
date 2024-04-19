<?php
/*  
@@@@@@ @@@@@@  @@@      @@@@@@@      @@@@@@@  @@@@@@@@ @@@  @@@
@@         @@! @@!        @@!        @@!  @@@ @@!      @@!  @@@
!@@!!   @!!!:  @!!        @!!        @!@  !@! @!!!:!   @!@  !@!
   !:!     !!: !!:        !!:        !!:  !!! !!:       !: .:! 
::.: :  ::: ::  : ::.: :    :         :: :  :  : :: :::    ::   

link  https://github.com/S3LTDev/S3LT-Social-Template
author  max2tz https://github.com/S3LT
license  GPL-3.0 License
*/
session_start();

require_once './vendor/prototype/autoload.php';
require_once './func/function.php';

/* Activity updated, only if it isn't an api */

if (isset($_SESSION['user'])) {
    if (substr( trim($_SERVER['REQUEST_URI'], '/'), 0, 3 ) != "api") {
        updateActivity($_SESSION['user']->id, true);
    }
}

/* Routes */

route('index', '/', function () {
    return redirect('homePage', true);
}, ['GET']);

route('homePage', '/home', function () {
    return template('home.template.php');
}, ['GET']);

route('userPageDynamic', '/user/{User Id}', function ($userId) {
    return template('profile.template.php', ['userId' => $userId]);
}, ['GET']);

/* Logged in user Area */

if (isset($_SESSION['user'])) {

    route('userPage', '/user', function () {
        return template('profile.template.php', ['userId' => $_SESSION['user_id']]);
    }, ['GET']);

    route('eventPage', '/event', function () {
        return template('event.template.php');
    }, ['GET']);

    route('shopPage', '/shop', function () {
        return template('shop.template.php', ['userId' => $_SESSION['user_id']]);
    }, ['GET']);

    /* Chat Pages */
    route('alphaChat', '/chat/alpha', function () {
        return template('/chats/Alpha_Talk.template.php');
    }, ['GET']);

    route('moderatorChat', '/chat/moderator', function () {
        return template('/chats/Mod_Chat.template.php');
    }, ['GET']);

    route('loungeChat', '/chat/lounge', function () {
        return template('/chats/chatting_lounge.template.php');
    }, ['GET']);


    /* Api interaction */

    route('shopItems', 'api.shop.items', function () {

        if ( isset($_POST['pageIndex']) && isset($_POST['pageSize']) && isset($_POST['rarity']) && isset($_POST['itemName']) ) {
            $response = [
                'status' => 'success',
                'items' => fetchShopItemsByPage($_POST['pageIndex'], $_POST['pageSize'], $_POST['rarity'], $_POST['itemName']),
                'pageIndex' => $_POST['pageIndex'],
                'pageSize' => $_POST['pageSize'],
                'rarity' => $_POST['rarity'],
                'itemName' => $_POST['itemName']
            ];
        } else {
            $response = [
                'status' => 'error'
            ];
        }
    
        header('Content-Type: application/json');
        return json_encode($response);    
    }, ['POST']);

    route('shopBuy', 'api.shop.buy', function() {
        if ( isset($_POST['itemId']) && ($item = fetchItem($_POST['itemId'])) && isset($_POST['userId']) && ($user = fetchUser($_POST['userId']))) {
            $response = buyShopItem($user, $item);
        } else {
            $response = [
                'status' => 'error',
                'error' => 'Failed to get information.'
            ];
        }
    
        header('Content-Type: application/json');
        return json_encode($response);
    }, ['POST']);

    route('rarityList', 'api.rarity.list', function () {
        $response = [
            'status' => 'success',
            'rarity' => fetchRarityList(),
        ];
    
        header('Content-Type: application/json');
        return json_encode($response);   
    }, ['POST']);

    route('updateDisplayname', 'api.user.displayname', function () {
        if (isset($_SESSION['user_id']) && isset($_POST['displayname'])) {
            $response = updateDisplayname($_SESSION['user_id'], $_POST['displayname']);
        } else {
            $response = [
                'status' => 'error',
                'error' => 'Failed to get information.'
            ];
        }
    
        header('Content-Type: application/json');
        return json_encode($response); 
    }, ['POST']);
}

/* Admin Area */

if (isset($_SESSION['admin']) && $_SESSION['admin']) {

    route('adminPage', '/admin.dashboard', function () {
        return template('admin/index.template.php');
    });

    route('userListPage', '/admin.userlist', function () {
        return template('admin/userlist.template.php');
    });

    route('userDetail', '/admin.userdetail/{User id}', function (string $userId) {
        return template('admin/userdetail.template.php', ['userId' => $userId]);
    }, ['GET']);


    /* Admin Api interaction */

    route('userList', 'api.admin.userlist', function () {
        $response = [
            'status' => 'success',
            'users' => fetchUserList()
        ];

        header('Content-Type: application/json');
        return json_encode($response);
    });

    route('suspendUser', 'api.admin.suspend/{User id}', function (string $userId) {
        if (fetchUser($userId)) {
            if ( suspendUser($userId) ) {
                $response = [
                    'status' => 'success'
                ];
            } else {
                $response = [
                    'status' => 'error'
                ];
            }   
        } else {
            $response = [
                'status' => 'error'
            ];
        }

        header('Content-Type: application/json');
        return json_encode($response);
    }, ['GET']);

    route('unsuspendUser', 'api.admin.unsuspend/{User id}', function (string $userId) {
        if (fetchUser($userId)) {
            if ( unsuspendUser($userId) ) {
                $response = [
                    'status' => 'success'
                ];
            } else {
                $response = [
                    'status' => 'error'
                ];
            }   
        } else {
            $response = [
                'status' => 'error'
            ];
        }

        header('Content-Type: application/json');
        return json_encode($response);
    }, ['GET']);

    route('updateGems', 'api.admin.updateGems/{User id, Int gems}', function (string $userId, int $gems) {
        if (fetchUser($userId)) {
            if (updateGems($userId, $gems) ) {
                $response = [
                    'status' => 'success'
                ];
            } else {
                $response = [
                    'status' => 'error'
                ];
            }   
        } else {
            $response = [
                'status' => 'error'
            ];
        }

        header('Content-Type: application/json');
        return json_encode($response);
    }, ['POST']);

    route('addBadge', 'api.admin.addBadge', function () {
        if (isset($_POST['userId']) && isset($_POST['badge']) && fetchUser($_POST['userId'])) {
            if ($badge_id = addBadge($_POST['userId'], $_POST['badge']) ) {
                $response = [
                    'status' => 'success',
                    'id' => $badge_id,
                ];
            } else {
                $response = [
                    'status' => 'error'
                ];
            }
        } else {
            $response = [
                'status' => 'error'
            ];
        }
    
        header('Content-Type: application/json');
        return json_encode($response);
    }, ['POST']);
    route('removeBadge', 'api.admin.removeBadge', function () {
        if (isset($_POST['userId']) && removeBadge($_POST['badgeId']) ) {
            $response = [
                'status' => 'success'
            ];
        } else {
            $response = [
                'status' => 'error'
            ];
        }

        header('Content-Type: application/json');
        return json_encode($response);
    }, ['POST']);

    route('addItem', 'api.admin.addItem/', 
        function (string $userId, string $name, string $asset, string $rarity, int $price) {
        if (isset($_POST['userId']) && isset($_POST['name']) && isset($_POST['asset']) && isset($_POST['rarity']) && isset($_POST['price']) && fetchUser($_POST['userId'])) {
            if ($id = addItemtoInventory($_POST['userId'], $_POST['name'], $_POST['asset'], $_POST['rarity'], $_POST['price'])) {
                $response = [
                    'status' => 'success',
                    'id' => $id,
                ];
            } else {
                $response = [
                    'status' => 'error'
                ];
            }
        } else {
            $response = [
                'status' => 'error'
            ];
        }

        header('Content-Type: application/json');
        return json_encode($response);
    }, ['POST']);

    route('removeItem', 'api.admin.removeItem/{String inventoryId}', function (string $inventoryId) {
        if (removeItem($inventoryId) ) {
            $response = [
                'status' => 'success'
            ];
        } else {
            $response = [
                'status' => 'error'
            ];
        }

        header('Content-Type: application/json');
        return json_encode($response);
    }, ['POST']);

    route('addEvent', 'api.admin.addEvent/{User host, String platform, String game, Date startTime}', 
        function (string $host, string $platform, string $game, string $startTime) {
        if (fetchUser($host)) {
            if (addEvent($platform, $game, $startTime, $host)) {
                $response = [
                    'status' => 'success'
                ];
            } else {
                $response = [
                    'status' => 'error'
                ];
            }
        } else {
            $response = [
                'status' => 'error'
            ];
        }

        header('Content-Type: application/json');
        return json_encode($response);
    }, ['POST']);

    route('removeEvent', 'api.admin.removeEvent/{String eventId}', function (string $eventId) {
        if (removeEvent($eventId) ) {
            $response = [
                'status' => 'success'
            ];
        } else {
            $response = [
                'status' => 'error'
            ];
        }

        header('Content-Type: application/json');
        return json_encode($response);
    }, ['POST']);

    route('deleteUser', 'api.admin.deleteUser/{User id}', function (string $userId) {
        if (fetchUser($userId)) {
            if (deleteUser($userId) ) {
                $response = [
                    'status' => 'success'
                ];
            } else {
                $response = [
                    'status' => 'error'
                ];
            }   
        } else {
            $response = [
                'status' => 'error'
            ];
        }

        header('Content-Type: application/json');
        return json_encode($response);
    }, ['POST']);
}

/* Api interaction */

route('userInfo', 'api.user.info/{User id}', function (string $userId) {

    if (fetchUser($userId)) {

        if (!file_exists(session_save_path().'/sess_'.fetchSessionId($userId))) {
            $status = 0;
        } else {
            if (time() - fetchActivity($userId) < 300) {
                $status = 1;
            } else {
                $status = 2;
            }
        }

        $response = [
            'status' => 'success',
            'id' => $userId,
            'username' => fetchUsername($userId),
            'displayname' => fetchDisplayname($userId),
            'avatar' => fetchAvatar($userId),
            'yourProfile' => isset($_SESSION['user_id']) && $_SESSION['user_id'] === $userId,
            'online' => $status,
            'gems' => fetchGems($userId),
            'events' => fetchEventsByUserId($userId),
            'inventory' => fetchInventory($userId),
            'badges' => fetchBadges($userId),
            'admin' => isadmin($userId)
        ];
    } else {
        $response = [
            'status' => 'error'
        ];
    }


    header('Content-Type: application/json');
    return json_encode($response);
}, ['GET']);

route('userCount', 'api.user.count', function () {
    $response = [
        'all_user' => getAllUserCount(),
        'online_user' => getOnlineUserCount(),
        'admin_user' => getAdminUserCount(),
        'badge_user' => getBadgeUserCount(),
    ];

    header('Content-Type: application/json');
    return json_encode($response);
}, ['GET']);

route('searchUser', 'api.search.user', function () {

    if ( isset($_POST['search']) ) {
        $response = [
            'status' => 'success',
            'users' => fetchUserByUserName($_POST['search']),
            'search' => $_POST['search']
        ];
    } else {
        $response = [
            'status' => 'error'
        ];
    }    

    header('Content-Type: application/json');
    return json_encode($response);    
}, ['POST']);


route('event', 'api.event', function () {

    $response = [
        'status' => 'success',
        'events' => fetchEvents()
    ];

    header('Content-Type: application/json');
    return json_encode($response);
}, ['GET']);


/* Discord interaction */

route('discordLogin', 'discord.login', function () {
    return redirect('discordCallback', true, ['action' => 'login']);
});

route('discordLogout', 'discord.logout', function () {
    return redirect('discordCallback', true, ['action' => 'logout']);
});

route('discordCallback', 'discord.login.callback', function () {
    include "./func/discord.php";
});

/* Start */

serve();
