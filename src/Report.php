<?php

/*
 * This file is part of Laravel Reportable.
 *
 * (c) Brian Faust <hello@brianfaust.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BrianFaust\Reportable;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = ['meta' => 'array'];

    public function reportable()
    {
        return $this->morphTo();
    }

    public function conclusion()
    {
        return $this->hasOne(Conclusion::class);
    }

    public function judge()
    {
        return $this->conclusion->judge;
    }

    public function conclude($data, Model $judge)
    {
        $conclusion = (new Conclusion())->fill(array_merge($data, [
            'judge_id'   => $judge->id,
            'judge_type' => get_class($judge),
        ]));

        $this->conclusion()->save($conclusion);

        return $conclusion;
    }

    public static function allJudges()
    {
        $judges = [];

        foreach (Conclusion::get() as $conclusion) {
            $judges[] = $conclusion->judge;
        }

        return $judges;
    }
}
