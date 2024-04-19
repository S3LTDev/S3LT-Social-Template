<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', 300); //300 seconds = 5 minutes. In case if your CURL is slow and is loading too much (Can be IPv6 problem)

error_reporting(E_ALL);

$ini_array = parse_ini_file("./config.ini", true);

define('OAUTH2_CLIENT_ID', $ini_array['DISCORD_ID']);
define('OAUTH2_CLIENT_SECRET', $ini_array['DISCORD_SECRET']);

$redirectUri = $ini_array['DISCORD_REDIRECT'];
$authorizeURL = 'https://discord.com/api/oauth2/authorize';
$tokenURL = 'https://discord.com/api/oauth2/token';
$apiURLBase = 'https://discord.com/api/users/@me';
$revokeURL = 'https://discord.com/api/oauth2/token/revoke';

if (get('action') == 'login') {

    $params = array(
        'client_id' => OAUTH2_CLIENT_ID,
        'redirect_uri' => $redirectUri,
        'response_type' => 'code',
        'scope' => 'identify email guilds guilds.members.read'
    );

    header('Location: https://discord.com/api/oauth2/authorize' . '?' . http_build_query($params));
    die();
}


if (get('code')) {
    $token = apiRequest($tokenURL, array(
        "grant_type" => "authorization_code",
        'client_id' => OAUTH2_CLIENT_ID,
        'client_secret' => OAUTH2_CLIENT_SECRET,
        'redirect_uri' => $redirectUri,
        'code' => get('code')
    ));

    $logout_token = $token->access_token;
    if (login($token) === false) {
        logout($revokeURL, array(
            'token' => session('access_token'),
            'token_type_hint' => 'access_token',
            'client_id' => OAUTH2_CLIENT_ID,
            'client_secret' => OAUTH2_CLIENT_SECRET,
        ));
    }

    return redirect('homePage');
}

if (get('action') == 'logout') {
    logout($revokeURL, array(
        'token' => session('access_token'),
        'token_type_hint' => 'access_token',
        'client_id' => OAUTH2_CLIENT_ID,
        'client_secret' => OAUTH2_CLIENT_SECRET,
    ));
    return redirect('homePage');
}

if (session('access_token')) {
    return redirect('homePage');
}