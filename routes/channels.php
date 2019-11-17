<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// only allows admins to subscribe to app events
Broadcast::channel('app.events', function ($user) {
    return $user->isSuperAdmin();
});
