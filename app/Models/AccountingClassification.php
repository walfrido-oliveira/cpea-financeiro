<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Models\AccountingConfig;
use App\Models\TotalStaticCheckPoint;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use ChrisKonnertz\StringCalc\StringCalc;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountingClassification extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'obs', 'level', 'classification', 'type_classification', 'featured',
    'color', 'bolder', 'accounting_classification_id', 'visible', 'unity', 'order', 'featured_color'
  ];

  /**
   * The parent
   */
  public function parent()
  {
    return $this->belongsTo(self::class);
  }

  /**
   * Configs
   */
  public function accountingConfigs()
  {
    return $this->belongsToMany(AccountingConfig::class);
  }


  /**
   * The children
   */
  public function children()
  {
    return $this->hasMany(self::class, 'accounting_classification_id');
  }

  /**
   * The Formula
   */
  public function formula()
  {
    return $this->hasMany(Formula::class);
  }

  /**
   * Returns the depth of an Instance
   * @param $idToFind
   * @return int
   */
  public static function DepthHelper($idToFind)
  {
    return AccountingClassification::GetParentHelper($idToFind);
  }

  // Recursive Helper function
  public static function  GetParentHelper($id, $depth = 0)
  {
    $model = AccountingClassification::find($id);

    if ($model) {
      if ($model->accounting_classification_id != null) {
        $depth++;

        return AccountingClassification::GetParentHelper($model->accounting_classification_id, $depth);
      } else {
        return $depth;
      }
    }
  }

  /**
   * Get the depth of this instance from the top-level instance.
   */
  public function getDepthAttribute()
  {
    return AccountingClassification::DepthHelper($this->id);
  }

  /**
   * Get types
   *
   * @return Array
   */
  public static function getTypesClassifications()
  {
    return ['DRE', 'RETIRADAS GERENCIAIS', 'RESULTADOS DO EXERCICIO', 'DRE AJUSTÁVEL'];
  }

  /**
   * Get types
   *
   * @return Array
   */
  public static function getTypesClassifications2()
  {
    return [
      'DRE' => 'DRE',
      'RETIRADAS GERENCIAIS' => 'Retiradas Gerenciais',
      'RESULTADOS DO EXERCICIO' => 'Resultado do Exercicio',
      'DRE AJUSTÁVEL' => 'DRE Ajustável',
      'RL' => 'RL',
      'NSR' => 'NSR'
    ];
  }

  /**
   * Get types
   *
   * @return Array
   */
  public static function getUnitys()
  {
    return ['R$', '%'];
  }

  /**
   * Get types
   *
   * @return Array
   */
  public static function getUnitys2()
  {
    return [
      'R$' => 'R$',
      '%' => '%',
    ];
  }

  public function getDescriptionAttribute()
  {
    return $this->classification . '-' . $this->name;
  }

  /**
   * Get calc name
   *
   * @return string
   */
  public function getCalcNameAttribute()
  {
    return '{' . $this->classification . '&' . $this->name . '}';
  }

  /**
   * Get total by classification
   *
   * @param int $month
   * @param int $year
   * @return int
   */
  public function getTotalClassificationWithdrawal($month, $year)
  {
    $accountingConfig = AccountingConfig::where('month', $month)
      ->where('year', $year)
      ->first();

    $formula = null;
    if ($accountingConfig) {
      $formula = $accountingConfig->formulas()
        ->where('accounting_classification_id', $this->id)
        ->whereNotIn('type_classification', ['RL', 'NSR'])
        ->first();
    }

    if ($formula) {
      $re = '/{(.*?)}/m';
      $formulaText = $formula->formula;
      preg_match_all($re, $formulaText, $matches, PREG_SET_ORDER, 0);
      $sum = 0;

      foreach ($matches as $value2) {
        $result = explode("&", $value2[1]);

        $classification = self::where('classification', $result[0])->where('name', $result[1])->first();

        if ($classification) {
          $withdrawal = Withdrawal::where('accounting_classification_id', $classification->id)
            ->where('month', $month)
            ->where('year', $year)
            ->first();
          if ($withdrawal) {
            $sum = $withdrawal->value;
          } elseif ($classification->type_classification == 'RETIRADAS GERENCIAIS') {
            $sum = $classification->getTotalClassificationWithdrawal($month, $year);
          }
        }
        $formulaText = Str::replace($value2[0], $sum, $formulaText);
      }
      $stringCalc = new StringCalc();
      if (Str::contains($formulaText, '/0')) return 0;
      $result = $stringCalc->calculate($formulaText);
      return $this->unity == 'R$' ? round($result, 0, PHP_ROUND_HALF_UP) : $result;
    }
    return 0;
  }

  /**
   * Get total by classification by month
   *
   * @param int $month
   * @param int $year
   * @return int
   */
  public static function getTotalClassificationByMonthWithdrawal($month, $year)
  {
    $total = 0;
    $accountingConfig = AccountingConfig::where('month', $month)
      ->where('year', $year)
      ->first();

    if ($accountingConfig) {
      foreach ($accountingConfig->accountingClassifications()->where('type_classification', 'RETIRADAS GERENCIAIS')->get() as $accountingClassification) {
        $total += $accountingClassification->getTotalClassificationWithdrawal($month, $year);
      }
    }
    return $total;
  }

  public function getEspecialFomulas($year, $type)
  {
    $formula = Formula::where('accounting_classification_id', $this->id)
        ->where('type_classification', $type)
        ->first();

    if ($formula) {
        $re = '/{(.*?)}/m';
        $formulaText = $formula->formula;
        preg_match_all($re, $formulaText, $matches, PREG_SET_ORDER, 0);
        $sum = 0;

        foreach ($matches as $value2) {
          $result = explode("&", $value2[1]);
          $classification = self::where('classification', $result[0])->where('name', $result[1])->first();
          if(!Cache::has("total-dre-$classification->id")) Cache::put("total-dre-$classification->id", $classification->getTotal($year), 60);
          $sum = Cache::get("total-dre-$classification->id");
          $formulaText = Str::replace($value2[0], $sum, $formulaText);
        }

        $stringCalc = new StringCalc();
        if (Str::contains($formulaText, '0/0')) return 0;

        try {
            $result = $stringCalc->calculate($formulaText);
        } catch (\Throwable $th) {
            abort(500, "Erro ao calcular formula $formula->id: $formulaText");
        }


        return  $result;
    }

    return 0;
  }

  /**
   * Get total by classification
   *
   * @param int $month
   * @param int $year
   * @param bool $sub
   * @return int
   */
  public function getTotalClassificationDRE($month, $year, $sub = false)
  {
    $accountingConfig = AccountingConfig::where('month', $month)
      ->where('year', $year)
      ->first();

    $formula = null;
    if ($accountingConfig) {
      $formula = $accountingConfig->formulas()
        ->where('accounting_classification_id', $this->id)
        ->whereNotIn('type_classification', ['RL', 'NSR'])
        ->first();
    }

    $dre = Dre::where("accounting_classification_id", $this->id)->where("month", $month)->where("year", $year)->latest('created_at')->first();

    if($dre) return $dre->value;

    if (!$formula && $sub) $formula = Formula::where('accounting_classification_id', $this->id)
    ->whereNotIn('type_classification', ['RL', 'NSR'])
    ->first();

    if ($formula) {
      $re = '/{(.*?)}/m';
      $formulaText = $this->checkCondicionalDRE($formula, $month, $year) ? $formula->formula : $formula->conditional_formula;
      preg_match_all($re, $formulaText, $matches, PREG_SET_ORDER, 0);
      $sum = 0;

      foreach ($matches as $value2) {
        $result = explode("&", $value2[1]);
        $workingDaysType = '';
        $sum = 0;

        if (count($result) >= 2) {
          $classification = self::where('classification', $result[0])->where('name', $result[1])->first();
          $workingDaysType = isset($result[2]) ? $result[2] : '';
        } else {
          $classification = self::where('classification', $result[0])->first();
        }

        if ($classification) {
          $accountingControl = AccountingControl::where('month', $month)
            ->where('year', $year)
            ->first();

          $dre = Dre::where("accounting_classification_id", $classification->id)->where("month", $month)->where("year", $year)->latest('created_at')->first();

          if ($accountingControl && !$dre) {
            if ($workingDaysType == '') {
              $accountingAnalytics = $accountingControl->accountingAnalytics()->where('accounting_classification_id', $classification->id)->first();
              if(!Cache::has("withdrawal-$month-$year-$classification->id")) Cache::put("withdrawal-$month-$year-$classification->id", Withdrawal::getTotalByMonthAndClassification($month, $year, $classification->id), 60);
              $withdrawal = Cache::get("withdrawal-$month-$year-$classification->id");

              if ($accountingAnalytics) {
                $sum += $accountingAnalytics->value;
              } else if ($withdrawal) {
                $sum += $withdrawal;
              } else {
                if(!Cache::has("total-classification-dre-$month-$year-$classification->id")) Cache::put("total-classification-dre-$month-$year-$classification->id", $classification->getTotalClassificationDRE($month, $year, true), 60);
                $sum = Cache::get("total-classification-dre-$month-$year-$classification->id");
              }

            } else {
              switch ($workingDaysType) {
                case 'CUSTOS INDIRETOS':
                  if(!Cache::has("custos-indiretos-$month-$year-$classification->id")) Cache::put("custos-indiretos-$month-$year-$classification->id",
                  TotalStaticCheckPoint::getTotal($year, $month, $classification->classification, TotalStaticCheckPoint::getTypes()[1], TotalStaticCheckPoint::getTypes()[0]), 60);
                  $sum = Cache::get("custos-indiretos-$month-$year-$classification->id");
                  break;

                case 'CUSTOS DIRETO':
                  if(!Cache::has("custos-direto-$month-$year-$classification->id")) Cache::put("custos-direto-$month-$year-$classification->id",
                  TotalStaticCheckPoint::getTotal($year, $month, $classification->classification, TotalStaticCheckPoint::getTypes()[0], TotalStaticCheckPoint::getTypes()[1]), 60);
                  $sum = Cache::get("custos-direto-$month-$year-$classification->id");
                  break;

                case 'TOTAL':
                  if(!Cache::has("total-01-$month-$year-$classification->id")) Cache::put("total-01-$month-$year-$classification->id",
                  TotalStaticCheckPoint::getTotal($year, $month, $classification->classification, TotalStaticCheckPoint::getTypes()[1], TotalStaticCheckPoint::getTypes()[0]), 60);
                  $result1 = Cache::get("total-01-$month-$year-$classification->id");

                  if(!Cache::has("total-02-$month-$year-$classification->id")) Cache::put("total-02-$month-$year-$classification->id",
                  TotalStaticCheckPoint::getTotal($year, $month, $classification->classification, TotalStaticCheckPoint::getTypes()[0], TotalStaticCheckPoint::getTypes()[1]), 60);
                  $result2 = Cache::get("total-02-$month-$year-$classification->id");

                  $sum = $result1 + $result2;
                  break;
              }
            }
          } else if($dre) {
            $sum = $dre->value;
          }

          if ($classification->unity == "%") $sum = $sum / 100;
        }
        $formulaText = Str::replace($value2[0], $sum, $formulaText);
      }

      $stringCalc = new StringCalc();
      if (Str::contains($formulaText, '0/0')) return 0;

      try {
        $result = $stringCalc->calculate($formulaText);
      } catch (\Throwable $th) {
        abort(500, "Erro ao calcular formula $formula->id: $formulaText");
      }


      return  $result;//$this->unity == 'R$' && !$sub  ? round($result, 0, PHP_ROUND_HALF_UP) : $result;
    }
    return 0;
  }


  public function getTotal($year, $months = [])
  {
    $sum = 0;
    foreach ($months as $month)
    {
        $sum += $this->getTotalClassificationDRE($month, $year);
    }

    return $sum;
  }

  /**
   * Get total by classification
   *
   * @param App\Modeles\Formula
   * @param int $month
   * @param int $year
   * @return bool
   */
  public function checkCondicionalDRE($formula, $month, $year)
  {
    if ($formula) {
      if (!$formula->conditional) return true;

      $re = '/{(.*?)}/m';
      $formulaText = $formula->conditional;
      preg_match_all($re, $formulaText, $matches, PREG_SET_ORDER, 0);
      $sum = 0;

      foreach ($matches as $value2) {
        $result = explode("&", $value2[1]);

        $classification = self::where('classification', $result[0])->first();

        if ($classification) {
          $sum = 0;
          $accountingControl = AccountingControl::where('month', $month)
            ->where('year', $year)
            ->first();

          if ($accountingControl) {
            $accountingAnalytics = $accountingControl->accountingAnalytics()->where('accounting_classification_id', $classification->id)->first();
            if ($accountingAnalytics) {
              $sum += $accountingAnalytics->value;
            } else {
              $sum = $classification->getTotalClassificationDRE($month, $year);
            }
          }
        }
        if ($classification->unity == "%") $sum = $sum / 100;
        $formulaText = Str::replace($value2[0], $sum, $formulaText);
      }
      $stringCalc = new StringCalc();
      if (Str::contains($formulaText, '0/0')) return 0;
      $result = $stringCalc->calculate($formulaText);

      return Formula::conditionalCalc($formula->conditional_type, $result, $formula->conditional_value);
    }
    return true;
  }

  /**
   * Get total by classification by month
   *
   * @param int $month
   * @param int $year
   * @return int
   */
  public static function getTotalClassificationByMonthDRE($month, $year)
  {
    $total = 0;
    $accountingClassifications = self::where('type_classification', 'DRE Ajustável')
      ->where('unity', 'R$')
      ->get();
    foreach ($accountingClassifications as $accountingClassification) {
      $total += $accountingClassification->getTotalClassificationDRE($month, $year);
    }

    return $total;
  }

  /**
   * Find users in dabase
   *
   * @param Array
   *
   * @return object
   */
  public static function filter($query)
  {
    $perPage = isset($query['paginate_per_page']) ? $query['paginate_per_page'] : DEFAULT_PAGINATE_PER_PAGE;
    $ascending = isset($query['ascending']) ? $query['ascending'] : DEFAULT_ASCENDING;
    $orderBy = isset($query['order_by']) ? $query['order_by'] : DEFAULT_ORDER_BY_COLUMN;

    $result = self::where(function ($q) use ($query) {
      if (isset($query['id'])) {
        if (!is_null($query['id'])) {
          $q->where('id', $query['id']);
        }
      }

      if (isset($query['name'])) {
        if (!is_null($query['name'])) {
          $q->where('name', 'like', '%' . $query['name'] . '%');
        }
      }

      if (isset($query['level'])) {
        if (!is_null($query['level'])) {
          $q->where('level', $query['level']);
        }
      }

      if (isset($query['classification'])) {
        if (!is_null($query['classification'])) {
          $q->where('classification', 'like', '%' . $query['classification'] . '%');
        }
      }

      if (isset($query['type_classification'])) {
        if (!is_null($query['type_classification'])) {
          $q->where('type_classification', $query['type_classification']);
        }
      }
    });

    $result->orderBy($orderBy, $ascending);

    return $result->paginate($perPage);
  }
}
