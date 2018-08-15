<?php

namespace App;

use App\Traits\Dateable;
use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use Illuminate\Database\Eloquent\Model;
use MathParser\Interpreting\Evaluator;
use MathParser\Parsing\Nodes\Node;
use MathParser\StdMathParser;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @property string $name
 * @property string $formula
 */
class Schema extends Model implements AuditableContract
{
    use Auditable;
    use OptimusEncodedRouteKey;
    use Dateable {
        asDateTime as parentAsDateTime;
    }

    protected $fillable = [
        'name',
        'formula',
    ];


    // Fix for the audit package not detecting this method (for some reason)
    protected function asDateTime($value)
    {
        return $this->parentAsDateTime($value);
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'schema_id');
    }

    // TODO change this signature to match the correct calculation
    public function calculate(int $firstVar, float $secondVar, float $thirdVar, float $fourthVar): float
    {
        // We cache the AST for faster lookups
        $cacheKey = "schema.formulas.{$this->formula}";

        /** @var Node $ast */
        $ast = cache()->rememberForever($cacheKey, function () {
            $parser = new StdMathParser();
            return $parser->parse($this->formula);
        });

        // TODO parse the proper variables
        return $ast->accept(new Evaluator([
            'x' => $firstVar,
            'y' => $secondVar,
            'z' => $thirdVar,
            'a' => $fourthVar,
        ]));
    }
}
