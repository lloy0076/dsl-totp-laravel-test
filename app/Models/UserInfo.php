<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInfo extends Model
{
    use SoftDeletes;

    /**
     * The table.
     *
     * @var string
     */
    protected $table = 'user_info';

    /**
     * The fillable values.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'provisioning_uri', 'secret', 'token'];

    /**
     * The user relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}
