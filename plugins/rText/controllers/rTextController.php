<?php

namespace plugins\rText\controllers;

use app\router\responses\JsonResponse;
use plugins\rText\models\rTextModel;

class rTextController
{
    public static function update()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['blockName'])) return new JsonResponse(['success' => false, 'error' => 'blockName is not find'], 400);;
        if (!isset($input['value'])) return new JsonResponse(['success' => false, 'error' => 'value is not find'], 400);;
        $model = new rTextModel($input['blockName']);
        if ($model->setText($input['value'])) return new JsonResponse([['value'] => $model->getText()]);
        else return new JsonResponse(['success' => false], 400);
    }
    
}
