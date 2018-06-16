<?php

namespace App;

use App\Jobs\LoadFile;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Class File
 * @package App
 *
 * @property integer $id
 * @property string $url
 * @property string $name
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class File extends Model
{
    const STATUS_PENDING = 0;
    const STATUS_DOWNLOADING = 1;
    const STATUS_COMPLETE = 2;
    const STATUS_ERROR = 3;

    protected $fillable = ['url'];

    /**
     * @param array $attributes
     * @param bool $dispatch_job
     * @return File|static
     * @throws \Throwable
     */
    public static function create(array $attributes = [], $dispatch_job = true)
    {
        $file = new self($attributes);
        $file->status = self::STATUS_PENDING;
        $file->name = basename($file->url);
        $file->saveOrFail();

        if ($dispatch_job) {
            dispatch(new LoadFile($file));
        }

        return $file;
    }

    public function getStatusTitleAttribute()
    {
        switch ($this->status) {
            case self::STATUS_PENDING:
                return trans('file.status_pending');
                break;
            case self::STATUS_DOWNLOADING:
                return trans('file.status_downloading');
                break;
            case self::STATUS_COMPLETE:
                return trans('file.status_complete');
                break;
            default:
                return trans('file.status_error');
                break;
        }
    }

    /**
     * @return bool
     * @throws \Throwable
     */
    public function download()
    {
        if (!in_array($this->status, [self::STATUS_PENDING, self::STATUS_ERROR])) {
            return false;
        }

        $this->status = self::STATUS_DOWNLOADING;
        $this->saveOrFail();

        $e = null;
        try {
            if (!Storage::put($this->id, file_get_contents($this->url))) {
                $this->status = File::STATUS_ERROR;
                $this->saveOrFail();
                return false;
            }
        } catch (\Throwable $e) {
            $this->status = File::STATUS_ERROR;
            $this->saveOrFail();
            throw $e;
        }

        $this->status = File::STATUS_COMPLETE;
        $this->saveOrFail();

        return true;
    }
}
