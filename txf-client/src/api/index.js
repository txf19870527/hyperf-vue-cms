import request from '../utils/request';
import config from '../config';

const requestUrl = {
    "login" : "public/login",
    "loginOut" : "public/loginOut",
    "index": "index/index",
    "staff" : "admin/lists",
    "staffbatchDelete" : "admin/batchDelete",
    "staffUpdate": "admin/update",
    "staffInsert": "admin/insert",
    "saveStaffRoles" : "admin/saveRoles",
    "updatePassword": "admin/updatePassword",
    "role" : "role/lists",
    "rolebatchDelete" : "role/batchDelete",
    "roleUpdate" : "role/update",
    "roleInsert" : "role/insert",
    "rolesWithAdmin":"role/getRolesWithAdmin",
    "saveRolePermissions" : "role/savePermissions",
    "permission" : "permission/lists",
    "permissionbatchDelete" : "permission/batchDelete",
    "permissionUpdate" : "permission/update",
    "permissionInsert" : "permission/insert",
    "permissionCascader" : "permission/getPermissionCascader",
    "permissionWithRole" : "permission/getPermissionsWithRole",
    "downloadExample" : "public/downloadExample",
    "uploadExample": "public/uploadExample",
};

export const requestApi = (url, data) => {
    return request({
        url: requestUrl[url],
        method: 'post',
        data: data,
        timeout: 9000
    });
}

export function requestDownLoad(url, data) {
    request({
        url: requestUrl[url],
        method: 'post',
        data: data,
        responseType: 'arraybuffer',
    }).then(res => {
        let downloadA = document.createElement('a');
        let blob = new Blob([res.data], {type: res.headers['content-type']});
        downloadA.href = URL.createObjectURL(blob);
        
        if(res.headers['content-disposition']) {
            let cd = res.headers['content-disposition'];
            if (cd.lastIndexOf("filename=")) {
                let fileName = cd.substr(cd.lastIndexOf("filename=") + 9);
                if ( fileName ) {
                    downloadA.download = fileName;
                }
            }
        }

        downloadA.click();
        URL.revokeObjectURL(downloadA.href);
    }).catch(error => {
        alert("下载失败");
    });
}

export function buildUrl(url) {
    return config("baseURL") + requestUrl[url];
}
