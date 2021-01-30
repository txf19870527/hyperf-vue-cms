import Vue from 'vue';
import Router from 'vue-router';

// 解决两次访问相同路由地址报错
const originalPush = Router.prototype.push
Router.prototype.push = function push(location) {
    return originalPush.call(this, location).catch(err => err)
}

Vue.use(Router);

let routeData = {
    routes: [{
            path: '/',
            redirect: '/dashboard'
        },
        {
            path: '/',
            component: () => import('../components/common/Home.vue'),
            children: [{
                    path: '/404',
                    component: () => import('../components/page/404.vue'),
                    meta: {
                        title: '404'
                    }
                },
                {
                    path: '/dashboard',
                    component: () => import('../components/page/Dashboard.vue'),
                    meta: {
                        title: '系统首页'
                    }
                },
                {
                    path: '/staff',
                    component: () => import('../components/page/Staff.vue'),
                    meta: {
                        title: '员工管理'
                    }
                },
                {
                    path: '/role',
                    component: () => import('../components/page/Role.vue'),
                    meta: {
                        title: '角色管理'
                    }
                },
                {
                    path: '/permission',
                    component: () => import('../components/page/Permission.vue'),
                    meta: {
                        title: '权限管理'
                    }
                },
            ]
        },
        {
            path: '/login',
            component: () => import('../components/page/Login.vue'),
            meta: {
                title: '登录'
            }
        },
        {
            path: '*',
            redirect: '/404'
        }
    ]
};

// 将后台配置的权限信息同步到路由中
let routes = localStorage.getItem("cms_routes");

if (routes) {
    routes = JSON.parse(routes);

    routeData['routes'][1]['children'].forEach(row => {
        let key = row.path.replace('/', '');
        if (routes[key]) {
            row.meta = routes[key];
        }
    });
}

export default new Router(routeData);