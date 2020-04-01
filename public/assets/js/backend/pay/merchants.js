define(['jquery','bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'pay/merchants/index' + location.search,
                    add_url: 'pay/merchants/add',
                    edit_url: 'pay/merchants/edit',
                    del_url: 'pay/merchants/del',
                    multi_url: 'pay/merchants/multi',
                    table: 'check_info',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'uid',
                sortName: 'uid',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'uid', title: __('Id')},
                        {field: 'name', title: __('姓名')},
                        {field: 'mobile', title: __('电话')},
                        {field: 'email', title: __('邮箱')},
                        {field: 'id_card', title: __('身份证')},
                        {field: 'type', title: __('申请类型'), searchList: {"3":__('Type 3'),"2":__('Type 2'),"1":__('Type 1')}, formatter: Table.api.formatter.normal},
                        {field: 'update_time', title: __('提交时间'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: '查看',
                                    title: __('查看'),
                                    classname: 'btn btn-xs btn-primary btn-addtabs',
                                    icon: 'fa fa-address-card',
                                    url: 'pay/merchants/detail',
                                }],
                            formatter: function (value, row, index) {//隐藏按钮方法
                                var that = $.extend({}, this);
                                var table = $(that.table).clone(true);
                                $(table).data("operate-edit", null);//被隐藏的按钮
                                $(table).data("operate-del", null);//被隐藏的按钮
                                that.table = table;
                                return Table.api.formatter.operate.call(that, value, row, index);
                            }}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        detail : function() {
            var url = document.location.href;
            var index = url.lastIndexOf("\/");
            var str = url.substring(index + 1,url.length);
            var user_id = str.split('?')[0];
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'pay/merchants/detail' + location.search,
                    // index_url: 'pay/order/index' + location.search,
                    add_url: 'pay/order/add',
                    edit_url: 'pay/order/edit',
                    del_url: 'pay/order/del',
                    multi_url: 'pay/order/multi',
                    table: 'wk_order',
                }
            });
            // $(document).on('click','.spec_add_btn', function (event) {
            //     var url = $(this).attr('data-url');
            //     if(!url) return false;
            //     var msg = $(this).attr('data-title');
            //     var width = $(this).attr('data-width');
            //     var height = $(this).attr('data-height');
            //     var area = [$(window).width() > 800 ? (width?width:'800px') : '95%', $(window).height() > 600 ? (height?height:'600px') : '95%'];
            //     var options = {
            //         shadeClose: false,
            //         shade: [0.3, '#393D49'],
            //         area: area,
            //         callback:function(value){
            //             CallBackFun(value.id, value.name);//在回调函数里可以调用你的业务代码实现前端的各种逻辑和效果
            //         }
            //     };
            //     Fast.api.open(url,msg,options);
            // });
            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url+'?user_id='+user_id,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'order_id', title: __('Order_id')},
                        {field: 'unique_order_id', title: __('Unique_order_id')},
                        {field: 'user_id', title: __('User_id')},
                        {field: 'leader_id', title: __('Leader_id')},
                        // {field: 'type', title: __('Type'), searchList: {"1":__('Type 1'),"2":__('Type 2')}, formatter: Table.api.formatter.normal},
                        {field: 'order_amount', title: __('Order_amount'), operate:'BETWEEN'},
                        {field: 'goods_detail', title: __('Goods_detail')},
                        {field: 'goods_title', title: __('Goods_title')},
                        {field: 'user_in', title: __('User_in'), operate:'BETWEEN'},
                        {field: 'agent_in', title: __('Agent_in'), operate:'BETWEEN'},
                        {field: 'platform_in', title: __('Platform_in'), operate:'BETWEEN'},
                        {field: 'yeepay_in', title: __('Yeepay_in'), operate:'BETWEEN'},
                        {field: 'order_status', title: __('Order_status'), searchList: {"1":__('Order_status 1'),"2":__('Order_status 2'),"3":__('Order_status 3')}, formatter: Table.api.formatter.status},
                        {field: 'residual_amount', title: __('Residual_amount'), operate:'BETWEEN'},
                        {field: 'divide_rate', title: __('Divide_rate'), operate:'BETWEEN'},
                        {field: 'add_time', title: __('Add_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'update_time', title: __('Update_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'sn', title: __('Sn')},
                        // {
                        //     field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                        //     buttons: [
                        //         {
                        //             name: 'refund',
                        //             title: __('退款'),
                        //             classname: 'btn btn-xs btn-primary btn-addtabs',
                        //             icon: 'fa fa-list',
                        //             url: 'pay/order/refund',
                        //             hidden:function(row){
                        //                 // Layer.alert("接收到回传数据：" + JSON.stringify(row), {title: "回传数据"});
                        //                 // Layer.alert("接收到回传数据：" +  JSON.stringify(111), {title: "回传数据"});
                        //                 if(row.order_status == 1 || row.order_status == 3 || row.update_time < parseInt(Date.parse(new Date())/1000-7200) || row.group_id == 1){
                        //
                        //                     return true;
                        //                 }
                        //             },
                        //             // callback: function (data) {
                        //             //     Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                        //             // }
                        //         }],
                        //     formatter: Table.api.formatter.operate
                        // },
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});