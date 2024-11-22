<?php

namespace App\Services\App\Module;

interface InitDetailsInterface
{
    public static function displayOptions(): array;

    public static function welcomeText(): array;

    public static function modelGroups(): array;

    public static function categories(): array;
}
