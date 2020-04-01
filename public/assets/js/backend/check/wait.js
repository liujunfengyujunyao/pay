define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'check/wait/index' + location.search,
                    add_url: 'check/wait/add',
                    edit_url: 'check/wait/edit',
                    del_url: 'check/wait/del',
                    multi_url: 'check/wait/multi',
                    table: 'check_info',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'uid', title: __('Uid')},
                        {field: 'name', title: __('姓名')},
                        {field: 'mobile', title: __('电话')},
                        {field: 'email', title: __('邮箱')},
                        {field: 'id_card', title: __('身份证')},
                        {field: 'type', title: __('申请类型'), searchList: {"3":__('Type 3'),"2":__('Type 2'),"1":__('Type 1')}, formatter: Table.api.formatter.normal},
                        {field: 'add_time', title: __('申请时间'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [
                                        {
                                            name: '查看',
                                            title: __('查看'),
                                            classname: 'btn btn-xs btn-primary btn-addtabs',
                                            icon: 'fa fa-list',
                                            url: 'check/wait/detail',
                                        }],
                            formatter: function (value, row, index) {//隐藏按钮方法
                            var that = $.extend({}, this);
                            var table = $(that.table).clone(true);
                            $(table).data("operate-edit", null);//被隐藏的按钮
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
        detail: function () {
            var url = document.location.href;
            var index = url.lastIndexOf("\/");
            var str = url.substring(index + 1,url.length);
            var check_id = str.split('?')[0];
            var cost = $('#cost').val();

            $("#test1").on("click",function(){
                cost = $('#cost').val();
                Fast.api.ajax({
                    url:'check/wait/detail',
                    data:{status:1,check_id:check_id,cost:cost}
                }, function(data, ret){
                    //成功的回调
                    alert(ret.msg);
                    location.href="/admin/check/wait";
                    return false;
                }, function(data, ret){
                    //失败的回调
                    alert(ret.msg);
                    return false;
                });
            });
            $("#test2").on("click",function(){
                cost = $('#cost').val();
                Fast.api.ajax({
                    url:'check/wait/detail',
                    data:{status:3,check_id:check_id,cost:cost,update:1}
                }, function(data, ret){
                    //成功的回调
                    alert(ret.msg);
                    location.href="/admin/check/wait";
                    return false;
                }, function(data, ret){
                    //失败的回调
                    alert(ret.msg);
                    return false;
                });
            });
            $("#test3").on("click",function(){
                cost = $('#cost').val();
                Fast.api.ajax({
                    url:'check/wait/detail',
                    data:{status:3,check_id:check_id,cost:cost,del:1}
                }, function(data, ret){
                    //成功的回调
                    alert(ret.msg);
                    location.href="/admin/check/wait";
                    return false;
                }, function(data, ret){
                    //失败的回调
                    alert(ret.msg);
                    return false;
                });
            });

        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});