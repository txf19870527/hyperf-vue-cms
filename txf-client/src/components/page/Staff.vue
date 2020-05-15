<template>
    <div>
        <div class="crumbs">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>
                    <i :class="icon"></i> {{title}}
                </el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="container">
            <div class="handle-box">
                <el-input v-model="query.name" placeholder="用户名" class="handle-input mr10"></el-input>
                <el-input v-model="query.mobile" placeholder="手机" class="handle-input mr10"></el-input>
                <el-select v-model="query.status" placeholder="状态" class="handle-select mr10">
                    <el-option key="-1" label="全部" value="-1"></el-option>
                    <el-option key="1" label="启用" value="1"></el-option>
                    <el-option key="0" label="禁用" value="0"></el-option>
                </el-select>
                <el-button type="primary" icon="el-icon-search" @click="handleSearch">搜索</el-button>
            </div>

            <div class="handle-box">
                <el-button type="primary" icon="el-icon-lx-delete" class="handle-del" @click="delAllSelection">批量删除</el-button>
                <el-button type="primary" icon="el-icon-lx-add" class="handle-add mr10" @click="handleAdd">新增</el-button>
            </div>

            <el-table :data="tableData" border class="table" ref="multipleTable" header-cell-class-name="table-header"
                @selection-change="handleSelectionChange">
                <el-table-column type="selection" width="55" align="center"></el-table-column>
                <el-table-column prop="id" label="ID" width="55" align="center"></el-table-column>
                <el-table-column prop="name" label="用户名" align="center"></el-table-column>
                <el-table-column prop="mobile" label="手机号" align="center"></el-table-column>
                <el-table-column label="状态" align="center">
                    <template slot-scope="scope">
                        <el-tag :type="scope.row.status == 1 ? 'success' : 'danger'">{{scope.row.status == 1 ? "启用" : "禁用"}}</el-tag>
                    </template>
                </el-table-column>
                <el-table-column prop="login_error_times" label="登录失败次数" align="center"></el-table-column>
                <el-table-column prop="last_login_time" label="最后登录时间"></el-table-column>
                <el-table-column prop="created_at" label="创建时间"></el-table-column>
                <el-table-column label="操作" width="220" align="center">
                    <template slot-scope="scope">
                        <el-button type="text" icon="el-icon-edit" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
                        <el-button type="text" icon="el-icon-delete" class="red" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
                        <el-button type="text" icon="el-icon-lx-settings" @click="handleSetting(scope.$index, scope.row.id)">绑定角色</el-button>
                    </template>
                </el-table-column>
            </el-table>

            <div class="pagination">
                <el-pagination background layout="total, prev, pager, next" :current-page="query.page" :page-size="query.size"
                    :total="total" @current-change="handlePageChange"></el-pagination>
            </div>
        </div>

        <!-- 新增弹出框 -->
        <el-dialog v-dialogDrag title="新增" :visible.sync="addVisible" width="30%" :close-on-click-modal="false">
            <el-form ref="addForm" :model="addForm" label-width="70px">
                <el-form-item label="用户名">
                    <el-input v-model="addForm.name"></el-input>
                </el-form-item>
                <el-form-item label="手机号">
                    <el-input v-model="addForm.mobile"></el-input>
                </el-form-item>
                <el-form-item label="密码">
                    <el-input v-model="addForm.password" show-password></el-input>
                </el-form-item>
                <el-form-item label="状态">
                    <el-radio-group v-model="addForm.status">
                        <el-radio :label="1">启用</el-radio>
                        <el-radio :label="0">禁用</el-radio>
                    </el-radio-group>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="addVisible = false">取 消</el-button>
                <el-button type="primary" @click="saveAdd">确 定</el-button>
            </span>
        </el-dialog>

        <!-- 编辑弹出框 -->
        <el-dialog v-dialogDrag title="编辑" :visible.sync="editVisible" width="30%" :close-on-click-modal="false">
            <el-form ref="form" :model="form" label-width="100px">
                <el-form-item label="用户名">
                    <el-input v-model="form.name"></el-input>
                </el-form-item>
                <el-form-item label="手机号">
                    <el-input v-model="form.mobile"></el-input>
                </el-form-item>
                <el-form-item label="状态">
                    <el-radio-group v-model="form.status">
                        <el-radio :label="1">启用</el-radio>
                        <el-radio :label="0">禁用</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="登录失败次数">
                    <el-input v-model="form.login_error_times"></el-input>
                    <span class="red">连续登录失败会被禁止登录，可以通过设置该参数解除登录限制</span>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="editVisible = false">取 消</el-button>
                <el-button type="primary" @click="saveEdit">确 定</el-button>
            </span>
        </el-dialog>

        <!-- 绑定角色弹出层 -->
        <el-dialog v-dialogDrag title="绑定角色" :visible.sync="roleVisible" width="30%" :close-on-click-modal="false">

            <el-table :data="roleForm" border class="table" ref="roleForm" header-cell-class-name="table-header"
                @selection-change="handleRoleSelectionChange">
                <el-table-column type="selection" width="55" align="center"></el-table-column>
                <el-table-column prop="role_name" label="名称" align="center"></el-table-column>
                <el-table-column label="状态" align="center">
                    <template slot-scope="scope">
                        <el-tag :type="scope.row.status == 1 ? 'success' : 'danger'">{{scope.row.status == 1 ? "启用" : "禁用"}}</el-tag>
                    </template>
                </el-table-column>
            </el-table>
            <span slot="footer" class="dialog-footer">
                <el-button @click="roleVisible = false">取 消</el-button>
                <el-button type="primary" @click="saveRole">确 定</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    import {
        requestApi
    } from '../../api/index';

    import {
        errMsg
    } from '../../utils/tool';


    export default {
        data() {
            return {
                test: true,
                query: {
                    name: '',
                    mobile: '',
                    status: '',
                    page: 1,
                    size: 20
                },
                tableData: [],
                multipleSelection: [],
                editVisible: false,
                addVisible: false,
                addForm: {
                    status: 1
                },
                total: 0,
                form: {},
                idx: -1,
                id: -1,
                title: '',
                icon: '',
                roleVisible: false,
                roleForm: [],
                roleMultipleSelection: []
            };
        },
        created() {

            this.title = this.$route.meta.title;
            this.icon = this.$route.meta.icon;

            this.getData();
        },
        methods: {
            // 绑定角色
            saveRole() {

                var ids = this.roleMultipleSelection.map(item => item.id);

                requestApi("saveStaffRoles", {
                    id: this.id,
                    roles: ids
                }).then(res => {
                    this.$message.success("保存成功");
                    this.roleVisible = false;
                }).catch(error => {
                    errMsg(this, error);
                });
            },
            // 绑定角色多选框
            handleRoleSelectionChange(val) {
                this.roleMultipleSelection = val;
            },
            initRoleChecked() {
                this.roleForm.forEach(row => {
                    if (row.checked) {
                        this.$refs.roleForm.toggleRowSelection(row, true);
                    }
                });
            },
            handleSetting(index, id) {
                this.idx = index;
                this.id = id;
                this.roleVisible = true;

                requestApi("rolesWithAdmin", {
                    "admin_id": id
                }).then(res => {

                    this.roleForm = res.data || [];

                    var that = this;
                    setTimeout(function() {
                        that.initRoleChecked();
                    }, 200);

                }).catch(error => {
                    errMsg(this, error);
                })
            },
            resetAddForm() {
                this.addForm = {
                    status: 1
                };
            },
            getData() {

                requestApi("staff", this.query).then(res => {

                    let data = res.data;

                    this.tableData = data.data;
                    this.total = data.total || 0;
                }).catch(error => {
                    errMsg(this, error);
                });

            },
            // 触发搜索按钮
            handleSearch() {
                this.$set(this.query, 'page', 1);
                this.getData();
            },
            // 删除操作
            handleDelete(index, row) {

                // 二次确认删除
                this.$confirm('确定要删除 [' + row.name + '] 吗？', '提示', {
                    type: 'warning'
                }).then(() => {
                    requestApi("staffbatchDelete", {
                        "ids": [row.id]
                    }).then(res => {
                        this.$message.success('删除成功');
                        this.tableData.splice(index, 1);
                    }).catch(error => {
                        errMsg(this, error);
                    });

                }).catch(() => {});
            },
            // 多选操作
            handleSelectionChange(val) {
                this.multipleSelection = val;
            },
            delAllSelection() {

                // 二次确认删除
                this.$confirm('确定要批量删除吗？', '提示', {
                    type: 'warning'
                }).then(() => {
                    const length = this.multipleSelection.length;

                    if (!length) {
                        return false;
                    }

                    let str = '';
                    let ids = [];

                    for (let i = 0; i < length; i++) {
                        str += this.multipleSelection[i].name + ' ';
                        ids.push(this.multipleSelection[i].id);
                    }

                    requestApi("staffbatchDelete", {
                        "ids": ids
                    }).then(res => {
                        this.$message.success(`删除了${str}`);
                        this.multipleSelection = [];
                        this.getData();

                    }).catch(error => {
                        errMsg(this, error);
                    })

                }).catch(() => {});

            },
            // 编辑操作
            handleEdit(index, row) {
                this.idx = index;
                this.form = Object.assign({}, row);
                this.editVisible = true;
            },
            // 新增操作
            handleAdd() {
                this.addVisible = true;
            },
            saveAdd() {
                requestApi("staffInsert", this.addForm).then(res => {
                    this.$message.success('新增成功');
                    this.resetAddForm();
                    this.getData();
                    this.addVisible = false;
                }).catch(error => {
                    errMsg(this, error);
                });
            },
            // 保存编辑
            saveEdit() {

                requestApi("staffUpdate", this.form).then(res => {
                    this.$message.success(`修改成功`);
                    this.$set(this.tableData, this.idx, this.form);
                    this.editVisible = false;
                }).catch(error => {
                    errMsg(this, error);
                })
            },
            // 分页导航
            handlePageChange(val) {
                this.$set(this.query, 'page', val);
                this.getData();
            }
        }
    };
</script>

<style scoped>
    .handle-box {
        margin-bottom: 20px;
    }

    .handle-select {
        width: 120px;
    }

    .handle-input {
        width: 300px;
        display: inline-block;
    }

    .table {
        width: 100%;
        font-size: 14px;
    }

    .red {
        color: #ff0000;
    }

    .mr10 {
        margin-right: 10px;
    }

    .table-td-thumb {
        display: block;
        margin: auto;
        width: 40px;
        height: 40px;
    }
</style>
