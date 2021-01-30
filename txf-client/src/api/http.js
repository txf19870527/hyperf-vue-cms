import axios from "axios";
import router from ".././router";
import {
  Message
} from "element-ui";

axios.defaults.baseURL = "http://127.0.0.1:9505/admin";


axios.defaults.timeout = 20000; //设置请求超时
axios.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded;charset=UTF-8"; //设置post请求头


// 请求拦截
axios.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem("cms_token");
    if (token) {

      if (Object.prototype.toString.call(config.data) == '[object FormData]') {
        config.data.append("token", token);
      } else {
        let data = JSON.parse(config.data)
        config.data = JSON.stringify({
          token: token,
          ...data
        })
      }
    }
    return config;
  },
  (error) => {
    return Promise.error(error);
  }
);

// 响应拦截器
axios.interceptors.response.use(
  (response) => {
    if (response.status === 200) {

      // 返回json有code，返回文件没有code
      if (response.data.code) {

        // 非0表示存在错误
        if (response.data.code != 0) {
          // 登录特殊处理
          if (response.data.code == '1015') {
            Message.error(response.data.message);
            setTimeout(() => {
              router.replace({
                path: "/login",
                query: {
                  redirect: router.currentRoute.fullPath,
                },
              });
            }, 1000);
          } else {  // 其它同意返回接口错误
            Message.error(response.data.message);
          }
        }

      }

      return Promise.resolve(response);

    } else {
      return Promise.reject(response);
    }
  },
  // 根据返回的状态码进行相关操作，例如登录过期提示，错误提示等等
  (error) => {

    return Promise.reject(error);
  }
);

/**
 * post方法，对应post请求
 * @param {String} url [请求地址]
 * @param {Object} params [请求时携带的参数]
 */
export function post(url, params) {

  return new Promise((resolve, reject) => {
    axios
      .post(url, JSON.stringify(params))
      .then((res) => {
        resolve(res.data);
      })
      .catch((err) => {
        reject(err.data);
      });
  });
}

/**
 * post方法，对应post请求
 * @param {String} url [请求地址]
 * @param {Object} params [请求时携带的参数]
 */
export function postLong(url, params) {
  return new Promise((resolve, reject) => {
    axios
      .post(url, JSON.stringify(params), {
        timeout: 120000
      })
      .then((res) => {
        resolve(res.data);
      })
      .catch((err) => {
        reject(err.data);
      });
  });
}

//文件上传（超时时间2分钟）
export function postUpload(url, params) {

  return new Promise((resolve, reject) => {
    axios
      .post(url, params, {
        timeout: 120000
      })
      .then((res) => {
        resolve(res.data);
      })
      .catch((err) => {
        reject(err.data);
      });
  });
}


/**
 * 表格下载downFile方法，对应get请求
 * @param {String} url [请求地址]
 * @param {Object} params [请求时携带的参数]
 */
export function downFile(url, params) {
  return new Promise((resolve, reject) => {
    axios
      .post(url, JSON.stringify(params), {
        timeout: 120000,
        responseType: "blob"
      })
      .then((res) => {
        // 后台返回错误
        if (res.data.type == 'application/json') {
          Message.error("没有数据可供下载");
          resolve(res.data);
        } else {
          // 有文件下载
          resolve(res.data);
          let downloadA = document.createElement('a');
          let blob = new Blob([res.data], {
            type: res.headers['content-type']
          });
          downloadA.href = URL.createObjectURL(blob);
          if (res.headers['content-disposition']) {
            let cd = res.headers['content-disposition'];
            if (cd.lastIndexOf("filename=")) {
              let fileName = cd.substr(cd.lastIndexOf("filename=") + 9);
              if (fileName) {
                downloadA.download = fileName;
              }
            }
          }
          downloadA.click();
          URL.revokeObjectURL(downloadA.href);
        }
      })
      .catch((err) => {
        reject(err.data);
      });
  });
}