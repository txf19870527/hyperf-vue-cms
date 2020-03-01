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
                <el-input v-model="query.role_name" placeholder="角色名" class="handle-input mr10"></el-input>
                <el-select v-model="query.status" placeholder="状态" class="handle-select mr10">
                    <el-option key="-1" label="全部" value="-1"></el-option>
                    <el-option key="1" label="启用" value="1"></el-option>
                    <el-option key="0" label="禁用" value="0"></el-option>
                </el-select>
                <el-button type="primary" icon="el-icon-search" @click="handleSearch">搜索</el-button>
            </div>

            <div class="handle-box">
                <el-button type="primary" icon="el-icon-ix-delete" class="handle-del" @click="delAllSelection">批量删除</el-button>
                <el-button type="primary" icon="el-icon-lx-add" class="handle-add mr10" @click="handleAdd">新增</el-button>
            </div>

            <el-table :data="tableData" border class="table" ref="multipleTable" header-cell-class-name="table-header"
                @selection-change="handleSelectionChange">
                <el-table-column type="selection" width="55" align="center"></el-table-column>
                <el-table-column prop="id" label="ID" width="55" align="center"></el-table-column>
                <el-table-column prop="role_name" label="角色名" align="center"></el-table-column>
                <el-table-column prop="description" label="角色描述" align="center"></el-table-column>
                <el-table-column label="状态" align="center">
                    <template slot-scope="scope">
                        <el-tag :type="scope.row.status == 1 ? 'success' : 'danger'">{{scope.row.status == 1 ? "启用" : "禁用"}}</el-tag>
                    </template>
                </el-table-column>

                <el-table-column prop="created_at" label="创建时间"></el-table-column>
                <el-table-column label="操作" width="220" align="center">
                    <template slot-scope="scope">
                        <el-button type="text" icon="el-icon-edit" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
                        <el-button type="text" icon="el-icon-delete" class="red" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
                        <el-button type="text" icon="el-icon-lx-settings" @click="handleSetting(scope.$index, scope.row.id)">绑定权限</el-button>
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
                <el-form-item label="角色名">
                    <el-input v-model="addForm.role_name"></el-input>
                </el-form-item>
                <el-form-item label="角色描述">
                    <el-input v-model="addForm.description"></el-input>
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
            <el-form ref="form" :model="form" label-width="70px">
                <el-form-item label="角色名">
                    <el-input v-model="form.role_name"></el-input>
                </el-form-item>
                <el-form-item label="角色描述">
                    <el-input v-model="form.description"></el-input>
                </el-form-item>
                <el-form-item label="状态">
                    <el-radio-group v-model="form.status">
                        <el-radio :label="1">启用</el-radio>
                        <el-radio :label="0">禁用</el-radio>
                    </el-radio-group>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="editVisible = false">取 消</el-button>
                <el-button type="primary" @click="saveEdit">确 定</el-button>
            </span>
        </el-dialog>

        <!-- 绑定权限弹出层 -->
        <el-dialog v-dialogDrag title="绑定权限" :visible.sync="permissionVisible" width="30%" :close-on-click-modal="false">

            <el-input placeholder="输入关键字进行过滤" v-model="filterText" clearable class="mb10">
            </el-input>

            <el-tree :data="permissionData" show-checkbox class="filter-tree" ref="tree" :filter-node-method="filterNode"
                node-key="id" default-expand-all :default-checked-keys="defaultCheckedKeys" :props="defaultProps">
            </el-tree>

            <span slot="footer" class="dialog-footer">
                <el-button @click="permissionVisible = false">取 消</el-button>
                <el-button type="primary" @click="savePermisssion">确 定</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    import {
        requestApi
    } from '../../api/index';
    
    import { errMsg } from '../../utils/tool';
    
    export default {
        data() {
            return {
                query: {
                    role_name: '',
                    status: '',
                    page: 1,
                    size: 20
                },
                tableData: [],
                multipleSelection: [],
                editVisible: false,
                addVisible: false,
                permissionVisible: false,
                addForm: {
                    status: 1
                },
                total: 0,
                form: {},
                idx: -1,
                id: -1,
                title: '',
                icon: '',
                permissionData: [],
                defaultCheckedKeys: [],
                defaultProps: {
                    children: 'subs',
                    label: 'title'
                },
                filterText: "",
            };
        },
        created() {
            
            this.title = this.$route.meta.title;
            this.icon = this.$route.meta.icon;

            this.getData();
        },
        watch: {
            filterText(val) {
                this.$refs.tree.filter(val);
            }
        },
        methods: {
            filterNode(value, data) {
                if (!value) return true;
                return data.title.indexOf(value) !== -1;
            },
            savePermisssion() {
                var permissionIds = this.$refs.tree.getCheckedNodes(false, true).map(item => item.id);

                requestApi("saveRolePermissions", {
                    "id": this.id,
                    "permissions": permissionIds
                }).then(res => {
                    this.$message.success("保存成功");
                    this.permissionVisible = false;
                }).catch(error => {
                    errMsg(this, error);
                });
            },
            handleSetting(index, id) {
                this.idx = index;
                this.id = id;
                this.permissionVisible = true;

                requestApi("permissionWithRole", {
                    "role_id": id
                }).then(res => {

                    var data = JSON.parse(res.data) || [];
                    this.permissionData = data.data;
                    this.defaultCheckedKeys = data.default_checked_keys;
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

                requestApi("role", this.query).then(res => {
                    var data = JSON.parse(res.data);

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
                this.$confirm('确定要删除 [' + row.role_name + '] 吗？', '提示', {
                    type: 'warning'
                }).then(() => {
                    requestApi("roleMulDel", {
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
                        str += this.multipleSelection[i].role_name + ' ';
                        ids.push(this.multipleSelection[i].id);
                    }

                    requestApi("roleMulDel", {
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
                requestApi("roleInsert", this.addForm).then(res => {
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

                requestApi("roleUpdate", this.form).then(res => {
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

    .mb10 {
        margin-bottom: 10px;
    }

    .table-td-thumb {
        display: block;
        margin: auto;
        width: 40px;
        height: 40px;
    }
</style>
