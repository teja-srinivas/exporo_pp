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

    // Almost the full alphabet, without reserved ones like "e"
    const POSSIBLE_VARIABLE_LETTERS = 'abcdfghijklmnopqrstuvwxyz';
    const RESERVED_NAMES = [
        'pi', 'sin', 'cos', 'tan',  'cot',
        'sind', 'cosd', 'tand', 'cotd',
        'arcsin', 'arccos', 'arctan', 'arccot',
        'exp', 'log', 'lg', 'sqrt',
        'sinh', 'cosh', 'tanh', 'coth',
        'arsinh', 'arcosh', 'artanh', 'arcoth',
        'abs', 'sgn', 'e', 'nan', 'inf',
    ];

    protected $fillable = [
        'name',
        'formula',
    ];

    /**
     * Cached formula object so we can execute the calculation
     * multiple times during a data import.
     *
     * This caches the node tree as well as
     * the variable replacement done via regex.
     *
     * @var Node
     */
    protected $parsedFormula;

    protected $variableNames;


    // Fix for the audit package not detecting this method (for some reason)
    protected function asDateTime($value)
    {
        return $this->parentAsDateTime($value);
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'schema_id');
    }

    public function setFormulaAttribute(string $formula)
    {
        $this->parsedFormula = null;
        $this->variableNames = null;

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
    public function calculate(array $variables): float
    {
        // We cache the AST for faster lookups
        $ast = $this->getOrReparseFormula();

        // Only fill in the values from our known variables from the formula,
        // we do not care about any others that have been provided
        $replacedVariables = array_map(function ($name) use ($variables) {
            return $variables[$name];
        }, array_flip($this->variableNames));

        return $ast->accept(new Evaluator($replacedVariables));
    }

    protected function getOrReparseFormula(): Node
    {
        if ($this->parsedFormula !== null) {
            return $this->parsedFormula;
        }

        $parser = new StdMathParser();

        return $this->parsedFormula = $parser->parse($this->replaceVariablesWithLetters());
    }

    protected function replaceVariablesWithLetters(): string
    {
        // Replace all variables with temporary single-letter equivalents
        // as the parser does not support multi-letter names
        $this->variableNames = [];

        return preg_replace_callback('/[a-z]+/i', function (array $matches) {
            $name = $matches[0];

            if (in_array($name, self::RESERVED_NAMES)) {
                return $name;
            }

            if (isset($this->variableNames[$name])) {
                return $this->variableNames[$name];
            }

            $replacement = self::POSSIBLE_VARIABLE_LETTERS[count($this->variableNames)];
            $this->variableNames[$name] = $replacement;

            return $replacement;
        }, $this->formula);
    }
}
