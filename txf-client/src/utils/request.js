import axios from 'axios';
import config from '../config';

const service = axios.create({
    baseURL: config("baseURL"),
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
});

service.interceptors.request.use(
    config => {
        if(config.url != 'login' && localStorage.getItem("cms_token") != 'undefined') {
            config.data.token = localStorage.getItem("cms_token");
        }
        return config;
    },
    error => {
        return Promise.reject(error);
    }
);

service.interceptors.response.use(
    response => {
        
        if(response.headers['content-type'].indexOf("application/json") == -1) {
            return response;
        }
        
        if (response.status === 200 && response.data.code == 0) {
            return response.data;
        } else {
            return Promise.reject( {"code":response.data.code,"message":response.data.message} );
        }
    },
    error => {
        return Promise.reject({"code":-1,"message":"请求失败"});
    }
);

export default service;
