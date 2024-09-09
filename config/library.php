<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuration of library
    |--------------------------------------------------------------------------
    |
    | These values are the configuration of the library.
    |
    */

    // Numbers of days that a user can have a loaned book
    'days_of_loans' => 7,

    // Number of books that the system need to rest when it loan a book
    'less_stock' => 1,

    // Number of books that the system need to add when it receive a book
    'more_stock' => 1,

];
