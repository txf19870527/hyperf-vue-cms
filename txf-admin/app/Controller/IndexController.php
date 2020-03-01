<?php

declare(strict_types=1);

namespace App\Controller;


use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Utils\Parallel;

/**
 * 后台首页相关接口
 * Class IndexController
 * @package App\Controller
 * @AutoController()
 */
class IndexController extends AbstractController
{

    /**
     * 首页假数据模拟
     */
    public function index()
    {

        // 并发模拟
        $parallel = new Parallel();

        $parallel->add(function (){
            return $this->toDoListMock();
        }, 'todo_list');

        $parallel->add(function (){
            return $this->weekSoldMock();
        }, 'week_sold');

        $parallel->add(function (){
            return $this->monthSoldMock();
        }, 'month_sold');


        return $parallel->wait();

    }

    protected function toDoListMock()
    {
        return [
            ['title' => '今天完成员工管理', 'status' => true],
            ['title' => '今天完成角色管理', 'status' => true],
            ['title' => '今天完成权限管理', 'status' => true],
            ['title' => '今天完成员工、角色绑定', 'status' => true],
            ['title' => '今天完成角色、权限绑定', 'status' => true],
            ['title' => '发布1.0版本', 'status' => true],
            ['title' => '发布2.0版本', 'status' => false],
        ];
    }

    protected function weekSoldMock()
    {
        return [
            'type' => 'bar',
            'title' => ['text' => '最近一周各品类销售图'],
            'xRorate' => 25,
            'labels' => ['周一', '周二', '周三', '周四', '周五'],
            'datasets' => [
                ['label' => '家电', 'data' => [234, 278, 270, 190, 230]],
                ['label' => '百货', 'data' => [164, 178, 190, 135, 160]],
                ['label' => '食品', 'data' => [144, 198, 150, 235, 120]],
            ]
        ];
    }

    protected function monthSoldMock()
    {
        return [
            'type' => 'line',
            'title' => ['text' => '最近几个月各品类销售趋势图'],
            'labels' => ['6月', '7月', '8月', '9月', '10月'],
            'datasets' => [
                ['label' => '家电', 'data' => [234, 278, 270, 190, 230]],
                ['label' => '百货', 'data' => [164, 178, 150, 135, 160]],
                ['label' => '食品', 'data' => [74, 118, 200, 235, 90]],
            ]
        ];
    }
}
