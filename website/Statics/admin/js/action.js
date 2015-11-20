var Action = function(){
    return{
        /*
        ** 侧边栏隐藏&显示
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
            $('.main-sidebar-list li.have-child>a').click(function(event){
                event.preventDefault();  //阻止连接跳转
            });
            $('.main-sidebar-list li.have-child').unbind('click');
            $('.main-sidebar-list li.have-child').click(function(){

                if($(this).hasClass('_hide')){
                    $(this).removeClass('_hide').addClass('_show');
                }else{
                    $(this).removeClass('_show').addClass('_hide');
                }
            });
        }
    }
}();