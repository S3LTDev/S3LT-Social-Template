<?php

/**
 * ANCHOR apiRequest
 *
 * @param  mixed $url
 * @param  mixed $post
 * @param  mixed $headers
 * @return object
 */
function apiRequest($url, $post = FALSE, $headers = array()) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    $response = curl_exec($ch);


    if ($post)
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

    $headers[] = 'Accept: application/json';

    if (session('access_token'))
        $headers[] = 'Authorization: Bearer ' . session('access_token');

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    return json_decode($response);
}


/**
 * ANCHOR logout
 *
 * @param  mixed $url
 * @param  mixed $data
 * @return object
 */
function logout($url, $data = array()) {
    $ch = curl_init($url);
    curl_setopt_array($ch, array(
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
        CURLOPT_POSTFIELDS => http_build_query($data),
    ));
    $response = curl_exec($ch);
    if (session('access_token')) {
        session_regenerate_id(true);
        unset($_SESSION['access_token']);
        unset($_SESSION['user']);
        unset($_SESSION['user_id']);
        unset($_SESSION['admin']);
    }
    return json_decode($response);
}

/**
 * ANCHOR login
 *
 * @param  object $token
 * @return object
 */
function login(object $token) {
    if (!session('access_token')) {
        $_SESSION['access_token'] = $token->access_token;
        $userData = apiRequest('https://discord.com/api/users/@me');
        $_SESSION['user'] = $userData;
        $_SESSION['user_id'] = $userData->id;
        $_SESSION['admin'] = isadmin($userData->id);
        return logUser($userData);
    }
}

/**
 * ANCHOR logUser
 *
 * @param  object $user
 * @return object
 */
function logUser($user) {
    require "./func/connection.php";

    $sql = 'SELECT user_id, user_suspend FROM user_storage WHERE user_id = "' . $user->id . '"';
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {

        $sql = 'INSERT INTO user_storage (user_id, user_name, access_token, session_id, user_joined_timestamp, user_avatar, display_name)
                VALUES ("' . $user->id . '", "' . $user->username . '#' . $user->discriminator . '", "' . $_SESSION['access_token'] . '", "' . session_id() . '", "' . date('Y-m-d H:i:s') . '", "' . $user->avatar . '", "' . $user->username . '");';
        $sql .= 'INSERT INTO badge (user_id, badge)
                VALUES ("' . $user->id . '", "Beta Tester");';
        $result = $conn->multi_query($sql);

    } else {
        $result = $result->fetch_assoc();
        if ( isset($result['user_suspend']) && $result['user_suspend'] == 0 ) {
            $sql = 'UPDATE user_storage SET access_token="' . $_SESSION['access_token'] . '", session_id="' . session_id() . '", user_name="' . $user->username . '#' . $user->discriminator . '", user_avatar="' . $user->avatar . '" WHERE user_id = "' . $user->id . '"';
            $result = $conn->query($sql);
        } else
            $result = false;
    }
    $conn->close();
    return $result;
}

/**
 * ANCHOR get
 *
 * @param  string $key
 * @param  bool $default
 * @return bool
 */
function get(string $key, bool $default = NULL) {
    return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}

/**
 * ANCHOR session
 *
 * @param  string $key
 * @param  bool $default
 * @return bool
 */
function session(string $key, bool $default = NULL) {
    return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
}

/**
 * ANCHOR fetchUser
 *
 * @param  string $userId
 * @return bool|object
 */
function fetchUser(string $userId) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $userId = $conn->real_escape_string($userId);

    $sql = 'SELECT * FROM user_storage WHERE user_id = "' . $userId . '"';
    $result = $conn->query($sql);
    $conn->close();

    if ($result->num_rows == 0) return false;
    else return $result->fetch_all(MYSQLI_ASSOC)[0];
}

/**
 * ANCHOR fetchUsername
 *
 * @param  string $userId
 * @return string
 */
function fetchUsername(string $userId) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $userId = $conn->real_escape_string($userId);

    $sql = 'SELECT user_name FROM user_storage WHERE user_id = "' . $userId . '"';
    $result = $conn->query($sql);
    $conn->close();

    return $result->fetch_assoc()['user_name'];
}

/**
 * ANCHOR fetchAvatar
 *
 * @param  string $user_id
 * @return string
 */
function fetchAvatar(string $userId) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $userId = $conn->real_escape_string($userId);

    $sql = 'SELECT user_avatar FROM user_storage WHERE user_id = "' . $userId . '"';
    $result = $conn->query($sql);

    $url = 'https://cdn.discordapp.com/avatars/' . $userId . '/' . $result->fetch_assoc()['user_avatar'] . '.png';
    $conn->close();

    return $url;
}

/**
 * ANCHOR fetchSessionId
 *
 * @param  string $userId
 * @return string
 */
function fetchSessionId(string $userId) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $userId = $conn->real_escape_string($userId);

    $sql = 'SELECT session_id FROM user_storage WHERE user_id = "' . $userId . '"';
    $result = $conn->query($sql);
    $conn->close();

    return $result->fetch_assoc()['session_id'];
}

/**
 * ANCHOR isadmin
 *
 * @param  string $userId
 * @return bool
 */
function isadmin(string $userId) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $userId = $conn->real_escape_string($userId);

    $sql = 'SELECT admin FROM user_storage WHERE user_id = "' . $userId . '"';
    $result = $conn->query($sql);
    $conn->close();

    if ($result->num_rows == 0) return false;
    else return $result->fetch_all(MYSQLI_ASSOC)[0]['admin']==1;

}

/**
 * ANCHOR fetchToken
 *
 * @param  string $userId
 * @return string
 */
function fetchToken(string $userId) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $userId = $conn->real_escape_string($userId);

    $sql = 'SELECT access_token FROM user_storage WHERE user_id = "' . $userId . '"';
    $result = $conn->query($sql);
    $conn->close();

    return $result->fetch_assoc()['access_token'];
}

/**
 * ANCHOR fetchGems
 *
 * @param  string $userId
 * @return int
 */
function fetchGems(string $userId) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $userId = $conn->real_escape_string($userId);

    $sql = 'SELECT user_gems FROM user_storage WHERE user_id = "' . $userId . '"';
    $result = $conn->query($sql);
    $conn->close();

    return $result->fetch_assoc()['user_gems'];
}

/**
 * ANCHOR fetchEventsByUserId
 *
 * @param  string $userId
 * @return array
 */
function fetchEventsByUserId(string $userId) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $userId = $conn->real_escape_string($userId);

    $sql = 'SELECT id, platform, game, start_timestamp, host FROM event WHERE host="' . $userId . '"';
    $result = $conn->query($sql);

    $events = [];

    foreach ($result->fetch_all(MYSQLI_ASSOC) as $record) {
        $events[] = [
            'id' => $record['id'],
            'platform' => $record['platform'],
            'game' => $record['game'],
            'start_timestamp' => $record['start_timestamp'],
            'host' => [
                'id' => $record['host'],
                'username' => fetchUsername($record['host']),
                'avatar' => fetchAvatar($record['host']),
            ]
        ];
    }
    $conn->close();

    return $events;
}

/**
 * ANCHOR fetchEvents
 *
 * @param  string $userId
 * @return json
 */
function fetchEvents() {
    require "./func/connection.php";

    $sql = 'SELECT id, platform, game, start_timestamp, host FROM event ';
    $result = $conn->query($sql);

    $events = [];

    foreach ($result->fetch_all(MYSQLI_ASSOC) as $record) {
        $events[] = [
            'id' => $record['id'],
            'platform' => $record['platform'],
            'game' => $record['game'],
            'start_timestamp' => $record['start_timestamp'],
            'host' => [
                'id' => $record['host'],
                'username' => fetchUsername($record['host']),
                'avatar' => fetchAvatar($record['host']),
            ]
        ];
    }
    $conn->close();

    return $events;
}

/**
 * ANCHOR fetchInventory
 *
 * @param  string $userId
 * @return json
 */
function fetchInventory(string $userId) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $userId = $conn->real_escape_string($userId);

    $sql = 'SELECT 
                inventory.id, a.name, a.asset, a.price, a.rarity_id, b.rarity
            FROM
                inventory
                LEFT JOIN (SELECT * FROM shop)a ON item_id = a.id
                LEFT JOIN (SELECT * FROM a_rarity)b ON a.rarity_id = b.id
            WHERE
                user_id = "' . $userId . '"';
    $result = $conn->query($sql);
    $conn->close();

    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * ANCHOR fetchBadges
 *
 * @param  string $userId
 * @return json
 */
function fetchBadges(string $userId) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $userId = $conn->real_escape_string($userId);

    $sql = 'SELECT id, badge FROM badge WHERE user_id = "' . $userId . '"';
    $result = $conn->query($sql);
    $conn->close();

    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * ANCHOR fetchActivity
 *
 * @param  string $userId
 * @return int|null
 */
function fetchActivity(string $userId) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $userId = $conn->real_escape_string($userId);

    $sql = 'SELECT user_activity FROM user_storage WHERE user_id = "' . $userId . '"';
    $result = $conn->query($sql);
    $conn->close();

    return  strtotime($result->fetch_assoc()['user_activity']);
}

/**
 * ANCHOR updateGems
 *
 * @param  string $userId
 * @param  int $gems
 * @return object
 */
function updateGems(string $userId, int $gems) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $userId = $conn->real_escape_string($userId);
    $gems = $conn->real_escape_string($gems);

    $sql = 'UPDATE user_storage SET user_gems="' . $gems . '" WHERE user_id = "' . $userId . '"';
    $result = $conn->query($sql);
    $conn->close();

    return $result;
}

/**
 * ANCHOR updateActivity
 *
 * @param  string $userId
 * @return object
 */
function updateActivity(string $userId) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $userId = $conn->real_escape_string($userId);
    $activity = $conn->real_escape_string(date('Y-m-d H:i:s'));

    $sql = 'UPDATE user_storage SET user_activity="' . $activity . '" WHERE user_id = "' . $userId . '"';
    $result = $conn->query($sql);
    $conn->close();

    return $result;
}

/**
 * ANCHOR getAllUserCount
 *
 * @return int
 */
function getAllUserCount() {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $sql = 'SELECT COUNT(*) AS all_user_count FROM user_storage';
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        return $result->fetch_assoc()['all_user_count'];
    } else {
        return 0;
    }
    $conn->close();

    return $result;
}

/**
 * ANCHOR getOnlineUserCount
 *
 * @return int
 */
function getOnlineUserCount() {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $sql = 'SELECT COUNT(*) AS online_user_count FROM user_storage WHERE user_activity >= SUBTIME(NOW(), "00:05:00")';
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        return $result->fetch_assoc()['online_user_count'];
    } else {
        return 0;
    }
    $conn->close();

    return $result;
}

/**
 * ANCHOR getAdminUserCount
 *
 * @return int
 */
function getAdminUserCount() {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $sql = 'SELECT COUNT(*) AS admin_user_count FROM user_storage WHERE admin = 1';
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        return $result->fetch_assoc()['admin_user_count'];
    } else {
        return 0;
    }
    $conn->close();

    return $result;
}

/**
 * ANCHOR getBadgeUserCount
 *
 * @return int
 */
function getBadgeUserCount() {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $sql = 'SELECT COUNT(*) AS badge_user_count FROM badge';
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        return $result->fetch_assoc()['badge_user_count'];
    } else {
        return 0;
    }

    $conn->close();
    return $result;
}

/**
 * ANCHOR fetchUserList
 *
 * @return array
 */
function fetchUserList() {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $sql = 'SELECT * FROM user_storage';
    $result = $conn->query($sql);

    $user_list = [];
    if ($result->num_rows > 0) {
        foreach ($result->fetch_all(MYSQLI_ASSOC) as $record) {
            $user_list[] = [
                'user_id'               => $record['user_id'],
                'user_name'             => $record['user_name'],
                'user_avatar'           => 'https://cdn.discordapp.com/avatars/' . $record['user_id'] . '/' . $record['user_avatar'] . '.png',
                'user_gems'             => $record['user_gems'],
                'user_joined_timestamp' => $record['user_joined_timestamp'],
                'user_activity'         => $record['user_activity'],
                'user_suspend'          => $record['user_suspend'],
                'admin'                 => ($record['admin'] == 1) ? "Site Administrator" : "Site User",
            ];
        }
    }

    $conn->close();

    return $user_list;
}

/**
 * ANCHOR suspendUser
 *
 * @return bool
 */
function suspendUser(string $user_id) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $userId = $conn->real_escape_string($user_id);

    $sql = 'UPDATE user_storage SET user_suspend = 1 WHERE user_id = "' . $userId . '"';
    $result = $conn->query($sql);
    $conn->close();

    return $result;
}

/**
 * ANCHOR unsuspendUser
 *
 * @return bool
 */
function unsuspendUser(string $user_id) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $userId = $conn->real_escape_string($user_id);

    $sql = 'UPDATE user_storage SET user_suspend = 0 WHERE user_id = "' . $userId . '"';
    $result = $conn->query($sql);
    $conn->close();

    return $result;
}

/**
 * ANCHOR deleteUser
 *
 * @return bool
 */
function deleteUser(string $user_id) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $userId = $conn->real_escape_string($user_id);

    $sql = 'DELETE FROM user_storage WHERE user_id = "' . $userId . '"';

    // TODO: delete related data
    $result = $conn->query($sql);
    $conn->close();

    return $result;
}

/**
 * ANCHOR addBadge
 *
 * @param  string $userId
 * @param  string $badge
 * @return bool
 */
function addBadge(string $userId, string $badge) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $userId = $conn->real_escape_string($userId);
    $badge = $conn->real_escape_string($badge);

    $sql = 'INSERT INTO badge (user_id, badge) VALUES ("' . $userId . '", "' . $badge . '")';
    $result = $conn->query($sql);

    if ($result)
        $result = $conn->insert_id;
    else
        $result = 0;
    $conn->close();

    return $result;
}

/**
 * ANCHOR removeBadge
 *
 * @param  string $badgeId
 * @return bool
 */
function removeBadge(string $badgeId) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $badgeId = $conn->real_escape_string($badgeId);

    $sql = 'DELETE FROM badge WHERE (id = "' . $badgeId . '")';
    $result = $conn->query($sql);
    $conn->close();

    return $result;
}

/**
 * ANCHOR addItemtoInventory
 *
 * @param  string $userId
 * @param  string $name
 * @param  string $asset
 * @param  string $rarity
 * @param  int $price
 * @return bool
 */
function addItemtoInventory(string $userId, string $name, string $asset, string $rarity, int $price) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $userId = $conn->real_escape_string($userId);
    $name = $conn->real_escape_string($name);
    $asset = $conn->real_escape_string($asset);
    $rarity = $conn->real_escape_string($rarity);
    $price = $conn->real_escape_string($price);

    $sql = 'INSERT INTO inventory (user_id, name, asset, rarity, price) VALUES ("' . $userId . '", "' . $name . '", "' . $asset . '", "' . $rarity . '", "' . $price . '")';
    $result = $conn->query($sql);

    if ($result)
        $result = $conn->insert_id;
    else
        $result = 0;
    $conn->close();

    return $result;
}

/**
 * ANCHOR removeItem
 *
 * @param  string $inventoryId
 * @return bool
 */
function removeItem(string $inventoryId) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $inventoryId = $conn->real_escape_string($inventoryId);

    $sql = 'DELETE FROM inventory WHERE (id = "' . $inventoryId . '")';
    $result = $conn->query($sql);
    $conn->close();

    return $result;
}

/**
 * ANCHOR addItemtoInventory
 *
 * @param  string $userId
 * @param  string $name
 * @param  string $asset
 * @param  string $rarity
 * @param  int $price
 * @return bool
 */
function addEvent(string $platform, string $game, string $startTime, string $host) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $platform = $conn->real_escape_string($platform);
    $game = $conn->real_escape_string($game);
    $startTime = $conn->real_escape_string($startTime);
    $host = $conn->real_escape_string($host);

    $sql = 'INSERT INTO event (platform, game, start_timestamp, host) VALUES ("' . $platform . '", "' . $game . '", "' . $startTime . '", "' . $host . '")';
    $result = $conn->query($sql);
    
    if ($result)
        $result = $conn->insert_id;
    else
        $result = 0;
    $conn->close();

    return $result;
}

/**
 * ANCHOR removeEvent
 *
 * @param  string $eventId
 * @return bool
 */
function removeEvent(string $eventId) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $eventId = $conn->real_escape_string($eventId);

    $sql = 'DELETE FROM event WHERE (id = "' . $eventId . '")';
    $result = $conn->query($sql);
    $conn->close();

    return $result;
}


/**
 * ANCHOR fetchUserByUserName
 *
 * @param  string $userName
 * @return array
 */
function fetchUserByUserName(string $userName) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $userName = $conn->real_escape_string($userName);

    $sql = 'SELECT `user_id`, `user_name`, CONCAT("https://cdn.discordapp.com/avatars/", user_id, "/", user_avatar, ".png") `user_avatar` 
                FROM `user_storage` WHERE user_name LIKE "%' . $userName . '%"';
    $result = $conn->query($sql);
    $conn->close();

    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * ANCHOR fetchUserByUserName
 *
 * @param  int $pageIndex
 * @param  int $pageSize
 * @return array
 */
function fetchShopItemsByPage(int $pageIndex, int $pageSize, int $rarity, string $itemName) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $pageIndex = $conn->real_escape_string($pageIndex) + 0;
    $pageSize = $conn->real_escape_string($pageSize) + 0;
    $rarity = $conn->real_escape_string($rarity) + 0;
    $itemName = $conn->real_escape_string($itemName);

    $startIndex = $pageIndex * $pageSize;

    $sql_rarity = '';
    if ($rarity != 0)
        $sql_rarity = 'rarity_id = ' . $rarity . ' AND';

    $sql = 'SELECT 
                shop.*, a.rarity 
            FROM shop LEFT JOIN (SELECT * FROM a_rarity)a ON shop.rarity_id = a.id
            WHERE ' . $sql_rarity . ' name LIKE "%' . $itemName . '%"
            ORDER BY shop.id
            LIMIT ' . $startIndex . ', ' . $pageSize;
    $result = $conn->query($sql);
    $conn->close();

    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * ANCHOR fetchRarityList
 *
 * @return array
 */
function fetchRarityList() {
    require "./func/connection.php";

    $sql = 'SELECT id, rarity FROM a_rarity WHERE del_flag = 0';
    $result = $conn->query($sql);
    $conn->close();

    return array_merge(
            array( array(
                'id' => "0",
                'rarity' => 'All'
            )), $result->fetch_all(MYSQLI_ASSOC)
        );
}


/**
 * ANCHOR buyShopItem
 *
 * @param  array $user
 * @param  array $item
 * @return object
 */
function buyShopItem(array $user, array $item) {
    require "./func/connection.php";

    $itemPrice = $item['price'] + 0;
    $userGems = $user['user_gems'] + 0;

    if ($itemPrice <= $userGems) {
        $userGems -= $itemPrice;

        // update user's gems
        $sql = 'UPDATE user_storage SET user_gems = "' . $userGems . '" WHERE user_id = "' . $user['user_id'] . '"';
        $result = $conn->query($sql);

        // add item in user's inventory
        $sql = 'INSERT INTO inventory (user_id, item_id) VALUES ("' . $user['user_id'] . '", "' . $item['id'] . '")';
        $result = $conn->query($sql);

        return ['status' => 'success'];
    } else {
        return [
            'status' => 'error',
            'message' => 'You do not have enough funds for this item.'
        ];
    }
}  


/**
 * ANCHOR fetchItem
 *
 * @param  string $item
 * @return bool|object
 */
function fetchItem(string $item) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $item = $conn->real_escape_string($item);

    $sql = 'SELECT * FROM shop WHERE id = "' . $item . '"';
    $result = $conn->query($sql);
    $conn->close();

    if ($result->num_rows == 0) return false;
    else return $result->fetch_all(MYSQLI_ASSOC)[0];
}

/**
 * ANCHOR fetchDisplayname
 *
 * @param  string $userId
 * @return string
 */
function fetchDisplayname(string $userId) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $userId = $conn->real_escape_string($userId);

    $sql = 'SELECT display_name FROM user_storage WHERE user_id = "' . $userId . '"';
    $result = $conn->query($sql);
    $conn->close();

    if ($result->num_rows == 0) return '';
    else return $result->fetch_all(MYSQLI_ASSOC)[0]['display_name'];
}

/**
 * ANCHOR updateDisplayname
 *
 * @param  string $userId
 * @param  string $displayname
 * @return string
 */
function updateDisplayname(string $userId, string $displayname) {
    require "./func/connection.php";

    /* SQLI SECURITY */
    $userId = $conn->real_escape_string($userId);
    $displayname = $conn->real_escape_string($displayname);

    /// todo

    $sql = 'UPDATE user_storage SET display_name = "' . $displayname . '", update_display_timestamp = NOW() WHERE user_id = "' . $userId . '"';
    $result = $conn->query($sql);
    $conn->close();

    if ($result) {
        return [
            'status' => 'success'
        ];
    }
    
    return [
        'status' => 'error',
        'message' => 'Failed to save data.'
    ];
}

