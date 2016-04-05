var Admin = function(){

    var menuEvents = function(menuNode){

            var menuNode =  menuNode ? menuNode : '.menu' ;

            $('body').undelegate(menuNode,'click');
            $('body').delegate(menuNode,'click',function(){

                var level = $(this).data('level');

                //var firstchild = firstchild ? firstchild : $('#menu'+level).children().first().children() ;

                var isLoad = $(this).data('loadchild');

                var menuid = $(this).data('id');
                
                action(level, isLoad, menuid, $(this), menuNode);

                return false;

            });
    }


    var action = function(level, isLoad, menuid, _this, menuNode){
                        //获取当前菜单等级
                //var level = level ? level : $(this).data('level');
                                //获取是否拥有自己
                //获取菜单等级
                var level = level ? level : 1 ;

                var menuNode =  menuNode ? menuNode : '.menu' ;

                //获取节点对象
                var _this = _this ? _this : $('#menu'+level).children().first().children();
                
                if(level == 3 && _this.parent().hasClass('have-child')){
                    var _this = _this
                                    .next()
                                    .children()
                                    .first()
                                    .children();
                }

                //是否加载子级
                var isLoad = isLoad ? isLoad : _this.data('loadchild');

                //获取菜单ID
                var menuid = menuid ? menuid : _this.data('id');



                //切换当前节点操作状态
                if(level == 3){
                    $('.have-child > ul > li').each(function(){

                        $(this).removeClass('active');

                    });

                }else if(level == 4){

                    _this
                        .parent()
                        .parent()
                        .parent()
                        .siblings()
                        .removeClass('active');


                }
                _this
                    .parent()
                    .addClass('active')
                    .siblings()
                    .removeClass('active');

                //如果不加载自己则加载页面
                if(isLoad == 0){

                    var src = _this.data('src');

                    //清除缓存或变量
                    clear();

                    $('#main-content').load(src,function(){

                        $('#main-title')
                                    .text(
                                        $('#menu'+level+' li.active')
                                                    .children()
                                                    .data('name')
                                    );

                        if(level == 1){

                            hideOrShow('hide','hide');

                        }else if(level == 2){

                            hideOrShow('show','hide');

                        }else{

                            hideOrShow('show','show');

                        }


                    });
                    // document.getElementById('main').src = src;
                    
                    return false;


                }else{

                }

                //加载菜单
                loadMenu('#menu'+(level+1),menuid,level);
    }

    /*
     ** @introduction: 加载菜单
     ** @author: 杨陈鹏
     ** @date: 2015-03-26 13：04
     ** @email: yangchenpeng@cdlinglu.com
     */

    var loadMenu = function(pElement,menuid,level){

        layer.load();

        var nextLevel = level ? level+1 : 1 ;

        $(pElement).load(loadMenuUrl,{'menuid':menuid,'level':level},function(data){

            layer.closeAll();

            if(level == 2){

                var name = $('#menu'+level+ ' li.active a').data('name');

                $('#second-title').text(name)

            }

            action(nextLevel);

        });

    }
    /*
    ** @introduction: 显示或隐藏侧边栏
    ** @author: 杨陈鹏
    ** @date: 2015-03-26 13：04
    ** @email: yangchenpeng@cdlinglu.com
    */
    var hideOrShow = function(s1,s2){
        var s1 = s1 ? s1 : 'show' ;
        var s2 = s2 ? s2 : 'show' ;
        //操作一级侧边栏
        if(s1 == 'show'){
            $('.body')
                .removeClass('sidebar-mini')
                .addClass('sidebar-full');
            $('.sidebar-content>.sidebar-fold').html('<span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span><span class="glyphicon glyphicon-triangle-left" aria-hidden="true"></span>');
        }else if(s1 == 'hide'){
            $('.body')
                .removeClass('sidebar-full')
                .addClass('sidebar-mini');
            $('.sidebar-content>.sidebar-fold').html('<span class="glyphicon glyphicon-triangle-left" aria-hidden="true"></span><span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>');
        }
        //操作二级侧边栏
        //操作一级侧边栏
        if(s2 == 'show'){
            $('.main')
                .removeClass('main-sidebar-hide')
                .addClass('main-sidebar-show');
        }else if(s2 == 'hide'){
            $('.main')
                .removeClass('main-sidebar-show')
                .addClass('main-sidebar-hide');
        }
    }
    /*
    ** @introduction: 显示当前系统时间
    ** @author: 杨陈鹏
    ** @date: 2015-03-28 09:30
    ** @email: yangchenpeng@cdlinglu.com
    */
    var showTime = function(){

        var date = new Date();
        var seperator1 = "/";
        var seperator2 = ":";
        var month = date.getMonth() + 1;
        var day = date.getDate();
        var hour = date.getHours();
        var minutes = date.getMinutes();
        var seconds = date.getSeconds();
        if (month >= 1 && month <= 9) {
            month = "0" + month;
        }
        if (day >= 0 && day <= 9) {
            day = "0" + day;
        }
        if(hour >= 0 && hour <=9) {
            hour = "0" + hour;    
        }
        if(minutes >= 0 && minutes <=9) {
            minutes = "0" + minutes;    
        }
        if(seconds >= 0 && seconds <=9) {
            seconds = "0" + seconds;    
        }
        
        //var timeStr = date.getFullYear() + seperator1 + month + seperator1 + day
                //+ " " + hour + seperator2 + minutes
                //+ seperator2 + seconds;
        var timeStr = "当前时间: "+ date.getFullYear()+ seperator1 +month + seperator1 + day
                + " " + hour + seperator2 + minutes
                + seperator2 + seconds;

        $('#time').text(timeStr);

        //一秒刷新一次显示时间
        var timeID = setTimeout(showTime,1000);

    }

    /*
     ** @introduction: ajax请求
     ** @author: 杨陈鹏
     ** @date: 2015-03-26 12：58
     ** @email: yangchenpeng@cdlinglu.com
     ** @param: requestUrl string 请求地址
     ** @param: method string 请求方式
     ** @param: data object 请求参数
     ** @param: id string 节点ID
     */
    var  _ajaxRequest = function(requestUrl,method,data,id,_this){

            $.ajax({

                url : requestUrl,

                type: method,

                data: {'data':data},

                dataType: "json",

                success: function(result){

                    layer.closeAll();

                    if(typeof ajaxRequestCallback == 'function'){

                        ajaxRequestCallback(result,id,_this);

                    }else{

                        if(typeof result == 'String')
                            result = JSON.parse(result);

                        layer.msg(result.msg);

                    }

                }

            });
        }

    return{

        /*
        ** @introduction: 隐藏侧边栏
        ** @author: 杨陈鹏
        ** @date: 2015-03-26 12：58
        ** @email: yangchenpeng@cdlinglu.com
        */
        sidebar:function(){
            //一级侧边栏
            $('.sidebar-fold').unbind('click');
            $('.sidebar-fold').click(function(){
                if($('.body').hasClass('sidebar-full')){
                    $('.body').removeClass('sidebar-full').addClass('sidebar-mini');
                    $(this).html('<span class="glyphicon glyphicon-triangle-left" aria-hidden="true"></span><span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>');
                }else{
                    $('.body').removeClass('sidebar-mini').addClass('sidebar-full');
                    $(this).html('<span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span><span class="glyphicon glyphicon-triangle-left" aria-hidden="true"></span>');
                }
            });
            //二级侧边栏
            $('.action').unbind('click');
            $('.action').click(function(){
                var $parent = $(this).parent();
                if($parent.hasClass('main-sidebar-show')){
                    $parent.removeClass('main-sidebar-show').addClass('main-sidebar-hide');
                }else{
                    $parent.removeClass('main-sidebar-hide').addClass('main-sidebar-show');
                }
            });
            //二级侧边栏折叠
            $('.main-sidebar-list').delegate('li.have-child > a','click',function(event){
                event.preventDefault();  //阻止连接跳转
            });
            $('.main-sidebar-list').undelegate('li.have-child > a','click');
            $('.main-sidebar-list').delegate('li.have-child > a','click',function(){

                if($(this).parent().hasClass('_hide')){
                    $(this)
                        .parent()
                        .removeClass('_hide')
                        .addClass('_show');
                }else{
                    $(this)
                        .parent()
                        .removeClass('_show')
                        .addClass('_hide');
                }
            });
        },

        Time: function()
        {

            showTime();

        },
        //节点操作请求
        _noteAjax:function (eventNote){

            var eventNote = eventNote ? eventNote : '._ajaxNote' ;

            $('.main-body').undelegate(eventNote,'click');

            $('.main-body').delegate(eventNote,'click',function(){

                var _this = $(this);


                var msg = _this.data('msg') ? _this.data('msg') : false ;



                var data = _this.data('data');

                var requestUrl = _this.data('src') ? _this.data('src') : '' ;

                var method = _this.data('method') ? _this.data('method') : 'post' ;

                var id = _this.data('id');



                if(msg){

                    layer.msg(msg, {
                        time: 0,
                        btn: ['确定', '关闭'],
                        yes: function (i) {

                            layer.close(i);

                            _ajaxRequest(requestUrl,method,data,id,_this);

                        }
                    });

                }else{

                    _ajaxRequest(requestUrl,method,data,id,_this);

                }

            });

        },
        /*
        ** @introduction: 异步加载菜单
        ** @author: 杨陈鹏
        ** @date: 2015-03-26 12：58
        ** @email: yangchenpeng@cdlinglu.com
        ** @param: menuNode  string 菜单节点类
        */

        menuEvent: function(menuNode){
            
            action();
            menuEvents(menuNode);

        },
        /*
        ** @introduction: ajax数据请求
        ** @author: 杨陈鹏
        ** @date: 2015-03-26 12：58
        ** @email: yangchenpeng@cdlinglu.com
        ** @param: menuNode  string 菜单节点类
        */
        ajaxRequest: function(className,dataClassName){

            this._noteAjax();

            var className = className ? className : 'form._ajaxSubmit' ;

            var dataClassName = dataClassName ? dataClassName : '._data' ;


            $('.main-body').undelegate(className,'submit');

            $('.main-body').delegate(className,'submit',function(){

                var _this = $(this);

               //显示加载动画
                layer.load();

                var data = {};

                var requestUrl = _this.attr('action');

                var id = _this.attr('id');

                var method = _this.attr('method');

                $(dataClassName).each(function(i,v){

                    if($(this).attr('type') == 'radio'){

                        if($(this).is(':checked'))
                            data[$(this).attr('name')] = $(this).val();

                    // }else if($(this).attr('type') == 'checkbox'){

                    //     if($(this).is(':checked'))
                    //         data[$(this).attr('name')] += $(this).val()+',';

                    }else{

                        data[$(this).attr('name')] = $(this).val();

                    }

                });

                _ajaxRequest(requestUrl,method,data,id,_this);

                return false;

            });
        }

    }
}();

    /**
     * @introduction: 清除
     * @author: 杨陈鹏
     * @date: 2015-03-26 12：58
     * @email: yangchenpeng@cdlinglu.com
     */
    function clear(){


        //清楚服务器时时请求
        if(typeof serverTimeout != 'undefined')
            clearTimeout(serverTimeout);


    }


    /**
     * @introduction: 显示提示信息和页面跳转
     * @author: 杨陈鹏
     * @date: 2015-03-26 12：58
     * @email: yangchenpeng@cdlinglu.com
     * @param:msg String or Object 字符串时为提示信息,  对象时为所有信息
     * @param:code  状态
     * @param:url 跳转地址
     * @param:data 跳转时的post参数
     * @param:time 跳转等待时间  默认2s
     */
    function showMessage(msg,code,url,data,time)
    {


        if(typeof msg != 'string'){

            var code = msg.code ? msg.code : false ;//200成功状态,300失败状态

            var data = msg.data ? msg.data : {} ;

            var url = msg.url ? msg.url : false ;

            var time = msg.time ? msg.time : 1 ;

            var msg = msg.msg ? msg.msg : '' ;

        }else{

            var code = code ? code : false ;//200成功状态,300失败状态

            var data = data ? data : {} ;

            var url = url ? url : false ;

            var time = time ? time : 1 ;

            var msg = msg ? msg : '' ;

        }
        switch(code){

            case 200:

                layer.msg(msg,{time:time},function(){

                    if(url)
                        _load(url, data);

                });

                break;

            case 300:

                layer.msg(msg,{time:time},function(){});

                    if(url)
                        _load(url, data);

                break;

            default :

                if(url)
                    _load(url, data);

                break;

        }

        return false;


    }

    function _load(url, data){

            layer.load();

            $('#main-content').load(url,data,function(result){

                layer.closeAll();

                if(typeof showMessageCallback == 'function'){

                    showMessageCallback(result);

                }

            });
    }
    /**
     * @introduction: 剪切URL路径，组装需要规格的图片地址
     * @author: 杨陈鹏
     * @date: 2015-03-26 12：58
     * @email: yangchenpeng@cdlinglu.com
     * @param:url String 图片地址
     * @param:width Number 图片宽度
     * @param:height Number 图片高度
     */
    function cutUrl(url,width,height){

        var d = '_' + width + '_' + height;

        if(url.indexOf(d) < 0){

            var str = url.split('.');

            return str[0] + d + '.' + str[1];

        }else{

            return url;

        }
    }