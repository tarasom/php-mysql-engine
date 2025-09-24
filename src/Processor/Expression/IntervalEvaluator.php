<?php
namespace Vimeo\MysqlEngine\Processor\Expression;

use Vimeo\MysqlEngine\FakePdoInterface;
use Vimeo\MysqlEngine\Processor\QueryResult;
use Vimeo\MysqlEngine\Processor\Scope;
use Vimeo\MysqlEngine\Query\Expression\IntervalOperatorExpression;

final class IntervalEvaluator
{
    /**
     * @param array<string, mixed> $row
     * @return array{value: int, unit: string}|null
     */
    public static function evaluate(
        FakePdoInterface $conn,
        Scope $scope,
        IntervalOperatorExpression $expr,
        array $row,
        QueryResult $result
    ) {
        if (!$expr->isWellFormed()) {
            throw new \RuntimeException("Malformed INTERVAL expression");
        }

        $number = Evaluator::evaluate($conn, $scope, $expr->number, $row, $result);

        if ($number === null) {
            return null;
        }

        if (!is_numeric($number)) {
            throw new \RuntimeException("INTERVAL requires numeric value, got " . gettype($number));
        }

        $unit = $expr->unit;

        return [
            'value' => (int)$number,
            'unit'  => $unit,
        ];
    }
}
