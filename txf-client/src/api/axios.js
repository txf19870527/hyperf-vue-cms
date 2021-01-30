import {
    post,
    downFile,
    postUpload,
    postLong
} from "./http";

let api = {
    login: (params) => post("public/login", params),
    loginOut: (params) => post("public/loginOut", params),
    index: (params) => post("index/index", params),
    staff: (params) => post("admin/lists", params),
    staffbatchDelete: (params) => post("admin/batchDelete", params),
    staffUpdate: (params) => post("admin/update", params),
    staffInsert: (params) => post("admin/insert", params),
    saveStaffRoles: (params) => post("admin/saveRoles", params),
    updatePassword: (params) => post("admin/updatePassword", params),
    role: (params) => post("role/lists", params),
    rolebatchDelete: (params) => post("role/batchDelete", params),
    roleUpdate: (params) => post("role/update", params),
    roleInsert: (params) => post("role/insert", params),
    rolesWithAdmin: (params) => post("role/getRolesWithAdmin", params),
    saveRolePermissions: (params) => post("role/savePermissions", params),
    permission: (params) => post("permission/lists", params),
    permissionbatchDelete: (params) => post("permission/batchDelete", params),
    permissionUpdate: (params) => post("permission/update", params),
    permissionInsert: (params) => post("permission/insert", params),
    permissionCascader: (params) => post("permission/getPermissionDropDown", params),
    permissionWithRole: (params) => post("permission/getPermissionsWithRole", params),
    downloadExample: (params) => downFile("public/downloadExample", params),
    uploadExample: (params) => postUpload("public/uploadExample", params),
};


export default api;