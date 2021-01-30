<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Com\File;
use App\Com\Log;
use App\Com\ResponseCode;
use App\Exception\BusinessException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UploadMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $request = $this->doUpload($request);

        return $handler->handle($request);
    }

    public function doUpload(ServerRequestInterface $request):ServerRequestInterface
    {
        $uri = $request->getUri()->getPath();

        if (in_array_UpLow($uri, config('upload.import_uri'))) {
            $request = $this->checkAndDone($request, 'import');
        } elseif(in_array_UpLow($uri, config('upload.upload_uri'))) {
            $request = $this->checkAndDone($request, 'upload');
        }

        return $request;
    }

    protected function checkAndDone(ServerRequestInterface $request, $type): ServerRequestInterface
    {

        $uri = strtolower($request->getUri()->getPath());

        /**
         * @var UploadedFileInterface
         */
        $uploadFile = $request->getUploadedFiles() ?? [];

        $uploadFile = current($uploadFile);

        if (empty($uploadFile) || !$uploadFile instanceof UploadedFileInterface) {

            $notRequireUri = config("upload.{$type}_not_require_uri");
            if (in_array_UpLow($uri, $notRequireUri)) {
                return $request;
            }

            throw new BusinessException(ResponseCode::UPLOAD_NOT_EXISTS);
        }

        // 默认配置
        $maxSize = config("upload.{$type}_max_size");
        $allowSuffix = config("upload.{$type}_allow_suffix");
        $allowType = config("upload.{$type}_allow_type");

        // 指定URI配置
        $userSitting = config("upload.{$type}_user_setting.{$uri}");
        if (!empty($userSitting)) {
            $maxSize = $userSitting["{$type}_max_size"] ?? $maxSize;
            $allowSuffix = $userSitting["{$type}_allow_suffix"] ?? $allowSuffix;
            $allowType = $userSitting["{$type}_allow_type"] ?? $allowType;
        }

        if ($uploadFile->getSize() > $maxSize) {
            throw new BusinessException(ResponseCode::UPLOAD_SIZE_ERROR);
        }

        $fileName = $uploadFile->getClientFilename();

        $index = strrpos($fileName, '.');
        if ($index < 1) {
            throw new BusinessException(ResponseCode::UPLOAD_TYPE_ERROR);
        }

        $suffix = substr($fileName, $index + 1);

        if (!in_array_UpLow($suffix, $allowSuffix)) {
            throw new BusinessException(ResponseCode::UPLOAD_TYPE_ERROR);
        }

        if (!in_array_UpLow($uploadFile->getClientMediaType(), $allowType)) {
            throw new BusinessException(ResponseCode::UPLOAD_TYPE_ERROR);
        }

        if ($type == 'import') {
            $saveName = File::buildTempFileName('', $suffix);
            $relativeName = str_replace(TEMP_PATH, '', $saveName);
        } else {
            $saveName = File::buildFileName('', $suffix);
            $relativeName = str_replace(UPLOAD_PATH, '', $saveName);
        }

        $uploadFile->moveTo($saveName);

        chmod($saveName, 0644);

        $fileName = pathinfo($fileName);
        $fileName = $fileName['basename'];

        $body = [
            'size' => $uploadFile->getSize(),
            'file_name' => $fileName,
            'save_name' => $saveName,
            'relative_name' => $relativeName,
            'media_type' => $uploadFile->getClientMediaType()
        ];

        // 上传文件的信息追加到日志中
        Log::append('upload_info', $body);

        // 追加上传信息
        $request = $request->withAttribute("upload_body", $body);

        return $request;

    }
}