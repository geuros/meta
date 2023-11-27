<?php
namespace App\Services;

use App\Enums\FileUploadEnum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Mockery\Exception;
use Pion\Laravel\ChunkUpload\Save\AbstractSave;

class FileUploadService
{
    private UploadedFile $file;

    public function __construct(AbstractSave $file) {
        $this->file = $file->getFile();
    }

    public function upload(): bool {
        $fileName = $this->getName();
        $isUploaded = $this->store($fileName);
        unlink($this->file->getPathname());

        return $isUploaded ? FileUploadEnum::STATUS_COMPLETED : FileUploadEnum::STATUS_ERROR;
    }

    private function store(string $fileName): bool {
        try {
            $disk = Storage::disk(config('filesystems.default'));
            $disk->putFileAs('file', $this->file, $fileName);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return false;
        }

        return true;
    }

    private function getName(): string {
        $extension = $this->file->getClientOriginalExtension();
        $fileName = File::extension($this->file->getClientOriginalName());

        return sprintf('%s_%s.%s', $fileName, uniqid(), $extension);
    }
}
