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

                        <div style="margin-bottom: 20px;">
                            <div>
                                <el-input placeholder="请输入用户昵称" v-model="search_value" class="input-with-select">
                                    <el-button slot="append" icon="el-icon-search" @click="searchValueChange"></el-button>
                                </el-input>
                            </div>
                        </div>

                        <template>
                            <el-table :data="tableData">

                                <el-table-column label="用户头像" align="center" width="80">
                                        <template slot-scope="scope">
                                        <img :src="scope.row.avatarUrl" style="width: 70px; height: 70px;">
                                    </template>
                                </el-table-column>

                                <el-table-column label="昵称"  align="center">
                                        <template slot-scope="scope">
                                        <span>{{ scope.row.nickName }}</span>
                                    </template>
                                </el-table-column>

                                <el-table-column label="贡献" align="center">
                                    <template slot-scope="scope">
                                        <span>{{ scope.row.contribution }}</span>
                                    </template>
                                </el-table-column>

                                <el-table-column label="分红" align="center">
                                    <template slot-scope="scope">
                                        <span>{{ scope.row.bonus }}</span>
                                    </template>
                                </el-table-column>

                                <el-table-column label="数据中心" align="center">
                                    <template slot-scope="scope">
                                        <span v-if="scope.row.callback_status == 0" style="background-color: #8c2b27; color: white; padding: 10px">未同步</span>
                                        <span v-if="scope.row.callback_status == 1" style="background-color: #5eb95e; color: white; padding: 10px">已同步</span>
                                    </template>
                                </el-table-column>

                                <el-table-column label="签到时间" align="center">
                                    <template slot-scope="scope">
                                        <span v-if="scope.row.pay_time != 0">{{ scope.row.create_time }}</span>
                                    </template>
                                </el-table-column>

                            </el-table>
                            <div class="block">
                                <el-pagination @size-change="sizeChange" @current-change="currentChange" :current-page="currentPage" :page-sizes="[10, 50, 250, 1250]" :page-size="limit" layout="total, sizes, prev, pager, next, jumper" :total="dataTotal">
                                </el-pagination>
                            </div>
                        </template>

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
    var Main = {
        data() {
            return {
                dataTotal: 0,                                                               //记录总数
                tableData: [],                                                              //当前页数据
                currentPage: 1,
                limit: 10,

                search_value: '',                                                            //搜索值
            }
        },
        methods: {
            // 每页条数改变
            sizeChange(val) {
                this.limit = val;
                this.recordGet();
            },
            // 当前页改变
            currentChange(val) {
                this.currentPage = val;
                this.recordGet();
            },
            // 搜索条件改变
            searchValueChange(){
                this.currentPage = 1;
                this.recordGet();
            },
            // 获取线下交易记录
            recordGet(){
                var url = 'index.php?s=/store/market.usercheckin/get_checkIn_list&user_name=' + this.search_value + '&page=' + this.currentPage + '&limit=' + this.limit;
                axios.get(url).then((res) => {
                    console.log(res);
                    if ( res.data.code == 0){
                        this.dataTotal = res.data.data.total;
                        this.tableData = res.data.data.data;
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
            this.recordGet();
        },
    };
    var Ctor = Vue.extend(Main);
    new Ctor().$mount('#app');
</script>
