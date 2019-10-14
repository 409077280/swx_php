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
</style>

<div id="app" style="padding: 20px;">
     <div style="margin-bottom: 20px;">
          <el-select v-model="filteType" @change="filteTypeChange" placeholder="请选择">
                 <el-option label="请选择" value="all"> </el-option>
                 <el-option label="姓名" value="real_name"> </el-option>
                 <el-option label="手机号" value="mobile_phone"> </el-option>
         </el-select>

         <el-input placeholder="请输入相关的内容" @input="filteValueChange" v-model="filteValue" clearable></el-input>
     </div>

     <template>
         <el-table :data="tableData" max-height="790" height="700">

             <el-table-column label="编号"  align="center" width="90">
                     <template slot-scope="scope">
                     <span>{{ scope.row.id }}</span>
                 </template>
             </el-table-column>

             <el-table-column label="真实姓名" align="center" width="90">
                     <template slot-scope="scope">
                     <span>{{ scope.row.real_name }}</span>
                 </template>
             </el-table-column>

             <el-table-column label="手机号" align="center" width="150">
                     <template slot-scope="scope">
                     <span>{{ scope.row.mobile_phone }}</span>
                 </template>
             </el-table-column>

             <el-table-column label="公司名称"  align="center">
                     <template slot-scope="scope">
                     <span>{{ scope.row.company_name }}</span>
                 </template>
             </el-table-column>

             <el-table-column label="合作产品" align="center">
                 <template slot-scope="scope">
                     <span>{{ scope.row.product_name }}</span>
                 </template>
             </el-table-column>

             <el-table-column label="详细地址" align="center" width="250">
                 <template slot-scope="scope">
                     <span>{{ scope.row.address_detail }}</span>
                 </template>
             </el-table-column>

             <el-table-column label="留言" align="center">
                 <template slot-scope="scope">
                     <span>{{ scope.row.message }}</span>
                 </template>
             </el-table-column>

             <el-table-column label="申请时间" align="center">
                 <template slot-scope="scope">
                     <span>{{ scope.row.create_time }}</span>
                 </template>
             </el-table-column>

             <el-table-column label="详情" type="expand" width="80" align="center">
                 <template slot-scope="props">
                     <el-form label-position="left" inline class="demo-table-expand">
                         <el-form-item label="留言细节">
                             <span>{{ props.row.message }}</span>
                         </el-form-item>
                     </el-form>
                 </template>
             </el-table-column>

         </el-table>
         <div class="block">
             <el-pagination @size-change="sizeChange" @current-change="currentChange" :current-page="currentPage" :page-sizes="[10, 50, 250, 1250]" :page-size="limit" layout="total, sizes, prev, pager, next, jumper" :total="dataTotal">
             </el-pagination>
         </div>
     </template>
</div>

<script src="https://unpkg.com/vue/dist/vue.js"></script>
<script src="https://cdn.bootcss.com/axios/0.19.0-beta.1/axios.min.js"></script>
<script src="https://unpkg.com/element-ui/lib/index.js"></script>
<script>
    var Main = {
        data() {
            return {
                dataTotal: 0,
                tableData: [],

                currentPage: 1,
                limit: 10,

                filteType:'',
                filteValue: ''
            }
        },
        methods: {
            sizeChange(val) {
                this.limit = val;
                this.recordGet();
            },
            currentChange(val) {
                this.currentPage = val;
                this.recordGet();
            },
            filteTypeChange(val){
                this.filteType = val;
                this.currentPage = 1;
                this.recordGet();
            },
            filteValueChange(val){
                this.filteValue = val;
                this.currentPage = 1;
                this.recordGet();
            },
            init(){
                this.filteType = "all";
                this.recordGet();
            },
            recordGet(){
                var url = 'index.php?s=/store/merchant/lists&filteType=' + this.filteType + '&filteValue=' + this.filteValue + '&page=' + this.currentPage + '&limit=' + this.limit;
                axios.get(url).then((res) => {
                    if ( res.data.code == 0){
                        this.dataTotal = res.data.data.total;
                        this.tableData = res.data.data.data;
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
            this.init();
        },
    };
    var Ctor = Vue.extend(Main);
    new Ctor().$mount('#app');
</script>
