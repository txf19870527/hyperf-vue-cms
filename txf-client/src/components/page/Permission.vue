<template>
    <div>
        <div class="crumbs">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item> <i :class="icon"></i> {{ title }} </el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="container">
            <div class="handle-box">
                <el-input v-model="query.title" placeholder="名称" class="handle-input mr10"></el-input>
                <el-cascader
                    :options="options"
                    :props="{ checkStrictly: true, value: 'id', label: 'title', children: 'subs' }"
                    placeholder="上级权限"
                    clearable
                    class="mr10"
                    @change="changePid"
                ></el-cascader>
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

            <el-table
                :data="tableData"
                border
                class="table"
                ref="multipleTable"
                header-cell-class-name="table-header"
                @selection-change="handleSelectionChange"
            >
                <el-table-column type="selection" width="55" align="center"></el-table-column>
                <el-table-column prop="id" label="ID" width="55" align="center"></el-table-column>
                <el-table-column prop="title" label="名称" align="center"></el-table-column>
                <el-table-column label="类型" align="center">
                    <template slot-scope="scope">
                        <el-tag v-if="scope.row.type == 1" type="primary">一级菜单</el-tag>
                        <el-tag v-else-if="scope.row.type == 2" type="success">二级菜单</el-tag>
                        <el-tag v-else-if="scope.row.type == 3" type="warning">三级菜单</el-tag>
                        <el-tag v-else type="info">功能权限</el-tag>
                    </template>
                </el-table-column>
                <el-table-column prop="icon" label="图标" align="center" width="220"></el-table-column>
                <el-table-column prop="index" label="路由" align="center"></el-table-column>
                <el-table-column prop="uri" label="接口路径" align="center" width="280"></el-table-column>
                <el-table-column label="状态" align="center">
                    <template slot-scope="scope">
                        <el-tag :type="scope.row.status == 1 ? 'success' : 'danger'">{{ scope.row.status == 1 ? '启用' : '禁用' }}</el-tag>
                    </template>
                </el-table-column>
                <el-table-column prop="sort" label="排序" align="center"></el-table-column>

                <el-table-column prop="created_at" label="创建时间" width="180"></el-table-column>
                <el-table-column label="操作" width="180" align="center">
                    <template slot-scope="scope">
                        <el-button type="text" icon="el-icon-edit" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
                        <el-button type="text" icon="el-icon-lx-delete" class="red" @click="handleDelete(scope.$index, scope.row)"
                            >删除</el-button
                        >
                    </template>
                </el-table-column>
            </el-table>

            <div class="pagination">
                <el-pagination
                    background
                    layout="total, prev, pager, next"
                    :current-page="query.page"
                    :page-size="query.size"
                    :total="total"
                    @current-change="handlePageChange"
                ></el-pagination>
            </div>
        </div>

        <!-- 新增弹出框 -->
        <el-dialog v-dialogDrag title="新增" :visible.sync="addVisible" width="30%" :close-on-click-modal="false">
            <el-form ref="addForm" :model="addForm" label-width="70px">
                <el-alert class="mb20" title="注意：上级权限、类型 一旦保存后就不允许修改，请谨慎填写" type="error" center show-icon>
                </el-alert>

                <el-form-item label="名称">
                    <el-input v-model="addForm.title"></el-input>
                </el-form-item>

                <el-form-item label="上级权限">
                    <el-cascader
                        :options="options"
                        ref="cascaderInesrt"
                        :props="{ checkStrictly: true, value: 'id', label: 'title', children: 'subs' }"
                        placeholder="非必填"
                        clearable
                        class="mr10"
                        @change="changeInsert"
                    ></el-cascader>
                </el-form-item>

                <el-form-item label="类型">
                    <el-radio-group v-model="addForm.type">
                        <el-radio :label="1" v-bind:disabled="isDisabled[1]">一级菜单</el-radio>
                        <el-radio :label="2" v-bind:disabled="isDisabled[2]">二级菜单</el-radio>
                        <el-radio :label="3" v-bind:disabled="isDisabled[3]">三级菜单</el-radio>
                        <el-radio :label="4" v-bind:disabled="isDisabled[4]">功能</el-radio>
                    </el-radio-group>
                </el-form-item>

                <el-form-item label="icon图标">
                    <el-input v-model="addForm.icon" placeholder="非必填"></el-input>
                </el-form-item>

                <el-form-item label="路由">
                    <el-input v-model="addForm.index" placeholder="非必填"></el-input>
                </el-form-item>

                <el-form-item label="接口路径">
                    <el-input v-model="addForm.uri"></el-input>
                </el-form-item>

                <el-form-item label="排序">
                    <el-input-number v-model="addForm.sort" :min="-10000" :max="10000" class="mr10"></el-input-number>
                    <span>默认255，越大越靠前</span>
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
                <el-alert class="mb20" title="注意：上级权限、类型 不允许修改，请删除后重新新增" type="error" center show-icon> </el-alert>

                <el-form-item label="名称">
                    <el-input v-model="form.title"></el-input>
                </el-form-item>

                <el-form-item label="上级权限">
                    <el-cascader
                        :options="options"
                        ref="cascaderUpdate"
                        v-model="form.pid"
                        disabled
                        :props="{ checkStrictly: true, value: 'id', label: 'title', children: 'subs' }"
                        placeholder="非必填"
                        clearable
                        class="mr10"
                    ></el-cascader>
                </el-form-item>

                <el-form-item label="类型">
                    <el-radio-group v-model="form.type" disabled>
                        <el-radio :label="1">一级菜单</el-radio>
                        <el-radio :label="2">二级菜单</el-radio>
                        <el-radio :label="3">三级菜单</el-radio>
                        <el-radio :label="4">功能</el-radio>
                    </el-radio-group>
                </el-form-item>

                <el-form-item label="icon图标">
                    <el-input v-model="form.icon"></el-input>
                </el-form-item>

                <el-form-item label="路由">
                    <el-input v-model="form.index"></el-input>
                </el-form-item>

                <el-form-item label="接口路径">
                    <el-input v-model="form.uri"></el-input>
                </el-form-item>

                <el-form-item label="排序">
                    <el-input-number v-model="form.sort" :min="-10000" :max="10000" class="mr10"></el-input-number>
                    <span>数字越大越靠前</span>
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
    </div>
</template>

<script>
export default {
    data() {
        return {
            query: {
                title: '',
                status: '',
                pid: '',
                page: 1,
                size: 20
            },
            tableData: [],
            multipleSelection: [],
            editVisible: false,
            addVisible: false,
            addForm: {
                status: 1,
                type: 1,
                sort: 255,
                pid: 0
            },
            total: 0,
            form: {},
            idx: -1,
            id: -1,
            title: '',
            icon: '',
            options: [],
            isDisabled: {
                1: true,
                2: true,
                3: true,
                4: true
            }
        };
    },
    created() {
        this.title = this.$route.meta.title;
        this.icon = this.$route.meta.icon;
        this.getData();
        this.resetCascader();
    },
    methods: {
        resetCascader() {
            // 下拉权限搜索
            this.$api
                .permissionCascader({})
                .then((res) => {
                    this.options = res.data || [];
                })
                .catch((error) => {});
        },
        // 搜索框上级权限变更，更改父级ID
        changePid(event) {
            if (event.length == 0) {
                this.query.pid = 0;
            } else {
                this.query.pid = event[event.length - 1];
            }
        },
        // 联动 type radio 没有选上级->一级菜单 选了一级上级->二级菜单或功能 选了二级上级->功能或三级菜单
        changeInsert(event) {
            if (event.length == 0) {
                this.addForm.pid = 0;
            } else {
                this.addForm.pid = event[event.length - 1];
            }

            this.isDisabled = {
                1: true,
                2: true,
                3: true,
                4: true
            };

            if (this.addForm.pid != 0) {
                var nodeData = this.$refs.cascaderInesrt.getCheckedNodes();
                var type = nodeData[0]['data']['type'] || '';
                if (type == 1) {
                    this.addForm.type = 2;
                    this.isDisabled[2] = false;
                    this.isDisabled[4] = false;
                } else if (type == 2) {
                    this.addForm.type = 4;
                    this.isDisabled[3] = false;
                    this.isDisabled[4] = false;
                }
            } else {
                this.addForm.type = 1;
            }
        },
        resetAddForm() {
            this.$refs.cascaderInesrt.handleClear();

            this.addForm = {
                status: 1,
                type: 1,
                sort: 255,
                pid: 0
            };
        },
        getData() {
            this.$api
                .permission(this.query)
                .then((res) => {
                    let data = res.data;

                    this.tableData = data.data;
                    this.total = data.total || 0;
                })
                .catch((error) => {});
        },
        // 触发搜索按钮
        handleSearch() {
            this.$set(this.query, 'page', 1);
            this.getData();
        },
        // 删除操作
        handleDelete(index, row) {
            // 二次确认删除
            this.$confirm('确定要删除 [' + row.title + '] 吗？', '提示', {
                type: 'warning'
            })
                .then(() => {
                    this.$api
                        .permissionbatchDelete({ ids: [row.id] })
                        .then((res) => {
                            if (res.code == 0) {
                                this.$message.success('删除成功');
                                this.tableData.splice(index, 1);
                                this.resetCascader();
                            }
                        })
                        .catch((error) => {});
                })
                .catch(() => {});
        },
        // 多选操作
        handleSelectionChange(val) {
            this.multipleSelection = val;
        },
        delAllSelection() {
            // 二次确认删除
            this.$confirm('确定要批量删除吗？', '提示', {
                type: 'warning'
            })
                .then(() => {
                    const length = this.multipleSelection.length;

                    if (!length) {
                        return false;
                    }

                    let str = '';
                    let ids = [];

                    for (let i = 0; i < length; i++) {
                        str += this.multipleSelection[i].title + ' ';
                        ids.push(this.multipleSelection[i].id);
                    }
                    this.$api
                        .permissionbatchDelete({ ids: ids })
                        .then((res) => {
                            if (res.code == 0) {
                                this.$message.success(`删除了${str}`);
                                this.multipleSelection = [];
                                this.getData();
                                this.resetCascader();
                            }
                        })
                        .catch((error) => {});
                })
                .catch(() => {});
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
            this.$api
                .permissionInsert(this.addForm)
                .then((res) => {
                    if (res.code == 0) {
                        this.$message.success('新增成功');
                        this.getData();
                        this.resetCascader();
                        this.addVisible = false;
                        this.resetAddForm();
                    }
                })
                .catch((error) => {});
        },
        // 保存编辑
        saveEdit() {
            this.$api
                .permissionUpdate(this.form)
                .then((res) => {
                    if (res.code == 0) {
                        this.$message.success(`修改成功`);
                        this.$set(this.tableData, this.idx, this.form);
                        this.resetCascader();
                        this.editVisible = false;
                    }
                })
                .catch((error) => {});
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

.mb20 {
    margin-bottom: 20px;
}

.table-td-thumb {
    display: block;
    margin: auto;
    width: 40px;
    height: 40px;
}
</style>
