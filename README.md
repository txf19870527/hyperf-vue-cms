# hyperf-vue-cms

# 介绍
基于 `hyperf` + `vue-manage-system` 开发的后台管理系统。

[预览地址](https://admin.fengfengphp.com) 账号: 13000000000 密码: 111111

测试账号只有浏览权限，不会因为登录失败次数过多而禁止登录

[hyperf](https://github.com/hyperf/hyperf) 基于 `swoole` 的php框架。

[vue-manage-system](https://github.com/lin-xin/vue-manage-system) 基于 `vue` + `element-ui` 的后台模板。

项目目前只包含一些基础功能 `登录`、`员工管理`、`角色管理`、`权限管理`、`导入`、`导出`等。以及一些常用的中间件 `日志`、`跨域`、`限频`、`权限认证`、`参数过滤`、`参数验证`、`上传处理`等。

你可以基于此直接开发你想要的功能，如： `内容管理`、`商品管理`、`订单管理`等。

项目分为 `txf-client`、`txf-admin`、`txf-server` 三块。

`txf-client` 客户端、前端。

`txf-admin` API网关、`hyperf jsonrpc-client`。

`txf-server` 服务端、`hyperf jsonrpc-server`。

# 安装
```
# npm使用淘宝镜像
npm install -g cnpm --registry=https://registry.npm.taobao.org

# composer使用阿里云镜像
composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/

# 安装客户端
cd txf-client
cnpm install

# 安装jsonrpc-client
cd txf-admin
composer install

# 安装jsonrpc-server
cd txf-server
composer install

# 初始化数据库
根据 .env文件配置 将 txf-server/init_sql.sql 导入到数据库
```

# 运行
```
# 启动客户端，默认监听 127.0.0.1:8080
cd txf-client
npm run serve

# 启动jsonrpc-client，默认监听 127.0.0.1:9504
cd txf-admin
php bin/hyperf.php start

# 启动jsonrpc-server，默认监听 127.0.0.1:9505
cd txf-server
php bin/hyperf.php start
```

# 使用
```
# 浏览器打开 http://127.0.0.1:8080

# 默认账号
13888888888

# 默认密码
123456

```

# 文档
[文档地址](http://wiki.fengfengphp.com)

# License
[MIT](https://github.com/txf19870527/hyperf-vue-cms/blob/master/LICENSE)