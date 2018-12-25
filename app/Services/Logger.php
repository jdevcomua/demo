<?php

namespace App\Services;

use App\Models\Log;

class Logger
{

    private $logModel;

    private static $idsObjectTypesMap = [
        'organization_id' => 'Організація',
        'animal_id' => 'Тварина'
    ];

    /**
     * @var array
     * key is id to replace with data to display
     * key => array(name_of_model, name_of_column_from_the_model)
     */
    private static $idsModelsToDisplay = [
        'veterinary_measure_id' => [
            'name' => 'Ветеринарний захід',
            'model' => 'VeterinaryMeasure',
            'column_name' => 'name'
        ],
    ];

    public function __construct(Log $logModel)
    {
        $this->logModel = $logModel;
    }

    public function start(array $payload = null)
    {
        $this->logModel->fill([
            'finished' => false
        ])->save();
        if ($payload) $this->addPayload($payload);
    }

    public function end($ok = false)
    {
        $this->update([
            'finished' => true
        ]);
        if ($ok) {
            $this->update(['status' => Log::STATUS_OK]);
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function object($model)
    {
        $this->update([
            'object_id' => $model->id,
            'object_type' => $model->getMorphClass(),
        ]);
    }

    public function update($data)
    {
        $this->logModel->update($data);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $newModel
     * @param \Illuminate\Database\Eloquent\Model $oldModel
     * @param bool $end
     * @return string
     */
    public function addChanges($newModel, $oldModel, $end = false, $ok = false)
    {
        if (count($oldModel->getAttributes()) === 0) {
            $changes = $newModel->getAttributes();
            $attr = array_fill_keys(array_keys($changes), null);
        } else {
            $attr = $oldModel->getAttributes();
            $oldModel->fill($newModel->getAttributes());
            $changes = $oldModel->getDirty();
        }

        foreach ($attr as $k => $v) {
            if (array_key_exists($k, $changes) && $changes[$k] != $v) {
                $attr[$k] = [
                    'old' => $v,
                    'new' => (is_bool($changes[$k])) ? intval($changes[$k]) : $changes[$k],
                ];
            } else {
                unset($attr[$k]);
            }
        }

        $this->update([
            'changes' => json_encode($attr)
        ]);

        if ($end) $this->end($ok);
    }

    public static function renderChanges($data)
    {
        if ($data) {
            $data = json_decode($data, true);

        } else return '';
        if (is_array($data)) {
            $res = '';
            foreach ($data as $k => $v) {

                $label = self::$idsModelsToDisplay[$k]['name'] ??  self::$idsObjectTypesMap[$k] ?? self::$idsObjectTypesMap[$k] ?? $k;
                $res .= '<span>';
                $res .= '<b>' . $label . '</b>: ';

                if (!empty(self::$idsObjectTypesMap[$k])) {
                    $v['old'] = $v['old'] !== null ? "<a href=\"" . route('admin.object') .
                       "/" . self::$idsObjectTypesMap[$k] . "/" . $v['old'] . "\">" . self::$idsObjectTypesMap[$k] . " #" . $v['old'] . "</a>" : null;
                    $v['new'] = $v['new'] !== null ? "<a href=\"" . route('admin.object') .
                        "/" . self::$idsObjectTypesMap[$k] . "/" . $v['new'] . "\">" . self::$idsObjectTypesMap[$k] . " #" . $v['new'] . "</a>" : null;
                }
                if (!empty(self::$idsModelsToDisplay[$k])) {
                    $model = self::$idsModelsToDisplay[$k]['model'];
                    $columnName = self::$idsModelsToDisplay[$k]['column_name'];

                    $v['old'] = ($v['old'] !== null) ? static::getValueByModelName($model, $v['old'], $columnName) : null;
                    $v['new'] = ($v['new'] !== null) ? static::getValueByModelName($model, $v['new'], $columnName) : null;
                }
                if ($v['old'] !== null) $res .= '<c-r>' . $v['old'] . '</c-r> ⟶ ';
                $res .= '<c-g>' . $v['new'] . '</c-g>';
                $res .= '</span>';
            }
            return $res;
        }
        return '';
    }

    private static function getValueByModelName($modelName, $id, $columnName)
    {
        $modelsPath = ($modelName !== 'User') ? '\App\Models\\' : '\App\\';
        $modelClass = $modelsPath . $modelName;
        return $modelClass::findOrFail($id)->$columnName;
    }

    /**
     * @param \Throwable $e
     */
    public function addError(\Throwable $e)
    {
        $this->addPayload(['exception' => [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTrace(),
        ]]);
    }


    public function addPayload(array $add)
    {
        $curr = json_decode($this->logModel->payload, true);
        $curr = (is_array($curr)) ? array_merge($curr, $add) : $add;
        $this->update([
            'payload' => json_encode($curr),
        ]);
    }

}