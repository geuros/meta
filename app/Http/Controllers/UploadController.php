<?php

namespace App\Http\Controllers;

use App\Enums\FileUploadEnum;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class UploadController  extends Controller {
    /**
     * @throws UploadFailedException
     */
    public function upload(Request $request): array {
        try {
            $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
            $file = $receiver->receive();
            if ($file->isFinished()) {
                $fileUploadService = new FileUploadService($file);

                return [
                    'status' => $fileUploadService->upload()
                ];
            }
            $handler = $file->handler();

            return [
                'done' => $handler->getPercentageDone(),
                'status' => FileUploadEnum::STATUS_IN_PROCESS
            ];
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return [
            'status' => FileUploadEnum::STATUS_ERROR
        ];
    }
}
