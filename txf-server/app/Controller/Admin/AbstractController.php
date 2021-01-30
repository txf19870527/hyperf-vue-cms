<?php


namespace App\Controller\Admin;


use App\Com\RequestHandle;
use App\Com\ResponseCode;
use App\Exception\BusinessException;
use App\Services\AbstractServiceInterface;

class AbstractController extends \App\Controller\AbstractController
{
    /**
     * 子类中打开 $useDefault 开关 并且注入 $service 就能使用通用 增删改查的 服务
     * @var bool
     */
    protected $useDefault = false;

    /**
     * @var AbstractServiceInterface
     * 这个接口目前只是看看的，没有实现
     */
    protected $service;

    /**
     * 列表通用方法
     */
    public function lists()
    {
        if (!$this->useDefault && empty($this->service)) {
            throw new BusinessException(ResponseCode::API_NOT_EXISTS);
        }

        $body = $this->request->getAttribute('body');

        $response = $this->service->lists($body);

        return $this->processExportResponse($body, $response);
    }


    /**
     * 删除/批量通用方法
     */
    public function batchDelete()
    {
        if (!$this->useDefault && empty($this->service)) {
            throw new BusinessException(ResponseCode::API_NOT_EXISTS);
        }

        $body = $this->request->getAttribute('body');
        return $this->service->batchDelete($body['ids']);
    }

    /**
     * 删除
     */
    public function delete()
    {
        if (!$this->useDefault && empty($this->service)) {
            throw new BusinessException(ResponseCode::API_NOT_EXISTS);
        }

        $body = $this->request->getAttribute('body');
        return $this->service->delete((int)$body['id']);
    }


    /**
     * 更新通用方法
     */
    public function update()
    {
        if (!$this->useDefault && empty($this->service)) {
            throw new BusinessException(ResponseCode::API_NOT_EXISTS);
        }

        $body = $this->request->getAttribute('body');
        return $this->service->update($body);
    }

    /**
     * 新增通用方法
     */
    public function insert()
    {
        if (!$this->useDefault && empty($this->service)) {
            throw new BusinessException(ResponseCode::API_NOT_EXISTS);
        }

        $body = $this->request->getAttribute('body');

        return $this->service->insert($body);
    }




    public function processExportResponse($params, $response)
    {
        if (!empty($params['is_export'])) {
            if (empty($response['file_name']) || !is_string($response['file_name'])) {
                throw new BusinessException(ResponseCode::EXPORT_CSV_ERROR);
            }

            return $this->response->download($response['file_name'], $response['download_name'] ?? "download")->withHeader('access-control-expose-headers', 'content-disposition');

        } else {
            return $response;
        }
    }

    public function getImportRequest($request = [])
    {
        if (empty($request)) {
            $request['body'] = $this->request->getAttribute("upload_body");

            if (empty($request['body']['save_name'])) {
                throw new BusinessException(ResponseCode::UPLOAD_ERROR);
            }
        }

        $uri = "/upload" . $this->request->getUri()->getPath();

        $request['filter'] = RequestHandle::getFilterConfig($uri);

        $request['validation'] = RequestHandle::getValidationConfig($uri);

        return $request;
    }

    public function processImportData($data, $request, $breaks = [])
    {

        $breaks = (array)$breaks;

        $data = RequestHandle::filter($data, $request['filter']);
        $data = array_trim($data);

        if (empty($data)) {
            throw new BusinessException(ResponseCode::IMPORT_ERROR);
        }

        foreach ($breaks as $break) {
            if (empty($data[$break])) {
                return null;
            }
        }

        RequestHandle::validation($data, $request['validation']['validation'], $request['validation']['message']);

        return $data;
    }
}