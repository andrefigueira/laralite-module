<?php

namespace Modules\Laralite\Models;

use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

/**
 * @mixin Eloquent
 */
class Roles extends Role
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'guard_name', 'created_at', 'updated_at',
    ];

    /**
     * @param $users
     * @return bool|Collection|Model|User|User[]
     * @throws Exception
     */
    public function assignUsers($users)
    {
        try {
            if ($users && is_array($users)) {
                foreach ($users as $user) {
                    $item = User::findOrFail($user);
                    if ($item) {
                        if (!$item->assignRole($this)) {
                            return false;
                        }
                    }
                }
                return true;
            } elseif ($users) {
                $item = User::findOrFail($users);
                return $item->assignRole($this);
            }
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
