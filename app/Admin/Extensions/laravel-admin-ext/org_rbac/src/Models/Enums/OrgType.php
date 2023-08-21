<?php


namespace Encore\OrgRbac\Models\Enums;


class OrgType
{
    const PLATFORM = 0;
    const COMPANY = 1;
    const DEPARTMENT = 2;
    const USER = 3;

    public static $index = [
        self::PLATFORM => 'platform',
        self::COMPANY => 'company',
        self::DEPARTMENT => 'department',
        self::USER => 'user'
    ];

}
