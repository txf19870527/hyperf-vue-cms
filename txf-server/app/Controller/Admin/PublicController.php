<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Com\Csv;
use App\Services\AdminService;
use Hyperf\Di\Annotation\Inject;

/**
 * Class PublicController
 * @package App\Controller
 */
class PublicController extends AbstractController
{

    /**
     * @Inject()
     * @var AdminService
     */
    protected $adminService;

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
     * 退出登录
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

    public function downloadExample()
    {
        $head = ["ID", "名字"];
        $data = [
            [
                'id' => 1,
                'name' => '张三'
            ],
            [
                'id' => 2,
                'name' => '李四'
            ],
        ];

        $fileName = Csv::dataToCsv($head, $data);
        return $this->response->download($fileName, 'test.csv')->withHeader('access-control-expose-headers', 'content-disposition');
    }

    public function uploadExample()
    {
        $request = $this->getImportRequest();

        Csv::csvToDataBatch($request['body']['save_name'], function ($data) use ($request) {

            p($data);
//            foreach ($data as $v) {
//
//                $v = $this->processImportData($v, $request);
//
//                if ($v === null) {
//                    break;
//                }
//
//            }

            return true;

        });


        return true;
    }

    public function test()
    {
        return $this->request->getUri()->getPath();
    }

}
