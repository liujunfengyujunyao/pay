define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'check/cost/index' + location.search,
                    add_url: 'check/cost/add',
                    edit_url: 'check/cost/edit',
                    del_url: 'check/cost/del',
                    multi_url: 'check/cost/multi',
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
                        {field: 'name', title: __('Name')},
                        {field: 'mobile', title: __('Mobile')},
                        {field: 'email', title: __('Email')},
                        {field: 'type', title: __('申请类型'), searchList: {"3":__('Type 3'),"2":__('Type 2'),"1":__('Type 1')}, formatter: Table.api.formatter.normal},
                        {field: 'update_time', title: __('Update_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api .formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: '查看',
                                    title: __('查看'),
                                    classname: 'btn btn-xs btn-primary btn-addtabs',
                                    icon: 'fa fa-address-card',
                                    url: 'check/cost/detail',
                                },{
                                    name: '设置费率',
                                    title: __('设置费率'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-strikethrough',
                                    url: 'check/cost/cost',
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
        cost: function() {
            $(document).ready(function() {
                $(".false").click(function() {
                    var index = parent.layer.getFrameIndex(window.name);
                    parent.layer.close(index);
                });
                $(".confirm").click(function() {
                    obj = new Object();
                    obj.id = $("#hi").val();
                    obj.cost = $(".num").text();
                    $.ajax({
                        url: "",
                        async: false,
                        data: obj,
                        type: "POST",
                        dataType: "json",
                        success: function(res) {
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                            alert("设置费率成功");
                        },
                        error: function() {
                            alert("设置失败请重新设置");
                        }
                    });
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