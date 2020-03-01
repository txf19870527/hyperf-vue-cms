<?php

declare(strict_types=1);

namespace App\Controller;

use App\Com\Excel;
use App\Com\File;
use App\Service\Interfaces\AdminServiceInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Redis\RedisFactory;

/**
 * Class PublicController
 * @package App\Controller
 * @AutoController()
 */
class PublicController extends AbstractController
{

    /**
     * @Inject()
     * @var AdminServiceInterface
     */
    protected $adminService;

    /**
     * @Inject()
     * @var RedisFactory
     */
    protected $redisFactory;


    /**
     * 登录
     * @return array
     */
    public function login()
    {

        $body = $this->request->getAttribute('body');

        return $this->adminService->login($body['mobile'], $body['password']);

    }

    /**
     * 退出登录（不请求server了，直接清理掉redis）
     * @return bool
     */
    public function loginOut()
    {
        $userData = $this->request->getAttribute("user_data");

        if (empty($userData['id'])) {
            return true;
        }

        $this->adminService->loginOut($userData['id']);


        return true;

    }

    /**
     * 下载示例
     */
    public function downloadExample()
    {

        $head = [
            "姓名","年龄","手机","时间"
        ];

        $data = [
            ['张三',13,1383838438,'2020-02-02 02:02:02'],
            ['李四',20,"1383838438",'1999-02-02 02:02:02'],
        ];

        $fullFile = Excel::dataToExcel($head,$data);

        // 追加内容
        Excel::append($fullFile, [['王五',25,"1353838438",'2001-02-02 02:02:02']]);

        // 不要立即清理，会影响下载
        File::afterClear($fullFile,2000);

        return $this->response->download($fullFile);

    }

    /**
     * 上传示例
     */
    public function uploadExample()
    {

        $body = $this->request->getAttribute('body');

        $body['row'] = Excel::getExcelRowCount($body['save_name']);

        $data = Excel::excelToData($body['save_name']);

        // 立即清理
        File::afterClear($body['save_name'],0);

        $body['count'] = count($data);

        return $body;

    }
}
