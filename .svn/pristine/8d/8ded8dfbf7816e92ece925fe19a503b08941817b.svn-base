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
                    <div id="app" style="padding: 20px;">
                        <div style="margin-bottom: 20px;">
                            <div style="width: 100%; margin-bottom: 20px;">
                                <el-button type="success" @click="dataToCSV" size="mini" icon="el-icon-download">订单导出</el-button>
                            </div>
                            <div>
                                <el-date-picker v-model="start_time" type="date" placeholder="选择开始日期" format="yyyy 年 MM 月 dd 日" value-format="timestamp">
                                </el-date-picker>

                                <el-date-picker v-model="end_time" type="date" placeholder="选择结束日期" format="yyyy 年 MM 月 dd 日" value-format="timestamp">
                                </el-date-picker>

                                <el-select v-model="search_type">
                                    <el-option label="订单号" value="order"> </el-option>
                                    <el-option label="用户名" value="user"> </el-option>
                                </el-select>

                                <el-input placeholder="请输入订单号/用户昵称" v-model="search_value" clearable></el-input>

                                <el-button type="primary" @click="searchValueChange" icon="el-icon-search"></el-button>
                            </div>
                        </div>

                        <template>
                            <el-table :data="tableData">

                                <el-table-column label="订单号" align="center" width="160">
                                        <template slot-scope="scope">
                                        <span>{{ scope.row.order_no }}</span>
                                    </template>
                                </el-table-column>

                                <el-table-column label="用户头像" align="center" width="80">
                                        <template slot-scope="scope">
                                        <img :src="scope.row.avatarUrl" style="width: 70px; height: 70px;">
                                    </template>
                                </el-table-column>

                                <el-table-column label="昵称"  align="center" width="130">
                                        <template slot-scope="scope">
                                        <span>{{ scope.row.nickName }}</span>
                                    </template>
                                </el-table-column>

                                <el-table-column label="实付金额" align="center" width="80">
                                    <template slot-scope="scope">
                                        <span>{{ scope.row.pay_price }}</span>
                                    </template>
                                </el-table-column>

                                <el-table-column label="支付方式" align="center" width="100">
                                    <template slot-scope="scope">
                                        <span v-if="scope.row.pay_type == 10" style="background-color: #14a6ef; color: white; padding: 10px">余额支付</span>
                                        <span v-if="scope.row.pay_type == 20" style="background-color: #F37B1D; color: white; padding: 10px">微信支付</span>
                                    </template>
                                </el-table-column>

                                <el-table-column label="付款状态" align="center" width="100">
                                    <template slot-scope="scope">
                                        <span v-if="scope.row.pay_status == 10" style="background-color: darkred; color: white; padding: 10px">未付款</span>
                                        <span v-if="scope.row.pay_status == 20" style="background-color: green; color: white; padding: 10px">已付款</span>
                                    </template>
                                </el-table-column>

                                <el-table-column label="微信交易号" align="center" width="250">
                                    <template slot-scope="scope">
                                        <span>{{ scope.row.transaction_id }}</span>
                                    </template>
                                </el-table-column>

                                <el-table-column label="留言" align="center">
                                    <template slot-scope="scope">
                                        <span>{{ scope.row.buyer_remark }}</span>
                                    </template>
                                </el-table-column>

                                <el-table-column label="付款时间" align="center" width="150">
                                    <template slot-scope="scope">
                                        <span v-if="scope.row.pay_time != 0">{{ unixTimeToDateTime(scope.row.pay_time) }}</span>
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

                pay_status: "<?= $pay_status ?>",                                            //支付状态
                start_time: '',                                                              //开始时间
                end_time: '',                                                                //结束时间
                search_type: '',                                                              //搜索方式
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
            // 时间戳转换成时间
            unixTimeToDateTime (unixTime) {
                var checkHour = function (m) {
                    return m<10?'0'+m:m
                };
                var time = new Date(unixTime * 1000);
                var y = time.getFullYear();
                var m = time.getMonth()+1;
                var d = time.getDate();
                var h = time.getHours();
                var mm = time.getMinutes();
                var s = time.getSeconds();
                return y+'-'+checkHour(m)+'-'+checkHour(d)+' '+checkHour(h)+':'+checkHour(mm)+':'+checkHour(s);
            },
            //导出数据到CSV
            dataToCSV(){
                var lables = `订单号,昵称,订单金额,实付金额,支付方式,付款状态,微信交易号,留言,付款时间\n`;
                var exportData = [];
                for(var i = 0 ; i < this.tableData.length ; i++ ){
                    var item = {};
                    item.order_no = this.tableData[i].order_no;
                    item.nickName = this.tableData[i].user_info.nickName;
                    item.pay_price = this.tableData[i].pay_price;
                    if (this.tableData[i].pay_type == 10){
                        item.pay_type = "余额支付";
                    } else if(this.tableData[i].pay_type == 20) {
                        item.pay_type = "微信支付";
                    }
                    if (this.tableData[i].pay_status == 10){
                        item.pay_status = "未付款";
                    } else if (this.tableData[i].pay_status == 20) {
                        item.pay_status = "已付款";
                    }
                    item.transaction_id = this.tableData[i].transaction_id;
                    item.buyer_remark = this.tableData[i].buyer_remark;
                    item.pay_time = this.unixTimeToDateTime(this.tableData[i].pay_time);

                    Vue.set(exportData, i, item)
                }
                for(var i = 0 ; i < exportData.length ; i++ ){
                    for(var item in exportData[i]){
                        lables+=`${exportData[i][item] + '\t'},`;
                    }
                    lables+='\n';
                }
                //encodeURIComponent解决中文乱码
                let uri = 'data:text/csv;charset=utf-8,\ufeff' + encodeURIComponent(lables);
                //通过创建a标签实现
                let link = document.createElement("a");
                link.href = uri;
                link.style.display = 'none';
                //对下载的文件命名
                var time = new Date();
                var fileName = time.getFullYear() + '-' + (time.getMonth() + 1)+ '-' + time.getDate();
                link.download =  '线下支付订单' + fileName + '.csv';
                document.body.appendChild(link);
                link.click();
            },
            // 获取线下交易记录
            recordGet(){
                var url = 'index.php?s=/store/offlineorder/get_list&pay_status=' + this.pay_status + '&start_time=' + this.start_time / 1000 + '&end_time=' + this.end_time / 1000 + '&search_type=' + this.search_type+ '&search_value=' + this.search_value+ '&page=' + this.currentPage+ '&limit=' + this.limit;
                axios.get(url).then((res) => {
                    if ( res.data.code == 0){
                        console.log(res);
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
