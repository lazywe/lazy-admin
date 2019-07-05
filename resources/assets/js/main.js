/**
 * 公用自定义js
 */
$(function() {

    /**
     * debug
     */
    var debug = DEBUG;

    /**
     * 禁止多次ajax处理
     */
    var submitStatus = false;

    var ajaxState = false;

    // 配置toast提示框
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-top-right",
        "onclick": null,
        "showDuration": "800",
        "hideDuration": "800",
        "timeOut": "1000",
        "extendedTimeOut": "200",
        "progressBar": true
    }

    /**
     * 确认弹出框
     * 
     * @param string   info    提示信息
     * @param function trueFun 正确回掉函数
     * @param function cancelFun 取消回掉函数
     * @param string   title   提示头
     */
    $.confirm = function(info, trueFun, cancelFun, title) {
        typeof info == "undefined" ? info = "确认这样操作吗？" : ""
        typeof title == "undefined" ? title = "提示信息" : ""

        //询问框
        var main = layer.confirm(info, {
            btn: ['确认', '取消'], //按钮
            skin: 'layui-layer-molv',
            title: title
        }, function() {
            layer.close(main)
            typeof trueFun == "function" && trueFun();
        }, function() {
            layer.close(main)
            typeof cancelFun == "function" && cancelFun();
        });
    }

    /**
     * 确认输入弹出框
     * 
     * @param string   info    提示信息
     * @param function trueFun 正确回掉函数
     */
    $.prompt = function(info, trueFun) {
        typeof info == "undefined" ? info = "输入内容，并确认" : ""
        var promptindex = layer.prompt({
            title: info, 
            formType: 2
        }, function(text){
            layer.close(promptindex);
            typeof trueFun == "function" && trueFun(text);
        });
    }

    $('body').off('click', '.batch-checkbox-all').on('click', '.batch-checkbox-all', function(){
        var t = $(this)
        if (t.is(":checked")) {
            $(".batch-checkbox").prop("checked", true)
        } else {
            $(".batch-checkbox").prop("checked", false)
        }
    })

    /**
     * 批量操作
     */
    $('body').on('click', '.batch-confirm-btn', function() {
        var self = $(this)
        var info = self.attr('data-confirm-info');
        var url = self.attr('data-url');
        var method = self.attr('data-method');
        var batch_data = [];
        // 批量数据获取
        try {
            var batchDom = $(".batch-checkbox")
            batchDom.each(function() {
                var t = $(this);
                if (t.is(":checked")) {
                    var data = t.attr("data-batch");
                    var newdata = JSON.parse(data)
                    batch_data.push(newdata);
                }
            })
        } catch (error) {
            console.error("error:", error);
            return false;
        }
        // 未选中判断
        if (batch_data.length <= 0) {
            toastr.error("未选择任何列!");
            return false;
        }
        // 未定义请求地址
        if (typeof url != 'string') {
            console.error('未定义请求地址(data-url)');
            return
        }
        try {
            var trueFun = eval(self.attr('data-truefun'));
            var cancelfun = eval(self.attr('data-cancelfun'));
        } catch (error) {

        }
        var data = { data: batch_data };
        // 绑定事件
        $.confirm(info, function() {
            $.ajaxRequest(url, data, method, trueFun);
        }, function() {
            typeof cancelfun == "function" && cancelfun(self)
        })
    })

    /**
     * 执行fun版本
     */
    $('body').on('click', '.operation-fun-btn', function() {
        var self = $(this)
            // 回掉函数异常
        try {
            var fun = eval(self.attr('data-fun'));
            typeof fun == "function" && fun(self)
        } catch (error) {
            console.error('未定义回掉函数(data-fun)');
        }
    })

    /**
     * 切换弹出选择列表
     */
    $('body').on('click', '.operation-selected-btn', function(e) {
        e.stopPropagation(); //  阻止事件冒泡
        var self = $(this)
        try {
            var popover = self.siblings('.custom-popover')
            popover.toggle();
            var top = popover.offset().top;
            if (parseInt(top) < 50) {
                var pt = self.parents().offset().top
                popover.css("bottom", -(parseInt(pt/2)))
            } else {
                popover.css({"bottom":"-8"})
            }
        } catch (error) {
        }
    })

    /**
     * 询问提示按钮
     * 
     * 如下自定义属性
     * data-confirm-info  询问信息
     * data-url           点击确认请求地址
     * data-method        请求类型
     * data-truefun       自定义ajax成功回掉函数
     * data-cancelfun     自定义取消回掉函数
     */
    $('body').on('click', '.operation-confirm-btn', function() {
        var self = $(this)
        var info = self.attr('data-confirm-info');
        var url = self.attr('data-url');
        var method = self.attr('data-method');

        // 未定义请求地址
        if (typeof url != 'string') {
            console.error('未定义请求地址(data-url)');
            return
        }
        var data = {};
        try {
            var trueFun = eval(self.attr('data-truefun'));
            var cancelfun = eval(self.attr('data-cancelfun'));
        } catch (error) {

        }

        try {
            var params = self.attr('data-params');
            if (params != "") {
                data = JSON.parse(params);
            }
        } catch (error) {
        }

        // 绑定事件
        $.confirm(info, function() {
            $.ajaxRequest(url, data, method, trueFun);
        }, function() {
            typeof cancelfun == "function" && cancelfun(self)
        })
    })

    /**
     * 提交
     */
    $('body').on('click', '.btn-submit', function() {
        var t = $(this);
        $.submit(t);
    })

    /**
     * 提交
     */
    $('body').on('click', '.btn-submit-confirm', function() {
        var t = $(this);
        try {
            var info = t.attr('data-confirm-info');
        } catch (error) {
            console.error('data-confirm-info 未定义');
            return false;
        }
        $.confirm(info, function() {
            $.submit(t);
        })
    })

    $.submit = function (t) {
        var form = t.parents('form:first');
        var data = form.serialize();
        var url = form.attr('action');
        if (url == '') {
            toastr.error('非法的action');
            return
        }
        var method = form.attr('method');
        try {
            var fun = eval(form.attr('data-fun'))
        } catch (error) {}
        $.ajaxRequest(url, data, method, fun)
    }

    /**
     * 询问提示按钮,切输入驳回信息
     * 
     * 如下自定义属性
     * data-confirm-info  询问信息
     * data-url           点击确认请求地址
     * data-method        请求类型
     * data-truefun       自定义ajax成功回掉函数
     * data-cancelfun     自定义取消回掉函数
     */
    $('body').on('click', '.operation-prompt-btn', function() {
        var self = $(this)
        var info = self.attr('data-title');
        var url = self.attr('data-url');
        var method = self.attr('data-method');

        // 未定义请求地址
        if (typeof url != 'string') {
            console.error('未定义请求地址(data-url)');
            return
        }
        var data = {};
        try {
            var trueFun = eval(self.attr('data-truefun'));
            var cancelfun = eval(self.attr('data-cancelfun'));
        } catch (error) {

        }

        try {
            var params = self.attr('data-params');
            if (params != "") {
                data = JSON.parse(params);
            }
        } catch (error) {

        }

        // 绑定事件
        $.prompt(info, function(text) {
            data['text'] = text
            $.ajaxRequest(url, data, method, trueFun);
        })
    })



    /**
     * 提交与上传
     */
    $('body').on('click', '.btn-submit-upload', function() {
        var t = $(this);
        var form = t.parents('form:first');
        // var data = form.serialize();
        var formData =  new FormData(form.get(0));
        var url = form.attr('action');
        if (url == '') {
            toastr.error('非法的action');
            return
        }
        var method = form.attr('method');
        try {
            var fun = eval(form.attr('data-fun'))
        } catch (error) {}
        $.ajaxUploadRequest(url,formData,method,fun)
    })

    /**
     * 显示自定义panal
     */
    $('body').on('click', '.show-panal', function() {
        var t = $(this);
        layer.open({
            type: 1,
            title: false,
            shadeClose: true,
            closeBtn: 0,
            skin: 'show-panal-info', //加上边框
            content: t.siblings('.fa-panal').html()
        });
    })

    /**
     * 重新定义ajax
     *
     * @param string   url     请求地址 
     * @param json     data    请求数据,default:{}
     * @param method   method  请求类型,default:get，支持[post,get,put,delete]
     * @param function callback 成功回掉函数
     */
    $.ajaxRequest = function(url, data, method, callback) {
        // 防止多次操作ajax
        if (submitStatus == true) {
            return
        }
        submitStatus = true
        typeof method == 'undefined' ? method = 'get' : "'"
        typeof data == 'undefined' ? data = {} : ""
        if (typeof data == 'object') {
            data['_method'] = method
            data['_token'] = FormToken
        } else if (typeof data == 'string') {
            data += '&_method=' + method
            data += '&_token=' + FormToken
        }
        if (debug == true) {
            console.info("请求数据:");
            console.log(data); 
            console.info("请求地址:" + url);
            console.info("请求类型:" + method);
        }
        $.ajax({
            method: method,
            url: url,
            data: data,
            dataType : 'JSON',
            success: function(data) {
                debug == true && console.info("返回数据:") && console.log(data);
                if (typeof callback == 'undefined') {
                    if (data.status == 1) {
                        toastr.success(data.info);
                        if (data.data.url != undefined) {
                            var url = window.location.href;
                            if (data.data.url == 'back') {
                                url = Referer+'#back=-3';
                            } else {
                                url = data.data.url;
                            }
                            window.location.href = url;
                        } else {
                            window.location.reload();
                        }
                    } else {
                        toastr.error(data.info);
                    }
                } else {
                    callback(data);
                }
            },
            error: function(data) {
                debug == true && console.info(data);
                toastr.error('处理失败，请重试 !');
            },
            complete: function() {
                submitStatus = false
            }
        });
    }

    /**
	 * ajax 二次封装
	 * 
	 * @param string   url      请求地址
	 * @param string   method   请求类型
	 * @param object   data     请求数据
	 * @param function callback 回调函数
	 */
	$.ajaxUploadRequest = function(url, data, method, callback){
		if (ajaxState == true) {
			return
		}
        ajaxState = true
        data.append('_token', FormToken);
		debug && console.info(data)
		var ajaxTimeoutTest = $.ajax({
			type:method,
			url:url,
			data:data,
			processData:false,
			contentType:false,
			dataType:'json',
			success:function(data){
                debug == true && console.info("返回数据:") && console.log(data);
                if (typeof callback == 'undefined') {
                    if (data.status == 1) {
                        toastr.success(data.info);
                        if (data.data.url != undefined) {
                            var url = window.location.href;
                            if (data.data.url == 'back') {
                                url = Referer+'#back=-3';
                            } else {
                                url = data.data.url;
                            }
                            window.location.href = url;
                        } else {
                            window.location.reload();
                        }
                        
                    } else {
                        toastr.error(data.info);
                    }
                } else {
                    callback(data);
                }
			},
			error:function(){
				toastr.error("操作失败，请重试~");
			},
			complete :function(XMLHttpRequest,status) { 
				ajaxState = false
			}
		});
	}

    /**
     * 顶部提示
     */
    $.topTip = function (t, info){
        layer.tips(info, $(t), {
          tips: [1, '#0FA6D8'] //还可配置颜色
        });
    }

    var loadHtml = '<div class=\'loadingDom\'><i class=\'fa fa-spinner fa-pulse\'></i>处理中...</div>'
    var loadStatus = false;
    /**
     * 处理中状态
     */
    $.startLoad = function() {
        if (loadStatus == false) {
            loadStatus = true
            $('.sidebar-toggle').after(loadHtml);
        }
    }
    $.stopLoad = function() {
        if (loadStatus == true) {
            loadStatus = false
            $('.loadingDom').remove();
        }
    }
    $(document).ajaxStart(function() {
        $.startLoad();
    })
    $(document).ajaxStop(function() {
        $.stopLoad();
    })

    $.getHashParameters = function ()
    {
        var arr = (location.hash || "").replace(/^\#/,'').split("&");
        var params = {};
        for(var i=0; i<arr.length; i++){
            var data = arr[i].split("=");
            if(data.length == 2){
                 params[data[0]] = data[1];
            }
        }
        return params;
    }

    // 全局返回
    $('body').on('click', '.history-back', function() {
        var params = $.getHashParameters();
        params['back'] != undefined ? history.go(params['back']):history.go(-1)
    })

    // 不走前往url
    $('body').on('click', '.goUrl', function(){
        var url = $(this).attr('url');
        window.location.href=url;
    })

    /**
     * 加载url
     */
    $.loadUrl = function(url, title){
        layer.open({
            type: 2,
            title:title,
            skin: 'layui-layer-molv',
            area: ['60%', '450px'],
            fixed: true, //不固定
            maxmin: true,
            content: url,
        });
    }

    /**
     * 加载window
     */
    $.openWindow = function(url, title){
        layer.open({
            type: 2,
            title:title,
            skin: 'layui-layer-molv',
            area: ['60%', '450px'],
            fixed: true, //不固定
            maxmin: true,
            content: url,
          });
    }

    $('body').on('click', '.open-window', function() {
        var t = $(this);
        var url = t.data('url');
        var title = t.data('title');
        $.openWindow(url, title)
    })

    // 上传组件
    $('.upload-dom').each(function(e){
        var t = $(this);
        var img = t.find('img');
        var file = img.data('file')
        var name = img.data('name')
        var btn = img.data('btn')
        var value = img.data('value')
        var html = "<div class='upload-btn'>";
            html += btn ? btn: "点击上传"
            html += "<input class='upload-input' type='file' name='" + file + "'>"
        html += "<input class='upload-input' type='hidden' name='" + name + "' value='" + value +"' >"
            html += "</div>";
        t.append(html)
        t.find(".upload-input").change(function(){
            var file = $(this);
            var f = file[0].files[0];
            if (f != undefined) {
                var reads = new FileReader();
                reads.readAsDataURL(f);
                reads.onload = function (e) {
                    img.attr('src', this.result);
                };
                // 新增还原
            }
        })
    })
})
