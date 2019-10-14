<link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
<style>
    .el-dropdown-link {
        cursor: pointer;
        color: #409EFF;
    }
    .el-icon-arrow-down {
        font-size: 12px;
    }
    .el-input{
        width: 230px;
    }
    .el-table{
        font-size: 10px;
    }
</style>

<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf"><?= $title ?></div>
                </div>
                <div class="widget-body am-fr">
                    <div id="app" style="padding: 10px;">

                        <el-form ref="form" ref="form" label-width="120px">

                            <!-- inc dec final -->
                            <el-form-item label="是否开启奖励">
                                <el-radio-group v-model="is_open">
                                    <el-radio :label="0">关闭</el-radio>
                                    <el-radio :label="1">开启</el-radio>
                                </el-radio-group>
                            </el-form-item>

                            <el-form-item label="随机贡献">
                                <el-input type="text" v-for="(item, index) in contribution" v-model="contribution[index]" @input="valueChange" style="width: 75px;margin-right: 10px;"></el-input>
                                <el-button type="warning" @click="minusItem" icon="el-icon-minus" size="mini" circle></el-button>
                                <el-button type="success" @click="plusItem" icon="el-icon-plus" size="mini" circle></el-button>
                            </el-form-item>

                            <el-form-item label="随机分红">
                                <el-input type="text" v-for="(item, index) in bonus" :value="bonus[index]" style="width: 75px;margin-right: 10px;" :disabled="true"></el-input>
                            </el-form-item>

                            <el-form-item>
                                <el-button type="primary" @click="setUserCheckSetting">确 定</el-button>
                            </el-form-item>
                        </el-form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://cdn.bootcss.com/axios/0.19.0-beta.1/axios.min.js"></script>
<script src="https://unpkg.com/element-ui/lib/index.js"></script>
<script>
    // Vue异步信息
    var Main = {
        data() {
            return {
                is_open: 1,                  // 是否开始
                contribution:[],   // 随机贡献
                bonus:[],          // 随机分红
            }
        },
        methods: {
            // 贡献值改变触发分红值改变
            valueChange(val){
                this.bonus = this.contribution;
            },
            // 添加随机项
            plusItem(){
                var item = '0';
                this.contribution.push(item);
                this.bonus = this.contribution;
            },
            // 删除随机项
            minusItem(){
                this.contribution.splice(this.contribution.length - 1, 1);
                this.bonus = this.contribution;
            },
            // 获取签到设置
            getUserCheckSetting(){
                var url = 'index.php?s=/store/market.usercheckin/get_setting';
                axios.get(url).then((res) => {
                    if ( res.data.code == 0){
                        this.is_open = res.data.data.is_open;
                        this.contribution = res.data.data.contribution;
                        this.bonus = res.data.data.bonus;
                    } else {
                        this.$message({
                            type: 'error',
                            message: res.data.msg
                        });
                    }
                }).catch((error) => {
                    this.$message({
                        type: 'error',
                        message: error.msg
                    });
                });
            },
            // 修改签到设置
            setUserCheckSetting(){
                var row = {
                    is_open: parseInt(this.is_open),
                    contribution: this.contribution,
                    bonus: this.bonus,
                };
                console.log(row);
                var url = 'index.php?s=/store/market.usercheckin/save_setting';
                axios.put(url, row).then((res) => {
                    if ( res.data.code == 0){
                        this.$message({
                            type: 'success',
                            message: res.data.msg,
                            onClose: this.getUserCheckSetting,
                        });
                    } else {
                        this.$message({
                            type: 'error',
                            message: res.data.msg
                        });
                    }
                }).catch((error) => {
                    this.$message({
                        type: 'error',
                        message: error.msg
                    });
                });
            },
        },
        mounted: function () {
            this.getUserCheckSetting();
        },
    };
    var Ctor = Vue.extend(Main);
    new Ctor().$mount('#app');
</script>
