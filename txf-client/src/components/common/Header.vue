<template>
    <div>
        <div class="header">
            <!-- 折叠按钮 -->
            <div class="collapse-btn" @click="collapseChage">
                <i v-if="!collapse" class="el-icon-s-fold"></i>
                <i v-else class="el-icon-s-unfold"></i>
            </div>
            <div class="logo">hyperf-vue-cms</div>
            <div class="header-right">
                <div class="header-user-con">
                    <!-- 全屏显示 -->
                    <div class="btn-fullscreen" @click="handleFullScreen">
                        <el-tooltip effect="dark" :content="fullscreen ? `取消全屏` : `全屏`" placement="bottom">
                            <i class="el-icon-rank"></i>
                        </el-tooltip>
                    </div>
                    <!-- 用户名下拉菜单 -->
                    <el-dropdown class="user-name" trigger="click" @command="handleCommand">
                        <span class="el-dropdown-link">
                            {{ username }}
                            <i class="el-icon-caret-bottom"></i>
                        </span>
                        <el-dropdown-menu slot="dropdown">
                            <el-dropdown-item command="showedit">更改密码</el-dropdown-item>
                            <el-dropdown-item divided command="loginout">退出登录</el-dropdown-item>
                        </el-dropdown-menu>
                    </el-dropdown>
                </div>
            </div>
        </div>

        <!-- 更改密码 -->
        <div>
            <el-dialog v-dialogDrag title="更改密码" :visible.sync="editVisible" width="30%">
                <el-form ref="form" :model="form" label-width="100px">
                    <el-form-item label="原密码">
                        <el-input show-password v-model="form.old_password"></el-input>
                    </el-form-item>
                    <el-form-item label="新密码">
                        <el-input show-password v-model="form.new_password"></el-input>
                    </el-form-item>
                    <el-form-item label="确认密码">
                        <el-input show-password v-model="form.again_password"></el-input>
                    </el-form-item>
                </el-form>
                <span slot="footer" class="dialog-footer">
                    <el-button @click="editVisible = false">取 消</el-button>
                    <el-button type="primary" @click="saveEditPass">确 定</el-button>
                </span>
            </el-dialog>
        </div>
    </div>
</template>
<script>
import bus from '../common/bus';

export default {
    data() {
        return {
            collapse: false,
            fullscreen: false,
            name: 'hyperf-vue-cms',
            message: 2,
            editVisible: false,
            form: {}
        };
    },
    computed: {
        username() {
            let username = localStorage.getItem('cms_username');
            return username ? username : this.name;
        }
    },
    methods: {
        saveEditPass() {
            this.$api
                .updatePassword(this.form)
                .then((res) => {
                    if (res.code == 0) {
                        this.$message.success('修改成功');
                        this.editVisible = false;
                    }
                })
                .catch((error) => {});
        },
        // 用户名下拉菜单选择事件
        handleCommand(command) {
            if (command == 'loginout') {
                this.$api
                    .loginOut({})
                    .then((res) => {
                        if (res.code == 0) {
                            this.$message.success('退出登录');
                        }
                        localStorage.removeItem('cms_token');
                        localStorage.removeItem('cms_username');
                        localStorage.removeItem('cms_menus');
                        localStorage.removeItem('cms_routes');
                    })
                    .catch((error) => {
                        localStorage.removeItem('cms_token');
                        localStorage.removeItem('cms_username');
                        localStorage.removeItem('cms_menus');
                        localStorage.removeItem('cms_routes');
                    });

                this.$router.push('/login');
            } else if (command == 'showedit') {
                this.editVisible = true;
            }
        },
        // 侧边栏折叠
        collapseChage() {
            this.collapse = !this.collapse;
            bus.$emit('collapse', this.collapse);
        },
        // 全屏事件
        handleFullScreen() {
            let element = document.documentElement;
            if (this.fullscreen) {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitCancelFullScreen) {
                    document.webkitCancelFullScreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            } else {
                if (element.requestFullscreen) {
                    element.requestFullscreen();
                } else if (element.webkitRequestFullScreen) {
                    element.webkitRequestFullScreen();
                } else if (element.mozRequestFullScreen) {
                    element.mozRequestFullScreen();
                } else if (element.msRequestFullscreen) {
                    // IE11
                    element.msRequestFullscreen();
                }
            }
            this.fullscreen = !this.fullscreen;
        }
    },
    mounted() {
        if (document.body.clientWidth < 1500) {
            this.collapseChage();
        }
    }
};
</script>
<style scoped>
.header {
    position: relative;
    box-sizing: border-box;
    width: 100%;
    height: 70px;
    font-size: 22px;
    color: #fff;
}

.collapse-btn {
    float: left;
    padding: 0 21px;
    cursor: pointer;
    line-height: 70px;
}

.header .logo {
    float: left;
    width: 250px;
    line-height: 70px;
}

.header-right {
    float: right;
    padding-right: 50px;
}

.header-user-con {
    display: flex;
    height: 70px;
    align-items: center;
}

.btn-fullscreen {
    transform: rotate(45deg);
    margin-right: 5px;
    font-size: 24px;
}

.btn-bell,
.btn-fullscreen {
    position: relative;
    width: 30px;
    height: 30px;
    text-align: center;
    border-radius: 15px;
    cursor: pointer;
}

.btn-bell-badge {
    position: absolute;
    right: 0;
    top: -2px;
    width: 8px;
    height: 8px;
    border-radius: 4px;
    background: #f56c6c;
    color: #fff;
}

.btn-bell .el-icon-bell {
    color: #fff;
}

.user-name {
    margin-left: 10px;
}

.user-avator {
    margin-left: 20px;
}

.user-avator img {
    display: block;
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

.el-dropdown-link {
    color: #fff;
    cursor: pointer;
}

.el-dropdown-menu__item {
    text-align: center;
}
</style>
