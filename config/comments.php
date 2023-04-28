<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Comment Model
    |--------------------------------------------------------------------------
    |
    | The comment model that should be used to store and retrieve the comments.
    | You can also change to your custom model.
    |
    */
    'comment_model' => \Comments\Models\Comment::class,

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | The user model that should be used when associating comments
    | with commentators.
    | Leave as null to use default user model.
    |
    */
    'user_model' => null,

];
