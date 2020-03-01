<template>
    <div class="login-wrap">
        <div class="ms-login">
            <div class="ms-title">hyperf-vue-cms</div>
            <el-form :model="param" :rules="rules" ref="login" label-width="0px" class="ms-content">
                <el-form-item prop="mobile">
                    <el-input v-model="param.mobile" placeholder="mobile">
                        <el-button slot="prepend" icon="el-icon-lx-people"></el-button>
                    </el-input>
                </el-form-item>
                <el-form-item prop="password">
                    <el-input type="password" placeholder="password" v-model="param.password" @keyup.enter.native="submitForm()">
                        <el-button slot="prepend" icon="el-icon-lx-lock"></el-button>
                    </el-input>
                </el-form-item>
                <div class="login-btn">
                    <el-button type="primary" @click="submitForm()">登录</el-button>
                </div>
                <p class="login-tips">请输入手机号和密码</p>
            </el-form>
        </div>
    </div>
</template>

<script>
    import {
        requestApi
    } from '../../api/index';
    export default {
        data: function() {
            return {
                param: {
                    mobile: '',
                    password: '',
                },
                rules: {
                    mobile: [{
                        required: true,
                        message: '请输入手机号',
                        trigger: 'blur'
                    }],
                    password: [{
                        required: true,
                        message: '请输入密码',
                        trigger: 'blur'
                    }],
                },
            };
        },
        methods: {
            submitForm() {
                this.$refs.login.validate(valid => {
                    if (valid) {

                        requestApi("login", this.param).then(res => {

                            var data = JSON.parse(res.data);
                            localStorage.setItem('cms_token', data.token);
                            localStorage.setItem('cms_username', data.name);
                            localStorage.setItem('cms_menus', JSON.stringify(data.menus));
                            localStorage.setItem('cms_routes', JSON.stringify(data.routes));

                            // 初始化权限列表
                            this.$message.success('登录成功');

                            this.$router.push("/", onComplete => {}, onAbort => {})
                        }).catch(error => {
                            this.$message.error(error);
                        });

                    } else {
                        this.$message.error('请输入手机号和密码');
                    }
                });
            },
        },
    };
</script>

<style scoped>
    .login-wrap {
        position: relative;
        width: 100%;
        height: 100%;
        background-image: url(../../assets/img/login-bg.jpg);
        background-size: 100%;
    }

    .ms-title {
        width: 100%;
        line-height: 50px;
        text-align: center;
        font-size: 20px;
        color: #fff;
        border-bottom: 1px solid #ddd;
    }

    .ms-login {
        position: absolute;
        left: 50%;
        top: 50%;
        width: 350px;
        margin: -190px 0 0 -175px;
        border-radius: 5px;
        background: rgba(255, 255, 255, 0.3);
        overflow: hidden;
    }

    .ms-content {
        padding: 30px 30px;
    }

    .login-btn {
        text-align: center;
    }

    .login-btn button {
        width: 100%;
        height: 36px;
        margin-bottom: 10px;
    }

    .login-tips {
        font-size: 12px;
        line-height: 30px;
        color: #fff;
    }
</style>
