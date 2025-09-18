<?php

use Illuminate\Support\Facades\Broadcast;

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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

   Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    // Return user info or false if not participant
    $isParticipant = \DB::table('conversation_user')
        ->where('conversation_id', $conversationId)
        ->where('user_id', $user->id)
        ->exists();

     if ($isParticipant) {
        return ['id' => $user->id, 'name' => $user->name];
      }

       return false;
   });
