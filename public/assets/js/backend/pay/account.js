define(['jquery', 'bootstrap', 'backend', 'table', 'form','jquery-code'], function ($, undefined, Backend, Table, Form,jquery_code) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'pay/account/index' + location.search,
                    add_url: 'pay/account/add',
                    edit_url: 'pay/account/edit',
                    del_url: 'pay/account/del',
                    multi_url: 'pay/account/multi',
                    table: 'wk_order',
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
                        {field: 'order_id', title: __('Order_id')},
                        {field: 'unique_order_id', title: __('Unique_order_id')},
                        {field: 'order_status', title: __('支付状态'), searchList: {"1":__('Order_status 1'),"2":__('Order_status 2'),"3":__('Order_status 3')}, formatter: Table.api.formatter.status},
                        {field: 'type', title: __('Type'), searchList: {"1":__('Type 1'),"2":__('Type 2')}, formatter: Table.api.formatter.normal},
                        {field: 'order_amount', title: __('Order_amount'), operate:'BETWEEN'},
                        {field: 'update_time', title: __('Update_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},

                        {
                            field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'refund',
                                    title: __('退款'),
                                    classname: 'btn btn-xs btn-primary btn-addtabs',
                                    icon: 'fa fa-list',
                                    url: 'pay/account/refund',
                                    hidden:function(row){
                                        // Layer.alert("接收到回传数据：" + JSON.stringify(row), {title: "回传数据"});
                                        // Layer.alert("接收到回传数据：" +  JSON.stringify(111), {title: "回传数据"});
                                        // if(row.order_status == 1 || row.order_status == 3 || row.update_time < parseInt(Date.parse(new Date())/1000-7200)){开启2小时内退款限制
                                        if(row.order_status == 1 || row.order_status == 3){

                                            return true;
                                        }
                                    },
                                    // callback: function (data) {
                                    //     Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    // }
                                }],
                            formatter: Table.api.formatter.operate
                        },
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
        paymethod:function () {
            $(".weixinbtn").click(function() {
                if($('#money').val().trim()===''|| $('#money').val().trim()<=0){
                    alert('金额必须是不能为空的大于0的数值');
                    return(false)
                }
                var amount = $("#money").val();
                var query = new Object();
                query.amount = amount;
                query.type = 2;
                $.ajax({
                    url: "pay/account/paymethod",
                    async: false, //同步 非异步
                    data: query,
                    type: "POST",
                    dataType: "json",
                    success: function(result) {
                        // var path = "data:image/png;base64," + result.qrcode;
                        var path = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAZoAAAGaAQMAAAAMyBcgAAAABlBMVEX///8AAABVwtN+AAACZElEQVR4nO2a263EMAhEkVKAS0rrKSkFROLewGDwvgogMx+7dsLx/iBeXhGKoqifGgodsqlvj3GJ7Od2r1TNKowuQv0hfAOSMN1VfRtvqzGh3pC5h5mqnouBkyPeHoSeBV3hKG5v0UV8ReiJkO8396VC3iL0KAiP3R6pxs5I1/qVnwg1gxQ6BqLLxw+IUH+oCA2Kb5Bq/p9ZvvkqQh0hKy8QU2AKlxkoR8U7F0IPgKw9PdGtpstYGMkXOtsXQt2hWXJYt6pmFdUGtnZaZCNC3aER8cNdZsxhVtYdFnFkI/QM6NYe1SayjKcam3bOmpTQUyBPIVsUoScGFrmCaTwj1B0SKT7i3lO23qAgzuSUi1BjyN/ZEm1JRJkaUzzi1PxEqDvk/aiPLnSqVKeoWAk9ATL32E+0rFF8rIFF5k0qod6QbDGmWM7QeWWGBmW5HiPUHRooOF/IfBYOJYT6Q3lXWt+VUReq0xyCE+oNVTfCGe5Gc6ahdZxBqDs0svSUaEaiS5EcXexaUw2hxpC7UcyxNGOKW9WZ+Es5SqgpNE2X/9Ak6fvbjdZZGKGmUDQjWM32dNp7OYrZlhDqD6lGtXHVdz7TUAy9YtR1EeoPxeN5hlenIOPcNfYQag+tg6sSbCzBQC/RiFBPKJUhRpBvTIkv5SihrtBsWY8YYnjpOVc1Eb31uYQaQviOqlPWOBOZJ8cZhPpDqjnF9DPiutwHG6H3lpVQd+itGfFgk9cin9yIUFfI99GoHtmWnHFf8sGNCHWETOkjb12K/+ViC9ci1BxSyCBzlH161R1iIrrUXyDUGKIoivqqP3HYdw4lrBA1AAAAAElFTkSuQmCC";
                        // var path = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAZoAAAGaAQMAAAAMyBcgAAAABlBMVEX///8AAABVwtN+AAACYklEQVR4nO2ZUY7DMAhEkXqAHilXz5F8gEhsjBnAW3X3nwwfblL75WsEDBZhMBiMP+OtHuf9JIde88+XrmW+3hv34nER6g/5r+3h6I3PDT3nP8cQf4rDhHpDJo+U0YbnJ01phJ4E3adUhy0Qz3wl9FxoHp26OYZnl1VlTkJPgyCUeX4e1YFTur7xT30i1AtSjyWZb4sHof5QxiYjpJhQ0Jcg1BHSbEdTVbo1pivZVBkR6grZUZtavENBqxM1Bb38C6f5lSGE2kMxvYq9bDTK62HOJbRHqC8EC5K2BN5E1UsN8JxpEGoMYcztzvSXgkr5uWp9ItQXKhqZMfIKRGv6OVCDCHWH5FU04ku0nsulKGbiVXuEukJmQdaCC9N1ClPvhH7Nwgj1hSKTrDzjWnpp1htfXHmEmkN5C4ZOtEoGT5vPJdQbOuFS0ox4jChE6EoIdYfQfFi4Uc3EYmkHvvWzHSXUEHp7lUEdgW6WQRkxAc2MQ6g5ZHuJQzd7B+JDrzIvJ9QVKqNvWzLcqniViTxD6AnQqE+rA/G+w01LGhlCj4Dm5ikSViXrzQUvK5/aI9QSwuhCysU5ehFUmZh7bvfuhJpCl2DWrdskI32rfiQWQs2hE8UECsohhtSiQ+gxkEQSUbyaaTm0hkQQagwV3HbgUkxL62vZjg5C/aGcWWFW4QYFgqrTjUgshBpD/rv1n7UJ1dJ8lHaUUGNIiz2dkGQvUnpSTzuEHgRpNB/zaA64EBJB6BmQaQRTLpeM43mY0AMgi82oOukFZmtDCLWHcPIUQXbBNSkaEpG8QSPUHWIwGIyv8QPkUq6YFOCnmQAAAABJRU5ErkJggg==";
                        $("#qrcode").attr("src", path);
                        $("#weixinPay").css("display", "block");
                        $("#zhifuPay").css("display", "none");
                        payok(result.unique_order_id);
                    },
                    error: function() {
                        alert("失败");
                    }
                });
            });
            $(".zhifubtn").click(function() {
                if($('#money').val().trim()===''||$('#money').val().trim()<=0){
                    alert('金额必须是不能为空的大于0的数值');
                    return(false)
                }
                var amount = $("#money").val();
                var query = new Object();
                query.amount = amount;
                query.type = 1;
                $.ajax({
                    url: "pay/account/paymethod",
                    async: false, //同步 非异步
                    data: query,
                    type: "POST",
                    dataType: "json",
                    success: function(result) {
                        // var path = "data:image/png;base64," + result.qrcode;
                        // var path = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAcIAAAHCAQMAAABG1lsGAAAABlBMVEX///8AAABVwtN+AAAC20lEQVR4nO2aQXLDMAhFNeMD+Ei+uo+UA3iGOsAXyE3bZTePRVpLPGXzB/BXxiAIgvj32C3jHJvdz9v93702jtf9kB++dg1lXpCQC5l/b1KPh2VWbXh+S4GEXMhlWfpKzaXgIs8fT0jIH8nNPD+hoZi1DBLyD3KfInu3QnttJnIzSMhfSY/Ijw8/7eW7ljoMciEgIZPMPhf5v39kQEIuZIVU5dO4Pizf8j7kQ0LO8Grle1W3avx2vAQKCfmNnFOT/CR/zIgN6fCowyEhOzmqRjVTKZSWu5FyjlQpJGQnh97ZLO3IlNsrZRn55z4742tAQj5IzdtmspeUGq3Q12Q+LeqDhHzH1jSnSSrVp3Hq0kFjWgOQkJOc89MpvAbxmKQOb4vRHvs0Dgk5ZuqjC8a1iC1nvM+NFzxIyAepFcnNT9M07qf1pggJ+ZGMiFmp9lTBVNrWSgcJOcn96jf+o0S2p5XkUa91kJAfyCpZWcZMUFQwkw7rVwCQkGONkKA91VcmwehO+AUJ2cloe9PI3tMayII2PQOXYGoTEnIhpx2ZV7amyxBpbsy8aIDlcENCKnIlVJUedruy9d2sW1ubriAhi2zC0wwuCbo2vQEKh4R8kHqjG0POUloDexejzMopRkjIInOSirp17lebmkJ4nlq+U6kPEjKiX5DIyM69Gp0kQUjIj2R1vGYDhATH6Jqb3wAJ2ckconK5+lxNTXIs8/DmSUFCjoeVFPcgaWTnIJ7CK20aJOTz3T6VtjS7x61aXfsvuoWEfMe0kix1qFp25a772meVMYOEXMkuQbkCV5ObcPXDVbeQkDUmnXN0OtQFPTzLTK6Ark8gISeZWtIkVQ63+9rr/BRfc0FCPsiUVolRl7d57t4hSMifybC005PUY3e4Ha8bf0jID2RE1DJVsDY/Hd+dJUjI3gUXm3uMOU41QyDrGyRkJxfNWf1CxA+yuoKbczkk5EoSBEH8a3wBmRD++CE171gAAAAASUVORK5CYII=";
                        $("#qrcode").attr("src", path);
                        $("#zhifuPay").css("display", "block");
                        $("#weixinPay").css("display", "none");
                        payok(result.unique_order_id);
                    },
                    error: function() {
                        alert("失败");
                    }
                });
            });
            function payok(unique_order_id) {
                var query = new Object();
                query.id = unique_order_id;
                $.ajax({
                    url: "pay/account/order_status",
                    async: false,
                    data: query,
                    type: "POST",
                    dataType: "json",
                    success: function(res) {
                        if (res.status === 2) {
                            alert("支付成功！");
                            $("#zhifuPay").css("display", "none");
                            $("#weixinPay").css("display", "none");
                            $("#qrcode").attr("src", null);
                        } else {
                            setTimeout(() => {
                                payok(unique_order_id);
                            }, 2000);
                        }
                    }
                });
            }
            $("#money").blur(function() {
                let reg = /^([1-9]\d*(\.\d*[1-9])?)|(0\.\d*[1-9])$/;
                if ($('#money').val().trim().length === 0) {
                    $('.warntext').css('display','none')
                    console.log('1')
                } else if (
                    !reg.test($('#money').val().trim())
                ) {
                    console.log('2');
                    $('.warntext').css('display','block')
                } else {
                    $('.warntext').css('display','none')
                }
                return true;
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
