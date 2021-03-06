<?php
/**
 * Created by eneasdh-fs - 11/12/16 09:35 PM.
 */

namespace Enea\Sequenceable\Model;

use Enea\Sequenceable\Contracts\SequenceContract;
use Enea\Sequenceable\Serie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Model Sequence.
 *
 * Attributes
 *
 * @property  int sequence
 * @property  string id
 * @property  string source
 * @property  string column_id
 * */
class Sequence extends Model implements SequenceContract
{
    /**
     * Codification adler32.
     *
     * @var string
     */
    const HASH = 'adler32';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sequences';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['next', 'prev', 'current'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'source', 'column_id', 'sequence'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'sequence' => 'int'
    ];

    /**
     * Returns the previous sequence.
     *
     * @return int
     */
    public function getPrevAttribute(): int
    {
        $prev = $this->sequence;
        return --$prev;
    }

    /**
     * Returns the current sequence.
     *
     * @return int
     */
    public function getCurrentAttribute(): int
    {
        return $this->sequence;
    }

    /**
     * Returns the next sequence.
     *
     * @return int
     */
    public function getNextAttribute(): int
    {
        $next = $this->sequence;
        return ++$next;
    }

    /**
     * {@inheritdoc}
     */
    public function next(): int
    {
        return ++$this->sequence;
    }

    /**
     * {@inheritdoc}
     */
    public function prev(): int
    {
        return --$this->sequence;
    }

    /**
     * {@inheritdoc}
     * */
    public function current(): int
    {
        return $this->sequence;
    }

    /**
     * {@inheritdoc}
     * */
    public function getColumnID(): string
    {
        return $this->column_id;
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceValue(): string
    {
        return $this->source;
    }

    /**
     * {@inheritdoc}
     */
    public function getSeriesFrom(string $table): Collection
    {
        return static::query()->where('source', '=', $table)->get();
    }

    /**
     * {@inheritdoc}
     */
    public function incrementOneTo(string $table, Serie $serie): int
    {
        $model = $this->findOrCreateSerieModel($table, $serie);
        $model->increment('sequence', 1);
        return $model->getAttributeValue('sequence');
    }

    protected function findOrCreateSerieModel(string $table, Serie $serie): Model
    {
        $columnID = $serie->getColumnID();
        $serieID = $this->createSerieID($table, $columnID);

        return static::query()->firstOrCreate(['id' => $serieID], [
            'source' => $table,
            'column_id' => $columnID,
            'created_at' => Carbon::now(),
        ]);
    }

    protected function createSerieID(string $table, string $columnID): string
    {
        return hash(self::HASH, "{$table}.{$columnID}", false);
    }
}
