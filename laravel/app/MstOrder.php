<?php

namespace App;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class MstOrder extends Model
{
    public function logs()
    {
        return $this->morphToMany(Log::class, 'model');
    }

    /**
     * @param CarbonImmutable $fromDate
     * @param CarbonImmutable $toDate
     * @param float $maxProfitRate
     * @param int $limit
     * @param int $offset
     * @return Collection
     */
    public function getOrders(CarbonImmutable $fromDate, CarbonImmutable $toDate, float $maxProfitRate, int $limit, int $offset)
    {
        return self::where('created_at', '>=', $fromDate)->where('created_at', '<=', $toDate)->where('max_profit_rate', '<>', $maxProfitRate)->limit($limit)->offset($offset)->get();
    }
}
