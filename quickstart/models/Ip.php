<?php

/**
 * Class Ip
 *
 * Fields:
 * id - integer,primary_key,auto_increment
 * address - varchar(15), not null, unique
 * user_id - integer, foreign_key users(id)
 */
class Ip extends Eloquent
{

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'ip_addresses';

    /**
     * Get user
     *
     * @return User
     */
    public function user()
    {
        return $this->belongsTo('User');
    }

} 