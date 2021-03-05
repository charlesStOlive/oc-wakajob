<?php namespace Waka\Wakajob\Models;

use Model;

/**
 * Job Model
 */
class Job extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'waka_wakajob_jobs';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array
     */
    protected $jsonable = ['metadata'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    public function getMetadata(): array
    {
        if (!$this->metadata) {
            return [];
        }

        return $this->metadata;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        $statusId = $this->status;
        $translations = [
            0 => trans('waka.wakajob::lang.jobs.in_queue'),
            1 => trans('waka.wakajob::lang.jobs.in_progress'),
            2 => trans('waka.wakajob::lang.jobs.complete'),
            3 => trans('waka.wakajob::lang.jobs.error'),
            4 => trans('waka.wakajob::lang.jobs.stopped'),
        ];
        if (array_key_exists($statusId, $translations)) {
            return $translations[$statusId];
        }

        return trans('waka.wakajob::lang.jobs.unknown');
    }

    /**
     * @return float
     */
    public function progressPercent(): float
    {
        if ((int)$this->progress_max === 0) {
            $this->progress_max = 1;
        }

        return round(($this->progress * 100) / $this->progress_max);
    }

    /**
     * @return bool
     */
    public function canBeCanceled(): bool
    {
        return \in_array($this->status, [0, 1], true);
    }
}
