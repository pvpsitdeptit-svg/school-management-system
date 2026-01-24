<?php

if (!function_exists('school')) {
    /**
     * Get the current school from the request
     */
    function school()
    {
        return app('current_school');
    }
}

if (!function_exists('school_id')) {
    /**
     * Get the current school ID from the request
     */
    function school_id()
    {
        return school()?->id;
    }
}
