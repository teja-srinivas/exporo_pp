<?php

declare(strict_types=1);

namespace App\Models;

use App\Events\SchemaUpdated;
use OwenIt\Auditing\Auditable;
use FormulaInterpreter\Compiler;
use FormulaInterpreter\Executable;
use Illuminate\Database\Eloquent\Model;
use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @property int $id
 * @property string $name
 * @property string $formula
 */
class Schema extends Model implements AuditableContract
{
    use Auditable;
    use OptimusEncodedRouteKey;

    const VAR_AMOUNT = 'investment';

    const VAR_BONUS = 'bonus';

    const VAR_MARGIN = 'marge';

    const VAR_RUNTIME = 'laufzeit';

    const VARS = [
        self::VAR_AMOUNT,
        self::VAR_BONUS,
        self::VAR_MARGIN,
        self::VAR_RUNTIME,
    ];

    protected $fillable = [
        'name',
        'formula',
    ];

    protected $dispatchesEvents = [
        'updated' => SchemaUpdated::class,
    ];

    /**
     * Cached formula object so we can execute the calculation
     * multiple times during a data import.
     *
     * @var Executable|null
     */
    protected $compiledFormula;

    public function projects()
    {
        return $this->hasMany(Project::class, 'schema_id');
    }

    public function setFormulaAttribute(string $formula)
    {
        $this->compiledFormula = null;

        $this->attributes['formula'] = $formula;
    }

    /**
     * Calculates a value based on the specified variables
     * using the formula from this model.
     *
     * @param array $variables
     * @return float
     * @throws \Exception
     */
    public function calculate(array $variables = []): float
    {
        $variables = array_change_key_case($variables, CASE_LOWER);

        return $this->getCompiledFormula()->run($variables);
    }

    protected function getCompiledFormula(): Executable
    {
        if ($this->compiledFormula !== null) {
            return $this->compiledFormula;
        }

        /** @var Compiler $compiler */
        $compiler = app(Compiler::class);

        return $this->compiledFormula = $compiler->compile(strtolower($this->formula));
    }
}
