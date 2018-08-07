<?php

namespace App\Services;

use App\Models\Log;

class Logger
{

    private $logModel;

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

    public function end()
    {
        $this->update([
            'finished' => true
        ]);
    }

    public function update($data)
    {
        $this->logModel->update($data);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $newModel
     * @param \Illuminate\Database\Eloquent\Model $oldModel
     * @return string
     */
    public function addChanges($newModel, $oldModel)
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
            if (array_key_exists($k, $changes)) {
                $attr[$k] = [
                    'old' => $v,
                    'new' => $changes[$k]
                ];
            } else {
                unset($attr[$k]);
            }
        }

        $this->update([
            'changes' => $this->renderChanges($attr)
        ]);
    }

    private function renderChanges($data)
    {
        $res = '';
        foreach ($data as $k => $v) {
            $res .= '<span>';
            $res .= '<b>'.$k.'</b>: ';
            if ($v['old']) $res .= '<c-r>'.$v['old'].'</c-r> ⟶ ';
            $res .= '<c-g>'.$v['new'].'</c-g>';
            $res .= '</span>';
        }
        return $res;
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