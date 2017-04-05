<?php
namespace kak\rbac\interfaces;

interface IGroupRole
{
    const ROLE_SUPER_ADMIN = 'superadmin';
    const ROLE_ADMIN       = 'admin';
    const ROLE_USER        = 'user';
    const ROLE_MANAGER     = 'manager';
    const ROLE_GUEST     = 'guest';
}